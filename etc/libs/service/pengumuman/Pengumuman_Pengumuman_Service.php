<?php
class Pengumuman_Pengumuman_Service {
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
	// List pengumuman
	//======================================================================

	public function getjenispengumumanListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql ="SELECT id, n_status, c_statusdelete, i_entry, d_entry FROM tr_statusPengumuman order by  n_status  ";
	//	echo $sql;
		$result = $db->fetchAll($sql);
				
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function caripengumumanList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	    $prodi			= $dataMasukan['prodi'];
		$jenisPengumuman	= $dataMasukan['jenisPengumuman'];
		$status			= $dataMasukan['status'];
		$kegiatan		= $dataMasukan['kegiatan'];

		if(trim($status) == ''){$status ='-';}

	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit = $itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;

			$whereBase = " where (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			//$whereOpt2 = " and c_statusPengumuman	= '$status' ";
				
			//$whereOpt1C = " c_prodi = '$prodi' ";
			//$whereOpt2C = " c_statusPengumuman	= '$status' ";

		
			if($katakunciCari != "") { $whereOptCar = " and ".$whereOpt;} else { $whereOptCar = "";}
			//if($prodi != "-" ){ $where1 = " and ".$whereOpt1C;}else {$where1 = "";} 
			//if($status != "-" ) { $where2 = " and ".$whereOpt2C;}else {$where2 = "";}  

			$whereBase1 =$where1.$where2.$whereOpt3.$whereOpt4.$whereOptCar;
			$where = $whereBase.$whereBase1;

			//if($katakunciCari != "") {$where =$whereBase.$where1.$where2.$where3.$where4.$whereOptCar; }
			//if($katakunciCari != "") {$where =$whereBase.$where1.$where2.$where3.$where4.$whereOptCar; }

			$order = " order by e_pengumuman ";
			$sqlProses = "SELECT id, e_pengumuman, c_statusaktif, c_statusdelete, i_entry, d_entry
FROM tm_pengumuman   ".$where;
		
			$sqlProsesP = $sqlProses.$order;
			//echo $sqlProsesP; 
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) as tbl";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlProses2 = $this->limit2($sqlProsesP,$itemPerPage, $xOffset,$total);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);	
			}
		//	echo $sqlData;
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  							=>(string)$result[$j]->id,
										"e_pengumuman"  				=>(string)$result[$j]->e_pengumuman,
										"c_statusaktif"					=>(string)$result[$j]->c_statusaktif
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function pengumumanInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db'); 
		try {
			$db->beginTransaction();

			$paramInput = array("e_pengumuman"			=>$e_pengumuman,
								"i_entry"			=>$dataMasukan['i_entry'],
								"d_entry"  			=>date('m/d/Y')
								);

			$db->insert('tm_pengumuman',$paramInput);
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

	
	public function detailpengumumanById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id= '$id' and (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$sqlProses = "SELECT id, e_pengumuman, c_statusaktif, c_statusdelete, i_entry, d_entry FROM tm_pengumuman  ";	

			$sqlData = $sqlProses.$where;
			//echo $sqlData;
			$result = $db->fetchRow($sqlData);	

			$hasilAkhir =		array("id"  	=>(string)$result->id,
								"e_pengumuman"  =>(string)$result->e_pengumuman
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function cekRek($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id_pengumuman= '$id' and (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$sqlProses = "SELECT id, n_namarek, id_pengumuman, n_rek, c_statusdelete, d_delete, i_entry, d_entry, i_update, d_update FROM tm_rek  ";	

			$sqlData = $sqlProses.$where;

			$result = $db->fetchRow($sqlData);	

			$hasilAkhir =		array("id"  	=>(string)$result->id
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function pengumumanUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();

			$paramInput = array("e_pengumuman"			=>$dataMasukan['e_pengumuman'],
								"i_update"			=>$dataMasukan['i_update'],
								"d_update"  		=>date('m/d/Y')
								);

			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_pengumuman',$paramInput, $where);
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


	public function pengumumanHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y'
								);	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_pengumuman',$paramInput, $where);
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
		///echo "count-------->".$count;
       // echo "total-------->".$total;
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
		$inner_sort = "asc";
		$outer_sort = "asc";
		$top2 = ($count+$offset);
      // echo "offset-------->".$offset;

		if(($count + $offset) > $total){
            //$offset = $total%$count;
			$offset = $total;
		    $inner_sort = "asc";
		    $outer_sort = "desc";
		    //$top2 = $offset;
			$top2 = $total;
			$count = $total%$count;
		}
        $orderby = stristr($sql, 'ORDER BY');
      //  echo "orderby-------->".$orderby;
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
