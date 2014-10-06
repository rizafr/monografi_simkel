<?php
class Pendaftaran_Service {
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
	// List Pendaftaran
	//======================================================================

	public function getPendaftaranList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT id, n_nama FROM tr_otoritas_user order by n_nama ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function cariPendaftaranList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= strToLower($dataMasukan['katakunciCari']);
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$id_pendaftar	= $dataMasukan['id_pendaftar'];

		if($kategoriCari == "") { $kategoriCari ="n_nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where c_status <> '' and c_status <> 'D' ";
			
			if($katakunciCari){
			$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";
			}
					
			$where = $whereOptCar;
			//$order = " order by noreg ";

			$sqlProses = "SELECT * FROM t_siswa ";	
			$sqlProses1 = $sqlProses.$order;
		//	echo $where;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses"." "."$where) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses1.$where." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);
			}
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_noreg"					=> (string)$result[$j]->c_noreg,
										"n_nama"					=> (string)$result[$j]->n_nama,
										"c_kelamin"				=> (string)$result[$j]->c_kelamin,
										"n_tempat"				=> (string)$result[$j]->n_tempat
										
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function pendaftaranInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
		/*$nama = subStr(trim(strToupper($dataMasukan['n_nama'])),0,1);
		$urut = $db->fetchOne("select count(*) as jum from t_pasien where substring(n_nama,0,1) ='$nama' and c_status <> 'd' and c_status <> '' ");
		if($urut){$k_urut = $urut;}
		else{$k_urut = '1';}

		if(strlen($k_urut) == '1'){$digit ="000".$k_urut;}
		if(strlen($k_urut) == '2'){$digit ="00".$k_urut;}
		if(strlen($k_urut) == '3'){$digit ="0".$k_urut;}
		if(strlen($k_urut) == '4'){$digit =$k_urut;}

			$kode_pasien =$nama."-".$digit;*/
			$paramInput = array("c_noreg"			=> $dataMasukan['c_noreg'],
								"n_nama"				=> $dataMasukan['n_nama'],
								"c_kelamin"				=> $dataMasukan['c_kelamin'],
								"t_waktu"			=> $dataMasukan['t_waktu'],
								"n_tempat"				=> $dataMasukan['n_tempat'],
								"t_berat"				=> $dataMasukan['t_berat'],
								"t_tinggi"			=> $dataMasukan['t_tinggi'],
								"t_tekanan"				=> $dataMasukan['t_tekanan'],
								"t_denyut"				=> $dataMasukan['t_denyut'],
								"t_frequensi"			=> $dataMasukan['t_frequensi'],
								"t_suhu"				=> $dataMasukan['t_suhu'],
								"n_mata"				=> $dataMasukan['n_mata'],
								"n_tht"				=> $dataMasukan['n_tht'],
								"n_gigi"			=> $dataMasukan['n_gigi'],
								"n_leher"				=> $dataMasukan['n_leher'],
								"n_jantung"				=> $dataMasukan['n_jantung'],
								"n_paru"			=> $dataMasukan['n_paru'],
								"n_perut"				=> $dataMasukan['n_perut'],
								"n_gerak"				=> $dataMasukan['n_gerak'],
								"n_gizi"				=> $dataMasukan['n_gizi'],
								"n_potensi"				=> $dataMasukan['n_potensi'],
								"n_mental"				=> $dataMasukan['n_mental'],
								"n_reproduksi"				=> $dataMasukan['n_reproduksi'],
								"n_kematangan"				=> $dataMasukan['n_kematangan'],
								"n_hb"				=> $dataMasukan['n_hb'],
								"n_feses"				=> $dataMasukan['n_feses'],
								"n_jasmani"				=> $dataMasukan['n_jasmani'],
								"c_bayi"				=> $dataMasukan['c_bayi'],
								"c_imunisasi1"				=> $dataMasukan['c_imunisasi1'],
								"c_imunisasi2"				=> $dataMasukan['c_imunisasi2'],
								"c_imunisasi3"				=> $dataMasukan['c_imunisasi3'],
								
									
								
								);
			//var_dump($paramInput);
			$db->insert('t_siswa',$paramInput);
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

