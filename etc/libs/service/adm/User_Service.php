<?php
class Otoritasinternal_Service {
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
	// List Otoritasinternal
	//======================================================================

	public function getOtoritasinternalList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT id, nama FROM tr_otoritas_user order by nama ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function cariOtoritasinternalList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$cabang		= $dataMasukan['cabang'];

		if($cabang == "" || $cabang == "-"){	$cabang = "-";}				

		if($kategoriCari == "") { $kategoriCari ="nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where (status = 'a') AND (bs = 'b') ";
			//$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = " id_cabang = '$cabang' ";
			
			if($katakunciCari){
			$whereOptCar = " and $kategoriCari like '%$katakunciCari%' ";
			}
			if( $cabang != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
					
			$where = $whereBase.$whereOptCar;
			$order = " order by id ";

			$sqlProses = "SELECT * FROM t_user ".$where;	
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
				$id_cabang=trim($result[$j]->id_cabang);
				$n_cabang= $db->fetchOne("SELECT nama FROM ts_cabang WHERE id_cabang = '$id_cabang' ");	
				$apl=trim($result[$j]->apl);
				$n_otoritasinternal= $db->fetchOne("SELECT nama FROM tr_otoritas_user WHERE id = '$apl' ");	
				//uid, id, userid, nama, email, password, id_cabang, id_bagian, id_supp, admin, apl, 
					//userid, nama, email, password, id_cabang
				$hasilAkhir[$j] = array("id"		=>(string)$result[$j]->id,
										"userid"	=>(string)$result[$j]->userid,
										"nama"		=>(string)$result[$j]->nama,
										"email"		=>(string)$result[$j]->email,
										"password"	=>(string)$result[$j]->password,
										"id_cabang"	=>(string)$result[$j]->id_cabang,
										"apl"		=>(string)$result[$j]->apl,
										"n_cabang"  =>$n_cabang,
										"n_otoritasinternal"  =>$n_otoritasinternal
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function otoritasinternalInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("userid"		=>$dataMasukan['userid'],
								"password"  	=>md5($dataMasukan['password']),
								"nama"			=>$dataMasukan['nama'],
								"email"			=>$dataMasukan['email'],
								"id_cabang"		=>$dataMasukan['id_cabang'],
								"apl"			=>$dataMasukan['apl'],
								"admin"			=>$dataMasukan['apl'],
								"bs"			=>'b',
								"cdate"			=>$dataMasukan['cdate'],
								"cuid"			=>$dataMasukan['cuid'],
								"status"		=>$dataMasukan['status'],
								);
			//var_dump($paramInput);
			$db->insert('t_user',$paramInput);
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

	public function detailOtoritasinternalById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id = '$id' ";
			$sqlProses = "SELECT * FROM t_user ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			//$id_pengadaan=trim($result->id_pengadaan);
			//$paket= $db->fetchOne("SELECT paket FROM  t_pengadaan_data WHERE id_pengadaan = '$id_pengadaan' ");	
		//	userid, nama, email, password, id_cabang
			$hasilAkhir = array("id"  		=>(string)$result->id,
								"userid"	=>(string)$result->userid,
								"nama"		=>(string)$result->nama,
								"email"		=>(string)$result->email,
								"id_cabang"	=>(string)$result->id_cabang,
								"apl"		=>(string)$result->apl,
								"id_bagian"	=>(string)$result->id_bagian,
								"id_supp"	=>(string)$result->id_supp,
								"admin"		=>(string)$result->admin,
								"status"	=>(string)$result->status
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function otoritasinternalUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("userid"	=>$dataMasukan['userid'],
								"password"  =>md5($dataMasukan['password']),
								"nama"		=>$dataMasukan['nama'],
								"email"		=>$dataMasukan['email'],
								"id_cabang"	=>$dataMasukan['id_cabang'],
								"apl"		=>$dataMasukan['apl'],
								"admin"		=>$dataMasukan['apl'],
								"bs"		=>'b',
								//"id_bagian"	=>$dataMasukan['id_bagian'],
								//"id_supp"	=>$dataMasukan['id_supp'],
								//"admin"		=>$dataMasukan['admin'],
								"status"	=>$dataMasukan['status']
								);	
			//var_dump($paramInput);
			$where[] = " id = '".$dataMasukan['id']."'";
			$db->update('t_user',$paramInput, $where);
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

	public function otoritasinternalHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " id = '".$dataMasukan['id']."'";
			$db->delete('t_user',$where);
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
