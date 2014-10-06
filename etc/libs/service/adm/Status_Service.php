<?php
class Status_Service {
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
	// List Cabang
	//======================================================================

	
	public function getStatusByList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT id, n_status FROM tr_status_pengadaan where id ='22' order by n_status ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function cariCabangList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$lokasi			= $dataMasukan['lokasi'];

		if($lokasi == "" || $lokasi == "-"){	$lokasi = "-";}				

		if($kategoriCari == "") { $kategoriCari ="nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where status ='a' ";
			//$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = " lokasi = '$lokasi' ";
			
			if($katakunciCari){
			$whereOptCar = " and $kategoriCari like '%$katakunciCari%' ";
			}
			if( $lokasi != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
					
			$where = $whereBase.$whereOptCar;
			$order = " order by id_cabang ";

			$sqlProses = "SELECT *, case lokasi when '1' then 'Kantor Pusat' when '2' then 'Area' else 'Belanja Jasa' end as n_lokasi FROM ts_cabang ".$where;	
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
				//$lokasi=trim($result[$j]->lokasi);
				//$n_lokasi= $db->fetchOne("SELECT n_syarat FROM tr_syarat WHERE id = '$lokasi' ");	
				$hasilAkhir[$j] = array("id_cabang"		=>(string)$result[$j]->id_cabang,
										"lokasi"		=>(string)$result[$j]->lokasi,
										"n_lokasi"		=>(string)$result[$j]->n_lokasi,
										"nama"			=>(string)$result[$j]->nama,
										"alamat"		=>(string)$result[$j]->alamat,
										"telp"			=>(string)$result[$j]->telp,
										"fax"			=>(string)$result[$j]->fax,
										"email"			=>(string)$result[$j]->email,
										"kepala"		=>(string)$result[$j]->kepala,
										"nip"			=>(string)$result[$j]->nip,
										"anggaran"		=>(string)$result[$j]->anggaran
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function cabangInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {

			$db->beginTransaction();
			$paramInput = array("lokasi"		=>$dataMasukan['lokasi'],
								"nama"			=>$dataMasukan['nama'],
								"alamat"		=>$dataMasukan['alamat'],
								"telp"			=>$dataMasukan['telp'],
								"fax"			=>$dataMasukan['fax'],
								"email"			=>$dataMasukan['email'],
								"kepala"		=>$dataMasukan['kepala'],
								"nip"			=>$dataMasukan['nip'],
								"cdate"			=>$dataMasukan['cdate'],
								"cuid"			=>$dataMasukan['cuid']
								);

			$db->insert('ts_cabang',$paramInput);
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

	public function detailCabangById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id_cabang = '$id' ";
			$sqlProses = "SELECT * FROM ts_cabang ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			//$id_pengadaan=trim($result->id_pengadaan);
			//$paket= $db->fetchOne("SELECT paket FROM  t_pengadaan_data WHERE id_pengadaan = '$id_pengadaan' ");	
			$hasilAkhir = array("id_cabang"		=>(string)$result->id_cabang,
								"lokasi"		=>(string)$result->lokasi,
								"nama"			=>(string)$result->nama,
								"alamat"		=>(string)$result->alamat,
								"telp"			=>(string)$result->telp,
								"fax"			=>(string)$result->fax,
								"email"			=>(string)$result->email,
								"kepala"		=>(string)$result->kepala,
								"nip"			=>(string)$result->nip,
								"anggaran"		=>(string)$result->anggaran
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function cabangUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("lokasi"		=>$dataMasukan['lokasi'],
								"nama"			=>$dataMasukan['nama'],
								"alamat"		=>$dataMasukan['alamat'],
								"telp"			=>$dataMasukan['telp'],
								"fax"			=>$dataMasukan['fax'],
								"email"			=>$dataMasukan['email'],
								"kepala"		=>$dataMasukan['kepala'],
								"nip"			=>$dataMasukan['nip'],
								"mdate"			=>$dataMasukan['mdate'],
								"muid"			=>$dataMasukan['muid']
								);	
		//	var_dump($paramInput);
			$where[] = " id_cabang = '".$dataMasukan['id_cabang']."'";
			$db->update('ts_cabang',$paramInput, $where);
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

	public function cabangHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " id_cabang = '".$dataMasukan['id_cabang']."'";
			$db->delete('ts_cabang',$where);
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
