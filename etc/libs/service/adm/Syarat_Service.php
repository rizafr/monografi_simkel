<?php
class Syarat_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

	//======================================================================
	// List Pengadaansyarat
	//======================================================================

	public function getSyaratList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT * FROM tr_syarat order by n_syarat ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function cariPengadaansyaratList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$peruntukan		= $dataMasukan['peruntukan'];

		if($peruntukan == "" || $peruntukan == "-"){	$peruntukan = "-";}				

		if($kategoriCari == "") { $kategoriCari ="nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where status ='a' ";
			//$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = " peruntukan = '$peruntukan' ";
			
			if($katakunciCari){
			$whereOptCar = " and $kategoriCari like '%$katakunciCari%' ";
			}
			if( $peruntukan != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
					
			$where = $whereBase.$whereOptCar;
			$order = " order by id_syarat ";

			$sqlProses = "SELECT * FROM t_pengadaan_syarat ".$where;	
			$sqlProses1 = $sqlProses.$order;
			//echo $sqlProses1;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset,$total);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);	 
			}
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$peruntukan=trim($result[$j]->peruntukan);
				$n_syarat= $db->fetchOne("SELECT n_syarat FROM tr_syarat WHERE id = '$peruntukan' ");	
				$hasilAkhir[$j] = array("id_syarat"  	=>(string)$result[$j]->id_syarat,
										"id_pengadaan"  =>(string)$result[$j]->id_pengadaan,
										"peruntukan"	=>(string)$result[$j]->peruntukan,
										"n_syarat"		=>$n_syarat,
										"nama"			=>(string)$result[$j]->nama,
										"cdate"			=>(string)$result[$j]->cdate,
										"cuid"			=>(string)$result[$j]->cuid,
										"mdate"			=>(string)$result[$j]->mdate,
										"muid"			=>(string)$result[$j]->muid
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function pengadaansyaratInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("id_pengadaan"  =>$dataMasukan['id_pengadaan'],
								"peruntukan"	=>$dataMasukan['peruntukan'],
								"nama"			=>$dataMasukan['nama'],
								"cdate"			=>$dataMasukan['cdate'],
								"cuid"			=>$dataMasukan['cuid'],
								"status"		=>$dataMasukan['status'],
								);
			$db->insert('t_pengadaan_syarat',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			$errMsg = $errmsgArr[0];
			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function detailPengadaansyaratById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id_syarat = '$id' ";
			$sqlProses = "SELECT * FROM t_pengadaan_syarat ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			$id_pengadaan=trim($result->id_pengadaan);
			$paket= $db->fetchOne("SELECT paket FROM  t_pengadaan_data WHERE id_pengadaan = '$id_pengadaan' ");	
			$hasilAkhir = array("id_syarat"  	=>(string)$result->id_syarat,
								"id_pengadaan"  =>(string)$result->id_pengadaan,
								"n_pengadaan"	=>$paket,
								"peruntukan"	=>(string)$result->peruntukan,
								"nama"			=>(string)$result->nama,
								"cdate"			=>(string)$result->cdate,
								"cuid"			=>(string)$result->cuid,
								"mdate"			=>(string)$result->mdate,
								"muid"			=>(string)$result->muid,
								"status"		=>(string)$result->status
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function pengadaansyaratUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("id_pengadaan"  =>$dataMasukan['id_pengadaan'],
								"peruntukan"	=>$dataMasukan['peruntukan'],
								"nama"			=>$dataMasukan['nama'],
								"mdate"			=>$dataMasukan['mdate'],
								"muid"			=>$dataMasukan['muid'],
								"status"		=>$dataMasukan['status']
								);	
		//	var_dump($paramInput);
			$where[] = " id_syarat = '".$dataMasukan['id_syarat']."'";
			$db->update('t_pengadaan_syarat',$paramInput, $where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			$errMsg = $errmsgArr[0];
			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function pengadaansyaratHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " id_syarat = '".$dataMasukan['id_syarat']."'";
			$db->delete('t_pengadaan_syarat',$where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function getPengadaanList(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$kataKunci 				= strToUpper($dataMasukan['kataKunci']);
		$id_pengadaan 			= ($dataMasukan['id_pengadaan']);
		if($kataKunci == ''){$kataKunci = '';}
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sql = "SELECT * FROM t_pengadaan_data where UPPER(paket) like '%$kataKunci%' and status ='a' ";
			$result = $db->fetchAll($sql);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$mtd_pengadaan=trim($result[$j]->mtd_pengadaan);
				$model_pengadaan=trim($result[$j]->model_pengadaan);
				$mtd_evaluasi=trim($result[$j]->mtd_evaluasi);
				$mtd_pengadaan=trim($result[$j]->mtd_pengadaan);
				$n_mtd_pengadaan= $db->fetchOne("SELECT n_mtd_pengadaan FROM tr_mtd_pengadaan WHERE id = '$mtd_pengadaan'");	
				$n_model_pengadaan= $db->fetchOne("SELECT n_model_pengadaan FROM tr_model_pengadaan WHERE id = '$model_pengadaan'");	
				$n_mtd_evaluasi= $db->fetchOne("SELECT n_mtd_evaluasi FROM tr_mtd_evaluasi WHERE id = '$mtd_evaluasi'");	
				$data[$j] = array("id_pengadaan"	=>(string)$result[$j]->id_pengadaan,	
								 "paket"			=>(string)$result[$j]->paket,
								 "id_cabang"		=>(string)$result[$j]->id_cabang,
								 "mtd_pengadaan"	=>(string)$result[$j]->mtd_pengadaan,
								 "model_pengadaan"	=>(string)$result[$j]->model_pengadaan,
								 "n_mtd_pengadaan"	=>$n_mtd_pengadaan,
								 "n_model_pengadaan"=>$n_model_pengadaan,
								 "mtd_evaluasi"		=>$n_mtd_evaluasi,
								 "mtd_evaluasi"		=>(string)$result[$j]->mtd_evaluasi,
								 "opr_kualifikasi"	=>(string)$result[$j]->opr_kualifikasi,
								 "anggaran"			=>(string)$result[$j]->anggaran
								);
				}
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}

 public function limit2($sql, $count, $offset = 0,$total)
     {
        $count = intval($count);
        if ($count <= 0) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = intval($offset);
        if ($offset < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }
		$inner_sort = "desc";
		$outer_sort = "asc";
		$top2 = ($count+$offset);
		if(($count + $offset) > $total){
            //$offset = $total%$count;
			$offset = $total;
		    $inner_sort = "desc";
		    $outer_sort = "asc";
		    //$top2 = $offset;
			$top2 = $total;
			$count = $total%$count;
		}
        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
            //$sort  = (stripos($orderby, ' desc') !== false) ? 'desc' : 'asc';
            $order = str_ireplace('ORDER BY', '', $orderby);
            $order = trim(preg_replace('/\bASC\b|\bDESC\b/i', '', $order));
        }

        $sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . $top2 . ' ', $sql);

        $sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ' '.$inner_sort.') AS inner_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ';
            $sql .= $outer_sort;
        }
        $sql .= ') AS outer_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ' . $outer_sort;
        }

        return $sql;
    }
}
?>