	public function detailPendaftaranById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where c_noreg = '$id' ";
			$sqlProses = "SELECT * FROM t_siswa ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array("c_noreg"   	=>(string)$result->c_noreg,
								"n_nama"		=>(string)$result->n_nama,
								"t_waktu" 	=>(string)$result->t_waktu,
								"n_tempat"	=>(string)$result->n_tempat,
								"t_berat"		=>(string)$result->t_berat,
								"t_tinggi"	=>(string)$result->t_tinggi,
								"t_tekanan"	=>(string)$result->t_tekanan,
								"t_denyut"	=>(string)$result->t_denyut,
								"t_frequensi"	=>(string)$result->t_frequensi,
								"t_suhu"	=>(string)$result->t_suhu,
								"n_mata"	=>(string)$result->n_mata,
								"n_tht"	=>(string)$result->n_tht,
								"n_gigi"	=>(string)$result->n_gigi,
								"n_leher"	=>(string)$result->n_leher,
								"n_jantung"	=>(string)$result->n_jantung,
								"n_paru"	=>(string)$result->n_paru,
								"n_perut"	=>(string)$result->n_perut,
								"n_gerak"	=>(string)$result->n_gerak,
								"n_gizi"	=>(string)$result->n_gizi,
								"n_potensi"	=>(string)$result->n_potensi,
								"n_mental"	=>(string)$result->n_mental,
								"n_reproduksi"	=>(string)$result->n_reproduksi,
								"n_kematangan"	=>(string)$result->n_kematangan,
								"n_hb"	=>(string)$result->n_hb,
								"n_feses"	=>(string)$result->n_feses,
								"n_jasmani"	=>(string)$result->n_jasmani,
								"n_bayi"	=>(string)$result->n_bayi,
								
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function pendaftaranUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_noreg"   	=>(string)$result->c_noreg,
								"n_nama"		=>(string)$result->n_nama,
								"t_waktu" 	=>(string)$result->t_waktu,
								"n_tempat"	=>(string)$result->n_tempat,
								"t_berat"		=>(string)$result->t_berat,
								"t_tinggi"	=>(string)$result->t_tinggi,
								"t_tekanan"	=>(string)$result->t_tekanan,
								"t_denyut"	=>(string)$result->t_denyut,
								"t_frequensi"	=>(string)$result->t_frequensi,
								"t_suhu"	=>(string)$result->t_suhu,
								"n_mata"	=>(string)$result->n_mata,
								"n_tht"	=>(string)$result->n_tht,
								"n_gigi"	=>(string)$result->n_gigi,
								"n_leher"	=>(string)$result->n_leher,
								"n_jantung"	=>(string)$result->n_jantung,
								"n_paru"	=>(string)$result->n_paru,
								"n_perut"	=>(string)$result->n_perut,
								"n_gerak"	=>(string)$result->n_gerak,
								"n_gizi"	=>(string)$result->n_gizi,
								"n_potensi"	=>(string)$result->n_potensi,
								"n_mental"	=>(string)$result->n_mental,
								"n_reproduksi"	=>(string)$result->n_reproduksi,
								"n_kematangan"	=>(string)$result->n_kematangan,
								"n_hb"	=>(string)$result->n_hb,
								"n_feses"	=>(string)$result->n_feses,
								"n_jasmani"	=>(string)$result->n_jasmani,
								"n_bayi"	=>(string)$result->n_bayi,
								);	
			//var_dump($dataMasukan['id']);
			$where[] = " noreg = '".$dataMasukan['noreg']."'";
			$db->update('t_siswa',$paramInput, $where);
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


	public function pendaftaranHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_status" =>'D');	
			$where[] = " noreg = '".$dataMasukan['id']."'";
			$db->delete('t_siswa', $where);
			
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


public function medrecList($kode_pasien) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kode_pasien = '$kode_pasien' ";
			$sqlProses = "SELECT * FROM t_medrec ";	
			$sqlData = $sqlProses.$where;
			//echo $sqlData;
			$result = $db->fetchAll($sqlData);	
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) { 

			$c_klasifikasi = (string)$result[$j]->c_klasifikasi;
			$n_klasifikasi = $db->fetchOne("Select n_klasifikasi from tr_klasifikasi_med where id_klasifikasi ='$c_klasifikasi'");
			$c_tindakan = (string)$result[$j]->c_tindakan;
			$n_tindakan = $db->fetchOne("Select n_tindakan from tr_tindakan where id_tindakan ='$c_tindakan' ");
				
			$hasilAkhir[$j] = array("id"					=> (string)$result[$j]->id,
									"kode_pasien"           => (string)$result[$j]->kode_pasien,
									"n_nama"				=> (string)$result[$j]->n_nama,
									"d_medrec"	            => (string)$result[$j]->d_medrec,
									"c_klasifikasi"	        => (string)$result[$j]->c_klasifikasi,
									"c_tindakan"	        => (string)$result[$j]->c_tindakan,
									"n_klasifikasi"         => $n_klasifikasi,
									"n_tindakan"	        => $n_tindakan,
									"c_alergi"				=> (string)$result[$j]->c_alergi,
									"c_rematik"				=> (string)$result[$j]->c_rematik
								);
		}
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
