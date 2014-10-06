<?php
class Pegawai_Pegawai_Service {
    private static $instance;
    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	public function getpegawaiListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT id, n_nama, n_nip, n_jabatan, c_upt, c_divre, c_statusdelete FROM tm_pegawai where (c_statusdelete != 'Y' or c_statusdelete is null) order by n_nama";
		//echo $sql;
		$result = $db->fetchAll($sql);
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function getpegawaiListByDivre($c_divre) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT id, n_nama, n_nip, n_jabatan, c_upt, c_divre, c_statusdelete FROM tm_pegawai where c_divre ='$c_divre' and (c_statusdelete != 'Y' or c_statusdelete is null) order by n_nama";
		//echo $sql;
		$result = $db->fetchAll($sql);
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function getpegawaiListByUpt($c_upt) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT id, n_nama, n_nip, n_jabatan, c_upt, c_divre, c_statusdelete FROM tm_pegawai where c_upt ='$c_upt' and (c_statusdelete != 'Y' or c_statusdelete is null) order by n_nama";
		//echo $sql;
		$result = $db->fetchAll($sql);
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function caripegawaiList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	    $divre			= $dataMasukan['divre'];
		$upt			= $dataMasukan['upt'];
		$jabatan		= $dataMasukan['jabatan'];

		//if(trim($divre) == ''){$divre ='-';}
		if(trim($upt) == ''){$upt ='-';}
		if(trim($jabatan) == ''){$jabatan ='-';}
		if(trim($kategoriCari) == ''){$kategoriCari ='n_nama';}
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit = $itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;

			$whereBase1 =$where.$where2.$where3.$whereOptC;
			$where = $whereBase.$whereBase1;
			$whereBase = " where (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = " c_divre = '$divre' ";
			$whereOpt2 = " c_upt = '$upt' ";
			$whereOpt3 = " n_jabatan = '$jabatan' ";
			
			if($katakunciCari){
			$whereOptCar = " and $kategoriCari like '%$katakunciCari%' ";
			}

