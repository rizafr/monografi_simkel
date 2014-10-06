<?php
class Tamu_Kritik_Service {
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
	// List Pengumuman
	//======================================================================
	public function cariKritikList(array $dataMasukan,$pageNumber,$itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			$whereBase = " where STATUS = 'AKTIF' ";
			$whereOpt = "  $kategoriCari like '%$katakunciCari%'  ";
			if($kategoriCari != "" && $katakunciCari != "" ) { 
				$where = $whereBase." and ".$whereOpt;
			} 
			else { 
				$where = $whereBase;
			}
			$order = " order by ID_SISTEM_WEB_SARAN ";
			$sqlProses = "SELECT ID_SISTEM_WEB_SARAN, dbo.FGetTglMachineToHuman(TGL_SARAN) as TGL_SARAN, NAMA, TELP, SARAN, STATUS FROM SISTEM_WEB_SARAN ".$where;	
			$sqlProses1 = $sqlProses.$order;

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
				$hasilAkhir[$j] = array("ID_SISTEM_WEB_SARAN"  	=>(string)$result[$j]->ID_SISTEM_WEB_SARAN,
									   "TGL_SARAN"      		=>(string)$result[$j]->TGL_SARAN,
									   "NAMA"      				=>(string)$result[$j]->NAMA,
									   "TELP"      				=>(string)$result[$j]->TELP,
									   "SARAN"      			=>(string)$result[$j]->SARAN,
									   "STATUS"      			=>(string)$result[$j]->STATUS
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function kritikInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();

			$sql = "select  MAX(ID_SISTEM_WEB_SARAN) as id from SISTEM_WEB_SARAN  ";
			$id = $db->fetchOne($sql);
			$id = $id + 1;
			
			if($id < 10) { $id = "00".$id;}
			else  if($id <100){ $id = "0".$id;} 
			else {$id = "001";}
			

			$paramInput = array("ID_SISTEM_WEB_SARAN"		=>$id,
							   "TGL_SARAN"					=>$dataMasukan['TGL_SARAN'],
							   "NAMA"						=>$dataMasukan['NAMA'],
							   "TELP"						=>$dataMasukan['TELP'],
							   "SARAN"						=>$dataMasukan['SARAN']
								);	

			$db->insert('SISTEM_WEB_SARAN',$paramInput);
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

	public function detailKritikById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = "where ID_SISTEM_WEB_SARAN = '$id' ";
			$sqlProses = "SELECT ID_SISTEM_WEB_SARAN, dbo.FGetTglMachineToHuman(TGL_SARAN) as TGL_SARAN, NAMA, TELP, SARAN, STATUS FROM SISTEM_WEB_SARAN ";

			$sqlData = $sqlProses.$where;					
			
			$result = $db->fetchRow($sqlData);
			$hasilAkhir = array("id"  =>(string)$result->ID_SISTEM_WEB_SARAN,
							   "TGL_SARAN"  		=>(string)$result->TGL_SARAN,
							   "NAMA"  				=>(string)$result->NAMA,
							   "TELP"  				=>(string)$result->TELP,
							   "SARAN"  			=>(string)$result->SARAN,
							   "STATUS"  			=>(string)$result->STATUS
								);
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function cek($n_sk,$bulan,$thn) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where n_sk= '$n_sk' and month(d_ajuan) = '$bulan' and year(d_ajuan) = '$thn' and (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$sqlProses = "SELECT ID_SISTEM_WEB_SARAN, dbo.FGetTglMachineToHuman(TGL_SARAN) as TGL_SARAN, NAMA, TELP, SARAN, STATUS FROM SISTEM_WEB_SARAN  ";	

			$sqlData = $sqlProses.$where;

			$result = $db->fetchRow($sqlData);	

			$hasilAkhir = array("id"  			=>(string)$result->id
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function kritikUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("NAMA"				=>$dataMasukan['NAMA'],
							   "TELP"			=>$dataMasukan['TELP'],
							   "SARAN"			=>$dataMasukan['SARAN']
								);	

			$where[] = "ID_SISTEM_WEB_SARAN = '".$dataMasukan['id']."'";
			$db->update('SISTEM_WEB_SARAN',$paramInput, $where);
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

	public function ubahStatus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("STATUS"				=>$dataMasukan['STATUS']
								);	

			$where[] = "ID_SISTEM_WEB_SARAN = '".$dataMasukan['id']."'";
			$db->update('SISTEM_WEB_SARAN',$paramInput, $where);
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

	public function kritikHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("STATUS"	=> 'TIDAK AKTIF'
								);	
								
			$where[] = "ID_SISTEM_WEB_SARAN = '".$dataMasukan['ID_SISTEM_WEB_SARAN']."'";
			
			$db->update('SISTEM_WEB_SARAN',$paramInput, $where);
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
               $offset = $total%$count;
			   $inner_sort = "asc";
			   $outer_sort = "asc";
			   $top2 = $offset;
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