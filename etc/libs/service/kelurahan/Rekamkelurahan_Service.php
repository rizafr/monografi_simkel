<?php
class RekamKelurahan_Service {
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
	// List Rekampasien
	//======================================================================

	public function getRekampasienList() {
	
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

	public function cariRekampasienList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= strToLower($dataMasukan['katakunciCari']);
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$kode_pasien		= $dataMasukan['kode_pasien'];

		if($kategoriCari == "") { $kategoriCari ="n_nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where c_noreg ='$kode_pasien' ";
			
			if($katakunciCari){
			$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";
			}
					
			$where = $whereOptCar.$whereBase;
			//$order = " order by id_medrec ";

			$sqlProses = "SELECT * FROM t_fisik ";	
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
				$kode_pasien = (string)$result[$j]->kode_pasien;
				$nama = $db->fetchOne("Select n_nama from t_pasien where kode_pasien ='$kode_pasien' and c_status <> '' and c_status <> 'd' ");
				$c_klasifikasi = (string)$result[$j]->c_klasifikasi;
				$n_klasifikasi = $db->fetchOne("Select n_klasifikasi from tr_klasifikasi_med where id_klasifikasi ='$c_klasifikasi'");
				$c_tindakan = (string)$result[$j]->c_tindakan;
				$n_tindakan = $db->fetchOne("Select n_tindakan from tr_tindakan where id_tindakan ='$c_tindakan' ");
				$hasilAkhir[$j] = array("c_id"					=> (string)$result[$j]->c_id,
										"c_noreg"			=> (string)$result[$j]->c_noreg,
										"t_waktu"				=> (string)$result[$j]->t_waktu,
										"t_berat"				=> (string)$result[$j]->t_berat,
										"t_tinggi"				=> (string)$result[$j]->t_tinggi,
										"t_tekanan"				=> (string)$result[$j]->t_tekanan,
										"t_denyut"				=> (string)$result[$j]->t_denyut,
										"t_frequensi"				=> (string)$result[$j]->t_frequensi,
										"t_suhu"				=> (string)$result[$j]->t_suhu,
										
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	///////////////////////////////////////////////////////////////////////
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
								"jml_lulusan_miskin"		=> $dataMasukan['jml_lulusan_miskin'],
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
			$db->insert('SIMKEL.dbon.mon_umum',$paramInput);
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
								"jml_lulusan_miskin"		=> $dataMasukan['jml_lulusan_miskin'],
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
								"jml_lulusan_miskin"		=> (string)$result->jml_lulusan_miskin,
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
								"id_jenkel"	            	=> $dataMasukan['id_jenkel'],
								
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
			$db->insert('SIMKEL.dbon.mon_personil',$paramInput);
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
								"id_jenkel"	            	=> $dataMasukan['id_jenkel'],
								
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
								"tmt_jabatan_lurah"	        => (string)$result->tmt_jabatan_lurah,
								"riwayat_jabatan1"         	=> (string)$result->riwayat_jabatan1,
								"id_jenkel"	            	=> (string)$result->id_jenkel,
								
								"nama_seklur"	           	=> (string)$result->nama_seklur,
								"nip_seklur"	            => (string)$result->nip_seklur,
								"gol_seklur"	            => (string)$result->gol_seklur,
								"id_pendidikan_seklur"	    => (string)$result->id_pendidikan_seklur,
								"tmt_jabatan_seklur"	    => (string)$result->tmt_jabatan_seklur,
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
								"jml_urusan"	           	=> $dataMasukan['jml_urusan'],
								"jml_urusan_wajib"	        => $dataMasukan['jml_urusan_wajib'],
								"jml_urusan_pilihan"	    => $dataMasukan['jml_urusan_pilihan'],
								"bidang_urusan_wajib"	    => $dataMasukan['bidang_urusan_wajib'],
								"bidang_urusan_pilihan"	    => $dataMasukan['bidang_urusan_pilihan'],
								"urusan_wajib"         		=> $dataMasukan['urusan_wajib'],
								"urusan_pilihan"	        => $dataMasukan['urusan_pilihan'],
								"jml_program_kelurahan"	    => $dataMasukan['jml_program_kelurahan']
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbon.mon_kewenangan',$paramInput);
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
	
	//update tabel dbo.mon_kewenangan
	public function kewenanganUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array(
								"jml_urusan"	           	=> $dataMasukan['jml_urusan'],
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
								"jml_urusan"	           	=> (string)$result->jml_urusan,
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
	
	
	////////////////////////////////////////////////////////////////////////////////
	//insert ke tabel dbo.mon_keuangan
	public function keuanganInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("tahun" 					=> $dataMasukan['tahun'],
								"bulan"						=> $dataMasukan['bulan'],
								"kd_kel"	           	 	=> $dataMasukan['kd_kel'],
								"anggaran_apbd"	           	=> $dataMasukan['anggaran_apbd'],
								"is_skpd"	        => $dataMasukan['is_skpd'],
								"bantuan_pusat"	    => $dataMasukan['bantuan_pusat'],
								"bantuan_prov"	    => $dataMasukan['bantuan_prov'],
								"bantuan_kota"	    => $dataMasukan['bantuan_kota'],
								"hibah"         		=> $dataMasukan['hibah'],
								"sumbangan"	        => $dataMasukan['sumbangan'],
								"swadaya"	    => $dataMasukan['swadaya']
								
								);
			//var_dump($paramInput);
			$db->insert('SIMKEL.dbon.mon_keuangan',$paramInput);
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
								"pkk_jml_pengurus"	   		=> $dataMasukan['pkk_jml_pengurus'],
								"pkk_jml_anggota"         	=> $dataMasukan['pkk_jml_anggota'],
								"pkk_jml_keg_perbulan"	    => $dataMasukan['pkk_jml_keg_perbulan'],
								"pkk_jml_buku_administrasi"	=> $dataMasukan['pkk_jml_buku_administrasi'],
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
			$db->insert('SIMKEL.dbon.mon_kelembagaan',$paramInput);
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
								"pkk_jml_pengurus"	   		=> $dataMasukan['pkk_jml_pengurus'],
								"pkk_jml_anggota"         	=> $dataMasukan['pkk_jml_anggota'],
								"pkk_jml_keg_perbulan"	    => $dataMasukan['pkk_jml_keg_perbulan'],
								"pkk_jml_buku_administrasi"	=> $dataMasukan['pkk_jml_buku_administrasi'],
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
								"pkk_jml_pengurus"	   		=> (string)$result->pkk_jml_pengurus,
								"pkk_jml_anggota"         	=> (string)$result->pkk_jml_anggota,
								"pkk_jml_keg_perbulan"	    => (string)$result->pkk_jml_keg_perbulan,
								"pkk_jml_buku_administrasi"	=> (string)$result->pkk_jml_buku_administrasi,
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
			$db->insert('SIMKEL.dbon.mon_trantib',$paramInput);
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

	public function detailRekampasienById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where c_id = '$id' ";
			$sqlProses = "SELECT * FROM t_fisik ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array(
								"c_id"			=> (string)$result->c_id,
								"c_noreg"			=> (string)$result->c_noreg,
										"t_waktu"				=> (string)$result->t_waktu,
										"t_berat"				=> (string)$result->t_berat,
										"t_tinggi"				=> (string)$result->t_tinggi,
										"t_tekanan"				=> (string)$result->t_tekanan,
										"t_denyut"				=> (string)$result->t_denyut,
										"t_frequensi"				=> (string)$result->t_frequensi,
										"t_suhu"				=> (string)$result->t_suhu,
										"n_mata"				=> (string)$result->n_mata,
										"n_tht"				=> (string)$result->n_tht,
										"n_gigi"				=> (string)$result->n_gigi,
										"n_leher"				=> (string)$result->n_leher,
										"n_jantung"				=> (string)$result->n_jantung,
										"n_paru"				=> (string)$result->n_paru,
										"n_perut"				=> (string)$result->n_perut,
										"n_gerak"				=> (string)$result->n_gerak,
										"n_gizi"				=> (string)$result->n_gizi,
										"n_potensi"				=> (string)$result->n_potensi,
										"n_mental"				=> (string)$result->n_mental,
										"n_reproduksi"				=> (string)$result->n_reproduksi,
										"n_kematangan"				=> (string)$result->n_kematangan,
										"n_hb"				=> (string)$result->n_hb,
										"n_feses"				=> (string)$result->n_feses,
										"n_jasmani"				=> (string)$result->n_jasmani,
										
										"c_golongan"				=> (string)$result->c_golongan,
										"c_jantung"				=> (string)$result->c_jantung,
										"c_lain"				=> (string)$result->c_lain,
										"c_asma"				=> (string)$result->c_asma,
										"c_thalassimia"				=> (string)$result->c_thalassimia,
										"c_jiwa"				=> (string)$result->c_jiwa,
										"c_darting"				=> (string)$result->c_darting,
										"c_manis"				=> (string)$result->c_manis,
										"c_kanker"				=> (string)$result->c_kanker,
										"c_golongani"				=> (string)$result->c_golongani,
										"c_jantungi"				=> (string)$result->c_jantungi,
										"n_laini"				=> (string)$result->n_laini,
										"c_haid"				=> (string)$result->n_haid,
										"c_teratur"				=> (string)$result->c_teratur,
										"n_mimpi"				=> (string)$result->n_mimpi,
										"c_dasar"				=> (string)$result->c_dasar,
										"c_lengkap"				=> (string)$result->c_lengkap,
										"c_thalassimia"				=> (string)$result->c_thalassimia,
										"t_berat2"				=> (string)$result->t_berat2,
										"t_tinggi2"				=> (string)$result->t_tinggi2,
										"t_tekanan2"				=> (string)$result->t_tekanan2,
										"t_denyut2"				=> (string)$result->t_denyut2,
										"t_frequensi2"				=> (string)$result->t_frequensi2,
										"t_suhu2"				=> (string)$result->t_suhu2,
										"n_mata2"				=> (string)$result->n_mata2,
										"n_tht2"				=> (string)$result->n_tht2,
										"n_gigi2"				=> (string)$result->n_gigi2,
										"n_jantung2"				=> (string)$result->n_jantung2,
										"n_paru2"				=> (string)$result->n_paru2,
										"n_perut2"				=> (string)$result->n_perut2,
										"n_gerak2"				=> (string)$result->n_gerak2,
										"n_gizi2"				=> (string)$result->n_gizi2,
										"n_darah"				=> (string)$result->n_darah,
										"n_hemoglobin"				=> (string)$result->n_hemoglobin,
										"n_urin"				=> (string)$result->n_urin,
										"n_faeces"				=> (string)$result->n_faeces,
										"n_thalassimia"				=> (string)$result->n_thalassimia,
										"n_diagnosa"				=> (string)$result->n_diagnosa,
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function detailPasienByKode($kode) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where c_id = '$id' ";
			$sqlProses = "SELECT * FROM t_fisik ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array("c_id"				=> (string)$result->c_id,
								"c_noreg"           => (string)$result->c_noreg,
								
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function rekampasienUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array(
											"c_noreg"           => $dataMasukan['c_noreg'],
											"t_waktu"				=> $dataMasukan['t_waktu'],
											"t_berat"	            => $dataMasukan['t_berat'],
											"t_tinggi"	            => $dataMasukan['t_tinggi'],
											"t_tekanan"	            => $dataMasukan['t_tekanan'],
											"t_denyut"	            => $dataMasukan['t_denyut'],
											"t_frequensi"	        => $dataMasukan['t_frequensi'],
											"t_suhu"	        => $dataMasukan['t_suhu'],
											"n_mata"           => $dataMasukan['n_mata'],
											"n_tht"	            => $dataMasukan['n_tht'],
											"n_gigi"          => $dataMasukan['n_gigi'],
											"n_leher"			=> $dataMasukan['n_leher'],
											"n_jantung"				=> $dataMasukan['n_jantung'],
											"n_paru"         => $dataMasukan['n_paru'],
											"n_perut"         => $dataMasukan['n_perut'],
											"n_gerak"				=> $dataMasukan['n_gerak'],
											"n_gizi"				=> $dataMasukan['n_gizi'],
											"n_potensi"				=> $dataMasukan['n_potensi'],
											"n_mental"				=> $dataMasukan['n_mental'],
											"n_reproduksi"				=> $dataMasukan['n_reproduksi'],
											"n_kematangan"				=> $dataMasukan['n_kematangan'],
											"n_hb"				=> $dataMasukan['n_hb'],
											"n_feses"				=> $dataMasukan['n_feses'],
											"n_jasmani"				=> $dataMasukan['n_jasmani'],
											
											
											"c_golongan"				=> $dataMasukan['c_golongan'],
											"c_jantung"				=> $dataMasukan['c_jantung'],
											"c_lain"				=> $dataMasukan['c_lain'],
											"c_asma"				=> $dataMasukan['c_asma'],
											"c_thalassimia"				=> $dataMasukan['c_thalassimia'],
											"c_jiwa"				=> $dataMasukan['c_jiwa'],
											"c_darting"				=> $dataMasukan['c_darting'],
											"c_manis"				=> $dataMasukan['c_manis'],
											"c_kanker"				=> $dataMasukan['c_kanker'],
											"c_golongani"				=> $dataMasukan['c_golongani'],
											"c_jantungi"				=> $dataMasukan['c_jantungi'],
											"c_laini"				=> $dataMasukan['c_laini'],
											"c_asmai"				=> $dataMasukan['c_asmai'],
											"c_thalassimiai"				=> $dataMasukan['c_thalassimiai'],
											"c_jiwai"				=> $dataMasukan['c_jiwai'],
											"c_dartingi"				=> $dataMasukan['c_dartingi'],
											"c_manisi"				=> $dataMasukan['c_manisi'],
											"c_kankeri"				=> $dataMasukan['c_kankeri'],
											
											"c_golongan1"				=> $dataMasukan['c_golongan1'],
											"n_rhesus"				=> $dataMasukan['n_rhesus'],
											"c_jantung1"				=> $dataMasukan['c_jantung1'],
											"n_lain"				=> $dataMasukan['n_lain'],
											"asma"				=> $dataMasukan['asma'],
											"c_thalassimia1"		=> $dataMasukan['c_thalassimia1'],
											"c_jiwa1"				=> $dataMasukan['c_jiwa1'],
											"c_menular"				=> $dataMasukan['c_menular'],
											"n_sebut"				=> $dataMasukan['n_sebut'],
											"c_rokok"				=> $dataMasukan['c_rokok'],
											"c_minum"				=> $dataMasukan['c_minum'],
											"c_narkoba"				=> $dataMasukan['c_narkoba'],
											"n_lain1"				=> $dataMasukan['n_lain1'],
											"n_haid"				=> $dataMasukan['n_haid'],
											"c_teratur"				=> $dataMasukan['c_teratur'],
											"n_mimpi"				=> $dataMasukan['n_mimpi'],
											"c_dasar"				=> $dataMasukan['c_dasar'],
											"c_lengkap"				=> $dataMasukan['c_lengkap'],
											
											"t_berat2"	            => $dataMasukan['t_berat2'],
											"t_tinggi2"	            => $dataMasukan['t_tinggi2'],
											"t_tekanan2"	            => $dataMasukan['t_tekanan2'],
											"t_denyut2"	            => $dataMasukan['t_denyut2'],
											"t_frequensi2"	        => $dataMasukan['t_frequensi2'],
											"t_suhu2"	        => $dataMasukan['t_suhu2'],
											"n_mata2"           => $dataMasukan['n_mata2'],
											"n_tht2"	            => $dataMasukan['n_tht2'],
											"n_gigi2"          => $dataMasukan['n_gigi2'],
											"n_jantung2"				=> $dataMasukan['n_jantung2'],
											"n_paru2"         => $dataMasukan['n_paru2'],
											"n_perut2"         => $dataMasukan['n_perut2'],
											"n_gerak2"				=> $dataMasukan['n_gerak2'],
											"n_gizi2"         => $dataMasukan['n_gizi2'],
											
											"n_darah"         => $dataMasukan['n_darah'],
											"n_hemoglobin"         => $dataMasukan['n_hemoglobin'],
											"n_urin"         => $dataMasukan['n_urin'],
											"n_faeces"         => $dataMasukan['n_faeces'],
											"n_thalassimia"         => $dataMasukan['n_thalassimia'],
											"n_diagnosa"         => $dataMasukan['n_diagnosa'],
											
								);	
			//var_dump($paramInput);
			$where[] = " c_id = '".$dataMasukan['id']."'";
			$db->update('t_fisik',$paramInput, $where);
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

		public function rekampasienFotoUpdate($id,$n_file) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_foto"           => $n_file);	
			var_dump($id."----".$n_file);
			$where[] = " id_medrec = '$id'";
			$db->update('t_medrec',$paramInput, $where);
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