			if( $divre != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
			if( $upt != "-"){$whereBase= $whereBase." and ".$whereOpt2;} else {$whereBase= $whereBase;}
			if( $jabatan != "-"){$whereBase= $whereBase." and ".$whereOpt3;} else {$whereBase= $whereBase;}
			
			$whereOpt =$where1.$where2.$where3.$where4.$where5.$whereOptCar;
			$where = $whereBase.$whereOpt;
			$order = " order by n_nama ";
			$sqlProses = "SELECT id, n_nama, n_nip, n_npwp, n_jabatan, c_upt, c_divre, dbo.FGetNamaUpt(c_upt) as n_upt, c_statusdelete FROM tm_pegawai ".$where;
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
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  					=>(string)$result[$j]->id,
										"n_nama"  				=>(string)$result[$j]->n_nama,
										"n_npwp"  				=>(string)$result[$j]->n_npwp,
										"n_nip"					=>(string)$result[$j]->n_nip,
										"n_jabatan"				=>(string)$result[$j]->n_jabatan,
										"c_upt"					=>(string)$result[$j]->c_upt,
										"c_divre"				=>(string)$result[$j]->c_divre,
										"n_upt"					=>(string)$result[$j]->n_upt
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function pegawaiInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db'); 
		try {
			$db->beginTransaction();

			$paramInput = array("n_nama"			=>trim($dataMasukan['n_nama']),
								"n_nip"  			=>trim($dataMasukan['n_nip']),
								"n_jabatan"  		=>trim($dataMasukan['n_jabatan']),
								"c_divre"  			=>trim($dataMasukan['c_divre']),
								"c_upt"  			=>trim($dataMasukan['c_upt']),
								"n_npwp"			=>trim($dataMasukan['n_npwp']),
								"k_npwp1"			=>trim($dataMasukan['k_npwp1']),
								"k_npwp2"			=>trim($dataMasukan['k_npwp2']),
								"k_npwp3"			=>trim($dataMasukan['k_npwp3']),
								"k_npwp4"			=>trim($dataMasukan['k_npwp4']),
								"k_npwp5"			=>trim($dataMasukan['k_npwp5']),
								"k_npwp6"			=>trim($dataMasukan['k_npwp6']),
								"i_entry"			=>$dataMasukan['i_entry'],
								"d_entry"  			=>date('m/d/Y')
								);
			$db->insert('tm_pegawai',$paramInput);
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

	
	public function detailpegawaiById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id= '$id' and (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$sqlProses = "SELECT  id, n_nama, n_nip, n_jabatan, c_divre, c_upt, n_npwp, k_npwp1, k_npwp2, k_npwp3, k_npwp4, k_npwp5, k_npwp6, c_statusdelete FROM tm_pegawai ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			$hasilAkhir =		array("id"  	=>(string)$result->id,
								"n_nama"  		=>(string)$result->n_nama,
								"n_nip"			=>(string)$result->n_nip,
								"n_jabatan"		=>(string)$result->n_jabatan,
								"c_upt"			=>(string)$result->c_upt,
								"n_npwp"			=>(string)$result->n_npwp,
								"k_npwp1"			=>(string)$result->k_npwp1,
								"k_npwp2"			=>(string)$result->k_npwp2,
								"k_npwp3"			=>(string)$result->k_npwp3,
								"k_npwp4"			=>(string)$result->k_npwp4,
								"k_npwp5"			=>(string)$result->k_npwp5,
								"k_npwp6"			=>(string)$result->k_npwp6,
								"c_divre"		=>(string)$result->c_divre
								);
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	
	public function pegawaiUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_nama"			=>trim($dataMasukan['n_nama']),
								"n_nip"  			=>trim($dataMasukan['n_nip']),
								"n_jabatan"  		=>trim($dataMasukan['n_jabatan']),
								"c_divre"  			=>trim($dataMasukan['c_divre']),
								"c_upt"  			=>trim($dataMasukan['c_upt']),
								"n_npwp"			=>trim($dataMasukan['n_npwp']),
								"k_npwp1"			=>trim($dataMasukan['k_npwp1']),
								"k_npwp2"			=>trim($dataMasukan['k_npwp2']),
								"k_npwp3"			=>trim($dataMasukan['k_npwp3']),
								"k_npwp4"			=>trim($dataMasukan['k_npwp4']),
								"k_npwp5"			=>trim($dataMasukan['k_npwp5']),
								"k_npwp6"			=>trim($dataMasukan['k_npwp6']),
								"i_update"			=>$dataMasukan['i_update'],
								"d_update"  		=>date('m/d/Y')
								);
			$where[] = " id = '".$dataMasukan['id']."'";
			$db->update('tm_pegawai',$paramInput, $where);
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


	public function pegawaiHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
			$where[] = " id = '".$dataMasukan['id']."'";
			//var_dump($paramInput);
			$db->update('tm_pegawai',$paramInput, $where);
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

public function cekData($n_nip) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where  n_nip = '$n_nip'  and (c_statusdelete != 'Y' or c_statusdelete is null) ";
			$sqlProses = "SELECT  id FROM tm_pegawai ";	
			$sqlData = $sqlProses.$where;
			//echo $sqlData;
			$result = $db->fetchRow($sqlData);	
			$hasilAkhir = array("id"  			=>(string)$result->id
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }
		$inner_sort = "asc";
		$outer_sort = "desc";
		$top2 = ($count+$offset);

		if(($count + $offset) > $total){
			$offset = $total;
		    $inner_sort = "asc";
		    $outer_sort = "desc";

			$top2 = $total;
			$count = $total%$count;
		}
        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
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
            $sql .= ' ORDER BY ' . $order . ' ' . "asc";
        }

        return $sql;
    }

}
?>
