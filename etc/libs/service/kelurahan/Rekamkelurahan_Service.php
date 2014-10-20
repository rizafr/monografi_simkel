<?php
class Rekamkelurahan_Service {
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
	// List Rekamkelurahan
	//======================================================================

	public function getKelurahanList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT * FROM [SIMKEL].[dbo].[m_kelurahan]");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function cariRekamkelurahanList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {
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

			$whereOpt = " AND ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$group = "";
			$order = "";
			
			
			$sqlProses = "SELECT K.kelurahan, P.[tahun] ,P.[bulan],P.[kd_kel]  ,P.[nip_lurah] ,P.[gol_lurah] ,P.[nama_lurah] ,P.[nama_seklur] FROM [SIMKEL].[dbo].[mon_personil] P, [SIMKEL].[dbo].[m_kelurahan] K WHERE P.kd_kel = K.kd_kel AND P.kd_Kel= $kd_kel";//".$where;	
			$sqlProses1 = $sqlProses.$where.$group.$order;
			//var_dump($sqlProses1);
			//var_dump($sqlProses);
			if(($pageNumber==0) && ($itemPerPage==0)){	
				$sqlTotal = "select count(*) from ($sqlProses1) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);
			}else{
				$sqlData = $sqlProses1.$order;//." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);				
			}
			
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array(
										"kelurahan"					=> (string)$result[$j]->kelurahan,
										"kd_kel"					=> (string)$result[$j]->kd_kel,
										"tahun"					=> (string)$result[$j]->tahun,
										"bulan"					=> (string)$result[$j]->bulan,
										"nama_lurah"			=> (string)$result[$j]->nama_lurah,
										"nip_lurah"				=> (string)$result[$j]->nip_lurah
										);
			}
			return $hasilAkhir; 
			
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}

	public function detailKelurahanByPeriode($kd_kel){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchRow("SELECT MIN(U.bulan) bulan, MIN(U.tahun) tahun FROM [SIMKEL].[dbo].[mon_umum] U where U.kd_kel ='$kd_kel'");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}

	public function detailKelurahanByKode($kd_kel){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchRow("SELECT K.kd_kel, KEC.kecamatan, K.kelurahan, MK.* FROM [SIMKEL].[dbo].[mon_kelurahan] MK,[SIMKEL].[dbo].[m_kelurahan] K,[SIMKEL].[dbo].[m_kecamatan] KEC where MK.kd_kel=K.kd_kel AND K.kd_kec=KEC.kd_kec AND K.kd_kel = '$kd_kel'");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}
		
		public function getJumlahPusat($kd_kel,$bulan,$tahun){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchOne("SELECT  COUNT(*) AS jumPusat from [SIMKEL].[dbo].[mon_program_kelurahan] where kd_kel='$kd_kel' AND bulan='$bulan' AND tahun='$tahun' AND kode='1' ");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}
		
		public function getJumlahProvinsi($kd_kel,$bulan,$tahun){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchOne("SELECT  COUNT(*) AS jumProvinsi from [SIMKEL].[dbo].[mon_program_kelurahan] where kd_kel='$kd_kel' AND bulan='$bulan' AND tahun='$tahun' AND kode='2'");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}
		
		public function getJumlahKota($kd_kel,$bulan,$tahun){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchOne("SELECT  COUNT(*) AS jumKota from [SIMKEL].[dbo].[mon_program_kelurahan] where kd_kel='$kd_kel' AND bulan='$bulan' AND tahun='$tahun' AND kode='3'");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}
		

	///////////////////////////////////////////////////////////////////////
	public function getTipologi($kd_kel,$bulan,$tahun){
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchRow("SELECT tipologi,sarana_kantor,sarana_puskesmas FROM SIMKEL.dbo.mon_umum where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun'  ");
				
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
		}
	
	
	//insert ke tabel dbo.mon_umum
	public function umumInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"tipologi"	           	 	=> $dataMasukan['tipologi'],
								"jml_jiwa"	            	=> $dataMasukan['jml_jiwa'],
								"jml_kk"	            	=> $dataMasukan['jml_kk'],
								"jml_laki"	       			=> $dataMasukan['jml_laki'],
								"jml_perempuan"	        	=> $dataMasukan['jml_perempuan'],
								"jml_0_15"         			=> $dataMasukan['jml_0_15'],
								"jml_15_65"	            	=> $dataMasukan['jml_15_65'],
								"jml_65_keatas"        		=> $dataMasukan['jml_65_keatas'],
								"jml_pns"					=> $dataMasukan['jml_pns'],
								"jml_abri"					=> $dataMasukan['jml_abri'],
								"jml_swasta"        		=> $dataMasukan['jml_swasta'],
								"jml_wiraswasta"        	=> $dataMasukan['jml_wiraswasta'],
								"jml_tani"					=> $dataMasukan['jml_tani'],
								"jml_pertukangan"			=> $dataMasukan['jml_pertukangan'],
								"jml_buruh_tani"			=> $dataMasukan['jml_buruh_tani'],
								"jml_pensiunan"				=> $dataMasukan['jml_pensiunan'],
								"jml_nelayan"				=> $dataMasukan['jml_nelayan'],
								"jml_pemulung"				=> $dataMasukan['jml_pemulung'],
								"jml_jasa"					=> $dataMasukan['jml_jasa'],
								"jml_lulusan_tk"			=> $dataMasukan['jml_lulusan_tk'],
								"jml_lulusan_sd"			=> $dataMasukan['jml_lulusan_sd'],
								"jml_lulusan_smp"			=> $dataMasukan['jml_lulusan_smp'],
								"jml_lulusan_sma"			=> $dataMasukan['jml_lulusan_sma'],
								"jml_lulusan_diploma"		=> $dataMasukan['jml_lulusan_diploma'],
								"jml_lulusan_sarjana"		=> $dataMasukan['jml_lulusan_sarjana'],
								"jml_lulusan_pascasarjana"	=> $dataMasukan['jml_lulusan_pascasarjana'],
								"jml_lulusan_pontren"		=> $dataMasukan['jml_lulusan_pontren'],
								"jml_lulusan_keagamaan"		=> $dataMasukan['jml_lulusan_keagamaan'],
								"jml_lulusan_slb"			=> $dataMasukan['jml_lulusan_slb'],
								"jml_lulusan_kursus"		=> $dataMasukan['jml_lulusan_kursus'],
								"jml_jiwa_miskin"			=> $dataMasukan['jml_jiwa_miskin'],
								"jml_jiwa_kk"				=> $dataMasukan['jml_jiwa_kk'],
								"umr"						=> $dataMasukan['umr'],
								"sarana_kantor"				=> $dataMasukan['sarana_kantor'],
								"sarana_puskesmas"			=> $dataMasukan['sarana_puskesmas'],
								"sarana_jml_posyandu"		=> $dataMasukan['sarana_jml_posyandu'],
								"sarana_jml_poliklinik"		=> $dataMasukan['sarana_jml_poliklinik'],
								"sarana_jml_paud"			=> $dataMasukan['sarana_jml_paud'],
								"sarana_jml_tk"				=> $dataMasukan['sarana_jml_tk'],
								"sarana_jml_sd"				=> $dataMasukan['sarana_jml_sd'],
								"sarana_jml_smp"			=> $dataMasukan['sarana_jml_smp'],
								"sarana_jml_sma"			=> $dataMasukan['sarana_jml_sma'],
								"sarana_jml_pt"				=> $dataMasukan['sarana_jml_pt'],
								"sarana_jml_masjid"			=> $dataMasukan['sarana_jml_masjid'],
								"sarana_jml_mushola"		=> $dataMasukan['sarana_jml_mushola'],
								"sarana_jml_gereja"			=> $dataMasukan['sarana_jml_gereja'],
								"sarana_jml_pura"			=> $dataMasukan['sarana_jml_pura'],
								"sarana_jml_vihara"			=> $dataMasukan['sarana_jml_vihara'],
								"sarana_jml_klenteng"		=> $dataMasukan['sarana_jml_klenteng'],
								"sarana_jml_olahraga"		=> $dataMasukan['sarana_jml_olahraga'],
								"sarana_jml_kesenian"		=> $dataMasukan['sarana_jml_kesenian'],
								"sarana_jml_balai_pertemuan"=> $dataMasukan['sarana_jml_balai_pertemuan'],
								"sarana_jml_lainnya"		=> $dataMasukan['sarana_jml_lainnya']			
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_umum',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			// var_dump($errmsgArr);
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
	
	//update tabel dbo.mon_umum
	public function umumUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"tipologi"	           	 	=> $dataMasukan['tipologi'],
								"jml_jiwa"	            	=> $dataMasukan['jml_jiwa'],
								"jml_kk"	            	=> $dataMasukan['jml_kk'],
								"jml_laki"	       			=> $dataMasukan['jml_laki'],
								"jml_perempuan"	        	=> $dataMasukan['jml_perempuan'],
								"jml_0_15"         			=> $dataMasukan['jml_0_15'],
								"jml_15_65"	            	=> $dataMasukan['jml_15_65'],
								"jml_65_keatas"        		=> $dataMasukan['jml_65_keatas'],
								"jml_pns"					=> $dataMasukan['jml_pns'],
								"jml_abri"					=> $dataMasukan['jml_abri'],
								"jml_swasta"        		=> $dataMasukan['jml_swasta'],
								"jml_wiraswasta"        	=> $dataMasukan['jml_wiraswasta'],
								"jml_tani"					=> $dataMasukan['jml_tani'],
								"jml_pertukangan"			=> $dataMasukan['jml_pertukangan'],
								"jml_buruh_tani"			=> $dataMasukan['jml_buruh_tani'],
								"jml_pensiunan"				=> $dataMasukan['jml_pensiunan'],
								"jml_nelayan"				=> $dataMasukan['jml_nelayan'],
								"jml_pemulung"				=> $dataMasukan['jml_pemulung'],
								"jml_jasa"					=> $dataMasukan['jml_jasa'],
								"jml_lulusan_tk"			=> $dataMasukan['jml_lulusan_tk'],
								"jml_lulusan_sd"			=> $dataMasukan['jml_lulusan_sd'],
								"jml_lulusan_smp"			=> $dataMasukan['jml_lulusan_smp'],
								"jml_lulusan_sma"			=> $dataMasukan['jml_lulusan_sma'],
								"jml_lulusan_diploma"		=> $dataMasukan['jml_lulusan_diploma'],
								"jml_lulusan_sarjana"		=> $dataMasukan['jml_lulusan_sarjana'],
								"jml_lulusan_pascasarjana"	=> $dataMasukan['jml_lulusan_pascasarjana'],
								"jml_lulusan_pontren"		=> $dataMasukan['jml_lulusan_pontren'],
								"jml_lulusan_keagamaan"		=> $dataMasukan['jml_lulusan_keagamaan'],
								"jml_lulusan_slb"			=> $dataMasukan['jml_lulusan_slb'],
								"jml_lulusan_kursus"		=> $dataMasukan['jml_lulusan_kursus'],
								"jml_jiwa_miskin"		=> $dataMasukan['jml_jiwa_miskin'],
								"jml_jiwa_kk"				=> $dataMasukan['jml_jiwa_kk'],
								"umr"						=> $dataMasukan['umr'],
								"sarana_kantor"				=> $dataMasukan['sarana_kantor'],
								"sarana_puskesmas"			=> $dataMasukan['sarana_puskesmas'],
								"sarana_jml_posyandu"		=> $dataMasukan['sarana_jml_posyandu'],
								"sarana_jml_poliklinik"		=> $dataMasukan['sarana_jml_poliklinik'],
								"sarana_jml_paud"			=> $dataMasukan['sarana_jml_paud'],
								"sarana_jml_tk"				=> $dataMasukan['sarana_jml_tk'],
								"sarana_jml_sd"				=> $dataMasukan['sarana_jml_sd'],
								"sarana_jml_smp"			=> $dataMasukan['sarana_jml_smp'],
								"sarana_jml_sma"			=> $dataMasukan['sarana_jml_sma'],
								"sarana_jml_pt"				=> $dataMasukan['sarana_jml_pt'],
								"sarana_jml_masjid"			=> $dataMasukan['sarana_jml_masjid'],
								"sarana_jml_mushola"		=> $dataMasukan['sarana_jml_mushola'],
								"sarana_jml_gereja"			=> $dataMasukan['sarana_jml_gereja'],
								"sarana_jml_pura"			=> $dataMasukan['sarana_jml_pura'],
								"sarana_jml_vihara"			=> $dataMasukan['sarana_jml_vihara'],
								"sarana_jml_klenteng"		=> $dataMasukan['sarana_jml_klenteng'],
								"sarana_jml_olahraga"		=> $dataMasukan['sarana_jml_olahraga'],
								"sarana_jml_kesenian"		=> $dataMasukan['sarana_jml_kesenian'],
								"sarana_jml_balai_pertemuan"=> $dataMasukan['sarana_jml_balai_pertemuan'],
								"sarana_jml_lainnya"		=> $dataMasukan['sarana_jml_lainnya']			
								
								);
			//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_umum',$paramInput, $where);
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
	
	//hapus umum
	public function umumHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_umum', $where);
			
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
	
	//UPDATE DAN DETAIL UMUM
	public function detailUmum($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_umum ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"tipologi"	           	 	=> (string)$result->tipologi,
								"jml_jiwa"	            	=> (string)$result->jml_jiwa,
								"jml_kk"	            	=> (string)$result->jml_kk,
								"jml_laki"	       			=> (string)$result->jml_laki,
								"jml_perempuan"	        	=> (string)$result->jml_perempuan,
								"jml_0_15"         			=> (string)$result->jml_0_15,
								"jml_15_65"	            	=> (string)$result->jml_15_65,
								"jml_65_keatas"        		=> (string)$result->jml_65_keatas,
								"jml_pns"					=> (string)$result->jml_pns,
								"jml_abri"					=> (string)$result->jml_abri,
								"jml_swasta"        		=> (string)$result->jml_swasta,
								"jml_wiraswasta"        	=> (string)$result->jml_wiraswasta,
								"jml_tani"					=> (string)$result->jml_tani,
								"jml_pertukangan"			=> (string)$result->jml_pertukangan,
								"jml_buruh_tani"			=> (string)$result->jml_buruh_tani,
								"jml_pensiunan"				=> (string)$result->jml_pensiunan,
								"jml_nelayan"				=> (string)$result->jml_nelayan,
								"jml_pemulung"				=> (string)$result->jml_pemulung,
								"jml_jasa"					=> (string)$result->jml_jasa,
								"jml_lulusan_tk"			=> (string)$result->jml_lulusan_tk,
								"jml_lulusan_sd"			=> (string)$result->jml_lulusan_sd,
								"jml_lulusan_smp"			=> (string)$result->jml_lulusan_smp,
								"jml_lulusan_sma"			=> (string)$result->jml_lulusan_sma,
								"jml_lulusan_diploma"		=> (string)$result->jml_lulusan_diploma,
								"jml_lulusan_sarjana"		=> (string)$result->jml_lulusan_sarjana,
								"jml_lulusan_pascasarjana"	=> (string)$result->jml_lulusan_pascasarjana,
								"jml_lulusan_pontren"		=> (string)$result->jml_lulusan_pontren,
								"jml_lulusan_keagamaan"		=> (string)$result->jml_lulusan_keagamaan,
								"jml_lulusan_slb"			=> (string)$result->jml_lulusan_slb,
								"jml_lulusan_kursus"		=> (string)$result->jml_lulusan_kursus,
								"jml_jiwa_miskin"			=> (string)$result->jml_jiwa_miskin,
								"jml_jiwa_kk"				=> (string)$result->jml_jiwa_kk,
								"umr"						=> (string)$result->umr,
								"sarana_kantor"				=> (string)$result->sarana_kantor,
								"sarana_puskesmas"			=> (string)$result->sarana_puskesmas,
								"sarana_jml_posyandu"		=> (string)$result->sarana_jml_posyandu,
								"sarana_jml_poliklinik"		=> (string)$result->sarana_jml_poliklinik,
								"sarana_jml_paud"			=> (string)$result->sarana_jml_paud,
								"sarana_jml_tk"				=> (string)$result->sarana_jml_tk,
								"sarana_jml_sd"				=> (string)$result->sarana_jml_sd,
								"sarana_jml_smp"			=> (string)$result->sarana_jml_smp,
								"sarana_jml_sma"			=> (string)$result->sarana_jml_sma,
								"sarana_jml_pt"				=> (string)$result->sarana_jml_pt,
								"sarana_jml_masjid"			=> (string)$result->sarana_jml_masjid,
								"sarana_jml_mushola"		=> (string)$result->sarana_jml_mushola,
								"sarana_jml_gereja"			=> (string)$result->sarana_jml_gereja,
								"sarana_jml_pura"			=> (string)$result->sarana_jml_pura,
								"sarana_jml_vihara"			=> (string)$result->sarana_jml_vihara,
								"sarana_jml_klenteng"		=> (string)$result->sarana_jml_klenteng,
								"sarana_jml_olahraga"		=> (string)$result->sarana_jml_olahraga,
								"sarana_jml_kesenian"		=> (string)$result->sarana_jml_kesenian,
								"sarana_jml_balai_pertemuan"=> (string)$result->sarana_jml_balai_pertemuan,
								"sarana_jml_lainnya"		=> (string)$result->sarana_jml_lainnya	
							);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	
	//insert ke tabel dbo.mon_personil
	public function personilInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"nama_lurah"	           	=> $dataMasukan['nama_lurah'],
								"nip_lurah"	            	=> $dataMasukan['nip_lurah'],
								"gol_lurah"	            	=> $dataMasukan['gol_lurah'],
								"id_pendidikan_lurah"	    => $dataMasukan['id_pendidikan_lurah'],
								"tmt_jabatan_lurah"	        => $dataMasukan['tmt_jabatan_lurah'],
								"riwayat_jabatan1"         	=> $dataMasukan['riwayat_jabatan1'],
								"id_jenkel_lurah"	        => $dataMasukan['id_jenkel_lurah'],
								
								"nama_seklur"	           	=> $dataMasukan['nama_seklur'],
								"nip_seklur"	            => $dataMasukan['nip_seklur'],
								"gol_seklur"	            => $dataMasukan['gol_seklur'],
								"id_pendidikan_seklur"	    => $dataMasukan['id_pendidikan_seklur'],
								"tmt_jabatan_seklur"	    => $dataMasukan['tmt_jabatan_seklur'],
								"riwayat_jabatan1_seklur"   => $dataMasukan['riwayat_jabatan1_seklur'],
								"id_jenkel_seklur"	        => $dataMasukan['id_jenkel_seklur'],
								
								"jumlah_aparat_gol1"	    => $dataMasukan['jumlah_aparat_gol1'],
								"jumlah_aparat_gol2"	    => $dataMasukan['jumlah_aparat_gol2'],
								"jumlah_aparat_gol3"	    => $dataMasukan['jumlah_aparat_gol3'],
								"jumlah_aparat_gol4"	    => $dataMasukan['jumlah_aparat_gol4']
									
								
								);
			
			$db->insert('SIMKEL.dbo.mon_personil',$paramInput);
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
	
	//update tabel dbo.mon_personil
	public function personilUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"nama_lurah"	           	=> $dataMasukan['nama_lurah'],
								"nip_lurah"	            	=> $dataMasukan['nip_lurah'],
								"gol_lurah"	            	=> $dataMasukan['gol_lurah'],
								"id_pendidikan_lurah"	    => $dataMasukan['id_pendidikan_lurah'],
								"tmt_jabatan_lurah"	        => $dataMasukan['tmt_jabatan_lurah'],
								"riwayat_jabatan1"         	=> $dataMasukan['riwayat_jabatan1'],
								"id_jenkel_lurah"	            	=> $dataMasukan['id_jenkel_lurah'],
								
								"nama_seklur"	           	=> $dataMasukan['nama_seklur'],
								"nip_seklur"	            => $dataMasukan['nip_seklur'],
								"gol_seklur"	            => $dataMasukan['gol_seklur'],
								"id_pendidikan_seklur"	    => $dataMasukan['id_pendidikan_seklur'],
								"tmt_jabatan_seklur"	    => $dataMasukan['tmt_jabatan_seklur'],
								"riwayat_jabatan1_seklur"   => $dataMasukan['riwayat_jabatan1_seklur'],
								"id_jenkel_seklur"	        => $dataMasukan['id_jenkel_seklur'],
								
								"jumlah_aparat_gol1"	    => $dataMasukan['jumlah_aparat_gol1'],
								"jumlah_aparat_gol2"	    => $dataMasukan['jumlah_aparat_gol2'],
								"jumlah_aparat_gol3"	    => $dataMasukan['jumlah_aparat_gol3'],
								"jumlah_aparat_gol4"	    => $dataMasukan['jumlah_aparat_gol4']
									
								
								);
			//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_personil',$paramInput, $where);
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
	
	//hapus personil
	public function personilHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_personil', $where);
			
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
	
	//UPDATE DAN DETAIL personil
	public function detailPersonil($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_personil ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"nama_lurah"	           	=> (string)$result->nama_lurah,
								"nip_lurah"	            	=> (string)$result->nip_lurah,
								"gol_lurah"	            	=> (string)$result->gol_lurah,
								"id_pendidikan_lurah"	    => (string)$result->id_pendidikan_lurah,
								"tmt_jabatan_lurah"	        => $result->tmt_jabatan_lurah,
								"riwayat_jabatan1"         	=> (string)$result->riwayat_jabatan1,
								"id_jenkel_lurah"	            	=> (string)$result->id_jenkel_lurah,
								
								"nama_seklur"	           	=> (string)$result->nama_seklur,
								"nip_seklur"	            => (string)$result->nip_seklur,
								"gol_seklur"	            => (string)$result->gol_seklur,
								"id_pendidikan_seklur"	    => (string)$result->id_pendidikan_seklur,
								"tmt_jabatan_seklur"	    => $result->tmt_jabatan_seklur,
								"riwayat_jabatan1_seklur"   => (string)$result->riwayat_jabatan1_seklur,
								"id_jenkel_seklur"	        => (string)$result->id_jenkel_seklur,
								
								"jumlah_aparat_gol1"	    => (string)$result->jumlah_aparat_gol1,
								"jumlah_aparat_gol2"	    => (string)$result->jumlah_aparat_gol2,
								"jumlah_aparat_gol3"	    => (string)$result->jumlah_aparat_gol3,
								"jumlah_aparat_gol4"	    => (string)$result->jumlah_aparat_gol4	
							);
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	////////////////////////////////////////////////////////////////////////////////
	//insert ke tabel dbo.mon_kewenangan
	public function kewenanganInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"jml_urusan_kota"	        => $dataMasukan['jml_urusan_kota'],
								"jml_limpah_urusan_kota"	=> $dataMasukan['jml_limpah_urusan_kota'],
								"jml_urusan_wajib"	        => $dataMasukan['jml_urusan_wajib'],
								"jml_urusan_pilihan"	    => $dataMasukan['jml_urusan_pilihan'],
								"bidang_urusan_wajib"	    => $dataMasukan['bidang_urusan_wajib'],
								"bidang_urusan_pilihan"	    => $dataMasukan['bidang_urusan_pilihan'],
								"urusan_wajib"         		=> $dataMasukan['urusan_wajib'],
								"urusan_pilihan"	        => $dataMasukan['urusan_pilihan'],
								"jml_program_kelurahan"	    => $dataMasukan['jml_program_kelurahan']
								
								);
				
			// var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_kewenangan',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			// var_dump($errmsgArr);
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
	
	//update tabel dbo.mon_kewenangan
	public function kewenanganUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("jml_urusan_kota"	        => $dataMasukan['jml_urusan_kota'],
								"jml_limpah_urusan_kota"	=> $dataMasukan['jml_limpah_urusan_kota'],
								"jml_urusan_wajib"	        => $dataMasukan['jml_urusan_wajib'],
								"jml_urusan_pilihan"	    => $dataMasukan['jml_urusan_pilihan'],
								"bidang_urusan_wajib"	    => $dataMasukan['bidang_urusan_wajib'],
								"bidang_urusan_pilihan"	    => $dataMasukan['bidang_urusan_pilihan'],
								"urusan_wajib"         		=> $dataMasukan['urusan_wajib'],
								"urusan_pilihan"	        => $dataMasukan['urusan_pilihan'],
								"jml_program_kelurahan"	    => $dataMasukan['jml_program_kelurahan']
								
								);
		//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_kewenangan',$paramInput, $where);
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
	
	//hapus kewenangan
	public function kewenanganHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_kewenangan', $where);
			
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
	
	//UPDATE DAN DETAIL kewenangan
	public function detailKewenangan($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_kewenangan ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			
			$hasilAkhir = array(
								"jml_urusan_kota"	        => (string)$result->jml_urusan_kota,
								"jml_limpah_urusan_kota"	=> (string)$result->jml_limpah_urusan_kota,
								"jml_urusan_wajib"	        => (string)$result->jml_urusan_wajib,
								"jml_urusan_pilihan"	    => (string)$result->jml_urusan_pilihan,
								"bidang_urusan_wajib"	    => (string)$result->bidang_urusan_wajib,
								"bidang_urusan_pilihan"	    => (string)$result->bidang_urusan_pilihan,
								"urusan_wajib"         		=> (string)$result->urusan_wajib,
								"urusan_pilihan"	        => (string)$result->urusan_pilihan,
								"jml_program_kelurahan"	    => (string)$result->jml_program_kelurahan
			
							);
							
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	///////////////// program kelurahan
	//insert ke tabel program kelurahan 
	public function programkelurahanInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 				=> $dataMasukan['tahun'],
								"bulan"					=> $dataMasukan['bulan'],
								"kd_kel"	           	=> $dataMasukan['kd_kel'],
								"kode"	        		=> $dataMasukan['kode'],
								"nama_program"			=> $dataMasukan['nama_program'],
								"anggaran"	     		=> $dataMasukan['anggaran']
								
								);
				
			// var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_program_kelurahan',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			// var_dump($errmsgArr);
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
	
	//update tabel program
	public function programkelurahanUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"nama_program"	    => $dataMasukan['nama_program'],
								"anggaran"	      	=> $dataMasukan['anggaran'],
								"kode"	   			=> $dataMasukan['kode']
								);
		//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('[SIMKEL].[dbo].[mon_program_kelurahan]',$paramInput, $where);
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
	
	
	
	public function detailProgram($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
				$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchAll("SELECT  * from [SIMKEL].[dbo].[mon_program_kelurahan] where kd_kel = '$kd_kel' AND bulan='$bulan' AND tahun='$tahun' ");
				return $result;
				} catch (Exception $e) {
				echo $e->getMessage().'<br>';
				return 'Data tidak ada <br>';
			}
	}
	
	//hapus kewenangan
	public function programHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('[SIMKEL].[dbo].[mon_program_kelurahan]', $where);
			
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
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////
	//insert ke tabel dbo.mon_keuangan
	public function keuanganInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 			=> $dataMasukan['tahun'],
								"bulan"				=> $dataMasukan['bulan'],
								"kd_kel"	        => $dataMasukan['kd_kel'],
								"anggaran_apbd"	    => $dataMasukan['anggaran_apbd'],
								"is_skpd"	        => $dataMasukan['is_skpd'],
								"bantuan_pusat"	    => $dataMasukan['bantuan_pusat'],
								"bantuan_prov"	    => $dataMasukan['bantuan_prov'],
								"bantuan_kota"	    => $dataMasukan['bantuan_kota'],
								"hibah"         	=> $dataMasukan['hibah'],
								"sumbangan"	        => $dataMasukan['sumbangan'],
								"swadaya"	    	=> $dataMasukan['swadaya']
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_keuangan',$paramInput);
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
	
	//update tabel dbo.mon_keuangan
	public function keuanganUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"anggaran_apbd"	    => $dataMasukan['anggaran_apbd'],
								"is_skpd"	        => $dataMasukan['is_skpd'],
								"bantuan_pusat"	    => $dataMasukan['bantuan_pusat'],
								"bantuan_prov"	    => $dataMasukan['bantuan_prov'],
								"bantuan_kota"	    => $dataMasukan['bantuan_kota'],
								"hibah"         	=> $dataMasukan['hibah'],
								"sumbangan"	        => $dataMasukan['sumbangan'],
								"swadaya"	    	=> $dataMasukan['swadaya']
								
								);
			//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_keuangan',$paramInput, $where);
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
	
	//hapus keuangan
	public function keuanganHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_keuangan', $where);
			
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
	
	//UPDATE DAN DETAIL keuangan
	public function detailKeuangan($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_keuangan ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"anggaran_apbd"	    => (string)$result->anggaran_apbd,
								"is_skpd"	        => (string)$result->is_skpd,
								"bantuan_pusat"	    => (string)$result->bantuan_pusat,
								"bantuan_prov"	    => (string)$result->bantuan_prov,
								"bantuan_kota"	    => (string)$result->bantuan_kota,
								"hibah"         	=> (string)$result->hibah,
								"sumbangan"	        => (string)$result->sumbangan,
								"swadaya"	    	=> (string)$result->swadaya
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	////////////////////////////////////////////////////////////////////////////////
	//insert ke tabel dbo.mon_kelembagaaan
	public function kelembagaanInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"lpm_jml_pengurus"	        => $dataMasukan['lpm_jml_pengurus'],
								"lpm_jml_anggota"	        => $dataMasukan['lpm_jml_anggota'],
								"lpm_jml_keg_perbulan"	    => $dataMasukan['lpm_jml_keg_perbulan'],
								"lpm_jml_dana"	    		=> $dataMasukan['lpm_jml_dana'],
								"lpm_jml_buku_administrasi"	=> $dataMasukan['lpm_jml_buku_administrasi'],
								"pkk_jml_pengurus"	   		=> $dataMasukan['pkk_jml_pengurus'],
								"pkk_jml_anggota"         	=> $dataMasukan['pkk_jml_anggota'],
								"pkk_jml_keg_perbulan"	    => $dataMasukan['pkk_jml_keg_perbulan'],
								"pkk_jml_buku_administrasi"	=> $dataMasukan['pkk_jml_buku_administrasi'],
								"pkk_jml_dana"				=> $dataMasukan['pkk_jml_dana'],
								"taruna_jml"	    		=> $dataMasukan['taruna_jml'],
								"taruna_jenis"	    		=> $dataMasukan['taruna_jenis'],
								"taruna_jml_pengurus"	    => $dataMasukan['taruna_jml_pengurus'],
								"jml_rw"	    			=> $dataMasukan['jml_rw'],
								"jml_rt"	    			=> $dataMasukan['jml_rt'],
								"rata_penghasilan_rw"	    => $dataMasukan['rata_penghasilan_rw'],
								"rata_penghasilan_rt"	    => $dataMasukan['rata_penghasilan_rt'],
								"jml_lembaga_lain"	    	=> $dataMasukan['jml_lembaga_lain']
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_kelembagaan',$paramInput);
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
	
	//update tabel dbo.mon_kelembagaaan
	public function kelembagaanUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"lpm_jml_pengurus"	        => $dataMasukan['lpm_jml_pengurus'],
								"lpm_jml_anggota"	        => $dataMasukan['lpm_jml_anggota'],
								"lpm_jml_keg_perbulan"	    => $dataMasukan['lpm_jml_keg_perbulan'],
								"lpm_jml_dana"	    		=> $dataMasukan['lpm_jml_dana'],
								"lpm_jml_buku_administrasi"	=> $dataMasukan['lpm_jml_buku_administrasi'],
								"pkk_jml_pengurus"	   		=> $dataMasukan['pkk_jml_pengurus'],
								"pkk_jml_anggota"         	=> $dataMasukan['pkk_jml_anggota'],
								"pkk_jml_keg_perbulan"	    => $dataMasukan['pkk_jml_keg_perbulan'],
								"pkk_jml_buku_administrasi"	=> $dataMasukan['pkk_jml_buku_administrasi'],
								"pkk_jml_dana"				=> $dataMasukan['pkk_jml_dana'],
								"taruna_jml"	    		=> $dataMasukan['taruna_jml'],
								"taruna_jenis"	    		=> $dataMasukan['taruna_jenis'],
								"taruna_jml_pengurus"	    => $dataMasukan['taruna_jml_pengurus'],
								"jml_rw"	    			=> $dataMasukan['jml_rw'],
								"jml_rt"	    			=> $dataMasukan['jml_rt'],
								"rata_penghasilan_rw"	    => $dataMasukan['rata_penghasilan_rw'],
								"rata_penghasilan_rt"	    => $dataMasukan['rata_penghasilan_rt'],
								"jml_lembaga_lain"	    	=> $dataMasukan['jml_lembaga_lain']
								
								);
				//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_kelembagaan',$paramInput, $where);
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
	
	//hapus kelembagaan
	public function kelembagaanHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_kelembagaan', $where);
			
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
	
	//UPDATE DAN DETAIL kelembagaan
	public function detailKelembagaan($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_kelembagaan ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"lpm_jml_pengurus"	        => (string)$result->lpm_jml_pengurus,
								"lpm_jml_anggota"	        => (string)$result->lpm_jml_anggota,
								"lpm_jml_keg_perbulan"	    => (string)$result->lpm_jml_keg_perbulan,
								"lpm_jml_dana"	    		=> (string)$result->lpm_jml_dana,
								"lpm_jml_buku_administrasi"	=> (string)$result->lpm_jml_buku_administrasi,
								"pkk_jml_pengurus"	   		=> (string)$result->pkk_jml_pengurus,
								"pkk_jml_anggota"         	=> (string)$result->pkk_jml_anggota,
								"pkk_jml_keg_perbulan"	    => (string)$result->pkk_jml_keg_perbulan,
								"pkk_jml_buku_administrasi"	=> (string)$result->pkk_jml_buku_administrasi,
								"pkk_jml_dana"				=> (string)$result->pkk_jml_dana,
								"taruna_jml"	    		=> (string)$result->taruna_jml,
								"taruna_jenis"	    		=> (string)$result->taruna_jenis,
								"taruna_jml_pengurus"	    => (string)$result->taruna_jml_pengurus,
								"jml_rw"	    			=> (string)$result->jml_rw,
								"jml_rt"	    			=> (string)$result->jml_rt,
								"rata_penghasilan_rw"	    => (string)$result->rata_penghasilan_rw,
								"rata_penghasilan_rt"	    => (string)$result->rata_penghasilan_rt,
								"jml_lembaga_lain"	    	=> (string)$result->jml_lembaga_lain
								
								);
			
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	////////////////////////////////////////////////////////////////////////////////
	//insert ke tabel dbo.mon_trantib
	public function trantibInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"jml_anggota_linmas"	    => $dataMasukan['jml_anggota_linmas'],
								"jml_pos_kamling"	        => $dataMasukan['jml_pos_kamling'],
								"jml_ops_penertiban"	    => $dataMasukan['jml_ops_penertiban'],
								"jml_pencurian"	    		=> $dataMasukan['jml_pencurian'],
								"jml_perkosaan"	   			=> $dataMasukan['jml_perkosaan'],
								"jml_kenakalan_remaja"      => $dataMasukan['jml_kenakalan_remaja'],
								"jml_pembunuhan"	    	=> $dataMasukan['jml_pembunuhan'],
								"jml_perampokan"			=> $dataMasukan['jml_perampokan'],
								"jml_penipuan"	    		=> $dataMasukan['jml_penipuan'],
								"jml_bencana"	    		=> $dataMasukan['jml_bencana'],
								"jml_pos_bencana"	    	=> $dataMasukan['jml_pos_bencana'],
								"jml_pembalakan_liar"	    => $dataMasukan['jml_pembalakan_liar'],
								"jml_pos_hutan_lindung"	    => $dataMasukan['jml_pos_hutan_lindung']
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_trantib',$paramInput);
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
	
	
	//update tabel dbo.mon_trantib
	public function trantibUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"jml_anggota_linmas"	    => $dataMasukan['jml_anggota_linmas'],
								"jml_pos_kamling"	        => $dataMasukan['jml_pos_kamling'],
								"jml_ops_penertiban"	    => $dataMasukan['jml_ops_penertiban'],
								"jml_pencurian"	    		=> $dataMasukan['jml_pencurian'],
								"jml_perkosaan"	   			=> $dataMasukan['jml_perkosaan'],
								"jml_kenakalan_remaja"      => $dataMasukan['jml_kenakalan_remaja'],
								"jml_pembunuhan"	    	=> $dataMasukan['jml_pembunuhan'],
								"jml_perampokan"			=> $dataMasukan['jml_perampokan'],
								"jml_penipuan"	    		=> $dataMasukan['jml_penipuan'],
								"jml_bencana"	    		=> $dataMasukan['jml_bencana'],
								"jml_pos_bencana"	    	=> $dataMasukan['jml_pos_bencana'],
								"jml_pembalakan_liar"	    => $dataMasukan['jml_pembalakan_liar'],
								"jml_pos_hutan_lindung"	    => $dataMasukan['jml_pos_hutan_lindung']
								
								);
				//var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->update('SIMKEL.dbo.mon_trantib',$paramInput, $where);
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
	
	//hapus trantib
	public function trantibHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_kel" =>'kd_kel');	
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND bulan =  = '".$dataMasukan['bulan']."' AND tahun =  = '".$dataMasukan['tahun']."' ";
			$db->delete('SIMKEL.dbo.mon_trantib', $where);
			
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
	
	//UPDATE DAN DETAIL trantib
	public function detailTrantib($kd_kel,$bulan,$tahun) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where kd_kel = '$kd_kel' AND bulan = '$bulan' AND tahun = '$tahun' ";
			$sqlProses = "SELECT * FROM SIMKEL.dbo.mon_trantib ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"jml_anggota_linmas"	    => (string)$result->jml_anggota_linmas,
								"jml_pos_kamling"	        => (string)$result->jml_pos_kamling,
								"jml_ops_penertiban"	    => (string)$result->jml_ops_penertiban,
								"jml_pencurian"	    		=> (string)$result->jml_pencurian,
								"jml_perkosaan"	   			=> (string)$result->jml_perkosaan,
								"jml_kenakalan_remaja"      => (string)$result->jml_kenakalan_remaja,
								"jml_pembunuhan"	    	=> (string)$result->jml_pembunuhan,
								"jml_perampokan"			=> (string)$result->jml_perampokan,
								"jml_penipuan"	    		=> (string)$result->jml_penipuan,
								"jml_bencana"	    		=> (string)$result->jml_bencana,
								"jml_pos_bencana"	    	=> (string)$result->jml_pos_bencana,
								"jml_pembalakan_liar"	    => (string)$result->jml_pembalakan_liar,
								"jml_pos_hutan_lindung"	    => (string)$result->jml_pos_hutan_lindung
								
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	////////////////////////////////////////////////////////////////////////////////

	
	

	

	public function rekampasienHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_status" =>'D');	
			$where[] = " c_id = '".$dataMasukan['id']."'";
			$db->delete('t_fisik', $where);
			
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
