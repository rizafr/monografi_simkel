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
		$result = $db->fetchAll("SELECT * FROM [SIMKEL].[dbo].[m_kelurahan] K,[SIMKEL].[dbo].[mon_kelurahan] MK 
					WHERE K.kd_kel = MK.kd_kel ");
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
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$kd_kel			= $dataMasukan['kd_kel'];
		
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			if($kd_kel!='00'){ $hak = " AND K.kd_kel='$kd_kel'";}
			
			$whereOpt = " AND ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$group = "";
			$order = "";
			
			
			$sqlProses = "SELECT K.kelurahan, MK.kd_kel,KEC.kecamatan,  MK.tahun_pembentukan, MK.dasar_pembentukan, MK.kode_pos FROM [SIMKEL].[dbo].[m_kelurahan] K ,[SIMKEL].[dbo].[mon_kelurahan] MK, [SIMKEL].[dbo].[m_kecamatan] KEC WHERE K.kd_kel= MK.kd_kel AND K.kd_kec= KEC.kd_kec".$hak.$where;	
			$sqlProses1 = $sqlProses.$group;
			//var_dump($sqlProses);
			if(($pageNumber==0) && ($itemPerPage==0)){	
				$sqlTotal = "select count(*) from ($sqlProses1) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);
			}else{
				$sqlData = $sqlProses1.$order ;//." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);				
			}
			
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("kd_kel"					=> (string)$result[$j]->kd_kel,
										"kelurahan"					=> (string)$result[$j]->kelurahan,
										"tahun_pembentukan"				=> (string)$result[$j]->tahun_pembentukan,
										"dasar_pembentukan"				=> (string)$result[$j]->dasar_pembentukan,
										"kode_pos"				=> (string)$result[$j]->kode_pos,
										"kecamatan"				=> (string)$result[$j]->kecamatan,
										"kota"				=> "cimahi",
										"prov"				=> "Jawa Barat"
										
										);
			}
			return $hasilAkhir; 
			
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}

	public function cariPendaftaranList1(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= strToLower($dataMasukan['katakunciCari']);
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$kelurahan	=     $dataMasukan['kelurahan'];

		if($kategoriCari == "") { $kategoriCari ="kelurahan";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " AND K.kelurahan <> '' ";
			
			if($katakunciCari){
			$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";
			}
					
			$where = $whereOptCar;
			//$order = " order by noreg ";

			$sqlProses = "SELECT * FROM [SIMKEL].[dbo].[m_kelurahan] K ,[SIMKEL].[dbo].[mon_kelurahan] MK WHERE K.kd_kel = MK.kd_kel ";	
			$sqlProses1 = $sqlProses.$order;
		//	echo $where;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses"." "."$where)";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses1.$where." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);
			}
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("kd_kel"					=> (string)$result[$j]->kd_kel,
										"kelurahan"					=> (string)$result[$j]->kelurahan,
										"tahun_pembentukan"				=> (string)$result[$j]->tahun_pembentukan,
										"dasar_hukum_pembentukan"				=> (string)$result[$j]->dasar_hukum_pembentukan,
										"no_kode_pos"				=> (string)$result[$j]->dasar_hukum_pembentukan,
										"kecamatan"				=> (string)$result[$j]->kecamatan,
										"kota"				=> "cimahi",
										"prov"				=> "Jawa Barat"
										
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
		$paramInput	= array(	"kd_kel"					=> $dataMasukan['kd_kel'],
								"tahun_pembentukan"			=> $dataMasukan['tahun_pembentukan'],
								"dasar_pembentukan"			=> $dataMasukan['dasar_pembentukan'],
								"kode_wilayah"				=> $dataMasukan['kode_wilayah'],
								"kode_pos"					=> $dataMasukan['kode_pos'],
								"luas"						=> $dataMasukan['luas'],
								"batas_utara"				=> $dataMasukan['batas_utara'],
								"batas_selatan"				=> $dataMasukan['batas_selatan'],
								"batas_barat"				=> $dataMasukan['batas_barat'],
								"batas_timur"				=> $dataMasukan['batas_timur'],
								"jarak_dari_kecamatan"		=> $dataMasukan['jarak_dari_kecamatan'],
								"jarak_dari_kota"			=> $dataMasukan['jarak_dari_kota'],
								"jarak_dari_ibukota_kota"	=> $dataMasukan['jarak_dari_ibukota_kota'],
								"jarak_dari_ibukota_prov"	=> $dataMasukan['jarak_dari_ibukota_prov']
							);
						
			
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_kelurahan',$paramInput);
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

	public function detailPendaftaranById($kd_kel) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where MK.kd_kel = '$kd_kel' AND K.kd_kel=MK.kd_kel AND K.kd_kec=KEC.kd_kec  ";
			$sqlProses = "SELECT * 
							FROM SIMKEL.dbo.mon_kelurahan MK,SIMKEL.dbo.m_kelurahan K ,SIMKEL.dbo.m_kecamatan KEC ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir	= array("kd_kel"					=> (string)$result->kd_kel,
								"tahun_pembentukan"			=> (string)$result->tahun_pembentukan,
								"dasar_pembentukan"			=> (string)$result->dasar_pembentukan,
								"kode_wilayah"				=> (string)$result->kode_wilayah,
								"kode_pos"					=> (string)$result->kode_pos,
								"luas"						=> (string)$result->luas,
								"batas_utara"				=> (string)$result->batas_utara,
								"batas_selatan"				=> (string)$result->batas_selatan,
								"batas_barat"				=> (string)$result->batas_barat,
								"batas_timur"				=> (string)$result->batas_timur,
								"jarak_dari_kecamatan"		=> (string)$result->jarak_dari_kecamatan,
								"jarak_dari_kota"			=> (string)$result->jarak_dari_kota,
								"jarak_dari_ibukota_kota"	=> (string)$result->jarak_dari_ibukota_kota,
								"jarak_dari_ibukota_prov"	=> (string)$result->jarak_dari_ibukota_prov,
								"kelurahan"	=> (string)$result->kelurahan,
								"kecamatan"	=> (string)$result->kecamatan
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
			$paramInput	= array(
								"tahun_pembentukan"			=> $dataMasukan['tahun_pembentukan'],
								"dasar_pembentukan"			=> $dataMasukan['dasar_pembentukan'],
								"kode_wilayah"				=> $dataMasukan['kode_wilayah'],
								"kode_pos"					=> $dataMasukan['kode_pos']
								
							);
						
			
			//var_dump($dataMasukan['id']);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."'";
			$db->update('SIMKEL.dbo.mon_kelurahan',$paramInput, $where);
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
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."'";
			$db->delete('SIMKEL.dbo.mon_kelurahan', $where);
			
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
