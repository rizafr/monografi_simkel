<?php
require_once 'share/globalReferensi.php';

class Aplikasi_Referensi_Service {
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
	// List Pejabat
	//======================================================================
	public function getNamaDanJabatanSetkabList() {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select i_nik,
								nama,
								kd_jabatan,
								kd_struktur_org
							from simpeg_internal.v_jab_peg";	
							
			
			$sqlData = $sqlProses; //.$where;
			$result = $db->fetchAll($sqlData);				
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$nmJabatan = $globalRef->getNamaJabatan($result[$j]->kd_jabatan);
				$nm_struktur_org = $globalRef->getNamaOrganisasi($result[$j]->kd_struktur_org);
				
				$nm_jabatan_lengkap = $nmJabatan." ".$nm_struktur_org;
				$hasilAkhir[$j] = array("i_nik"  				=>(string)$result[$j]->i_nik,
										"nama"				=> (string)$result[$j]->nama,
										"kd_jabatan"  		=>(string)$result[$j]->kd_jabatan,
									    "kd_struktur_org"  	=>(string)$result[$j]->kd_struktur_org,
										"nm_struktur_org" 	=> $nm_struktur_org,
									    "nmJabatan"			=> $nmJabatan,
									    "nm_jabatan_lengkap"=> $nm_jabatan_lengkap 
										); 
				
				
			}	
			//var_dump($hasilAkhir);	
			return $hasilAkhir;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	//List Instansi
	//===================
	public function getInstansiList() {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select a.nm_instansi
							from simpeg_internal.tbl_instansi a";	
							
			
			$sqlData = $sqlProses; //.$where;
			$result = $db->fetchAll($sqlData);				
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("nm_instansi"  		=>(string)$result[$j]->nm_instansi
										); 
				//var_dump($hasilAkhir);	
				
			}	
			return $hasilAkhir;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function isNamaPegawai($nPeg) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select count(*) jml
							from tm_pegawai a
							where trim(a.nama) = trim('$nPeg')";	
							
			
			$sqlData = $sqlProses; 
			$jumlah = $db->fetchOne($sqlData);				
			
			return $jumlah;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getnamaPegawai($i_nik) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select a.nama
							from tm_pegawai a
							where i_nik = '$i_nik'";	
							
			
			$sqlData = $sqlProses; 
			$nama = $db->fetchOne($sqlData);				
			
			return $nama;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getjabatanPegawai($i_nik) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select concat(nm_jabatan ,' ',level ,' ',nm_level) as jabatan
							from simpeg_internal.v_jab_peg a
							where i_nik = '$i_nik' and DATE_FORMAT(tgl_pemberhentian, '%Y-%m-%d') = '0000-00-00'";	
							
			
			$sqlData = $sqlProses; 
			$nama = $db->fetchOne($sqlData);				
			
			return $nama;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getSatuanLampiranList() {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select id, nm_satuanlampiran
							from tr_satuanlampiran";	
							
			
			$sqlData = $sqlProses; //.$where;
			$result = $db->fetchAll($sqlData);				
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id_satuanlampiran"  		=>(string)$result[$j]->id,
										"nm_satuanlampiran"  		=>(string)$result[$j]->nm_satuanlampiran); 	
			}	
			return $hasilAkhir;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getAtasan($kdOrg, $kd_jabatan) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select a.kd_struktur_org_induk, 
								(select b.kd_jabatan from simpeg_internal.v_jab_peg b
								where b.kd_struktur_org = a.kd_struktur_org_induk) as kd_jabatan_induk
						  from simpeg_internal.v_jab_peg a
						  where a.kd_struktur_org = '$kdOrg' and a.kd_jabatan = '$kd_jabatan'";	
							
			
			$sqlData = $sqlProses; //.$where;
			$result = $db->fetchRow($sqlData);				
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			$hasilAkhir = array("kd_struktur_org_induk"  		=>(string)$result->kd_struktur_org_induk,
									"kd_jabatan_induk"  		=>(string)$result->kd_jabatan_induk); 	
			return $hasilAkhir;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getNipList() {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = "select a.i_nik
							from tm_pegawai a";	
							
			
			$sqlData = $sqlProses; //.$where;
			$result = $db->fetchAll($sqlData);				
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_nik"  		=>(string)$result[$j]->i_nik
										); 
				//var_dump($hasilAkhir);	
				
			}	
			return $hasilAkhir;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}


	public function getnamaKabupaten($c_propinsi) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$globalRef = new globalReferensi;
			
			$sqlProses = " SELECT n_kab FROM tr_kabupaten where id_prop = '$c_propinsi'";	
							
			$sqlData = $sqlProses; 
			$nama = $db->fetchOne($sqlData);				
			
			return $nama;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
}
?>