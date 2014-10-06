<?php 
class globalReferensi {
	/*private static $instance;
   
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
	
	public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
		$this->view->baseData = $registry->get('baseData'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
		
		$this->agendamasuk_serv = Aplikasi_Suratmasukagenda_Service::getInstance();
		$this->srtjenis_serv = Aplikasi_Refsrtjenis_Service::getInstance();
		$this->srtsifat_serv = Aplikasi_Refsrtsifat_Service::getInstance();
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	*/

	public function getNamaOrganisasi($kd_org){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				
			$data = $db->fetchRow("SELECT level, nm_level
										FROM simpeg_internal.v_jab_peg
										WHERE kd_struktur_org = '$kd_org'");
			
			$level = $data->level;
			$nmlevel = $data->nm_level;
			
			$nmOrganisasiLengkap = $level." ".$nmlevel;	
			$kdOrgInduk = $kd_org;							
			while ($kdOrgInduk != '0') {
				$kdOrgInduk = $db->fetchOne("SELECT kd_struktur_org_induk
											FROM simpeg_internal.v_jab_peg
											WHERE kd_struktur_org = '$kdOrgInduk'");
										
				$level = $db->fetchOne("SELECT level
											FROM simpeg_internal.v_jab_peg
											WHERE kd_struktur_org = '$kdOrgInduk'");

				$nmLevel = $db->fetchOne("SELECT nm_level
											FROM simpeg_internal.v_jab_peg
											WHERE kd_struktur_org = '$kdOrgInduk'");			
	
				if(!$nmOrganisasiLengkap){
					$nmOrganisasiLengkap = $level." ".$nmLevel;
				} else {
					if($kdOrgInduk != 0) 
						$nmOrganisasiLengkap = $nmOrganisasiLengkap.", ".$level." ".$nmLevel;
					else
						$nmOrganisasiLengkap = $nmOrganisasiLengkap;
				}
							
			} 
					
			return $nmOrganisasiLengkap;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	/* public function getNamaOrganisasi($kd_org) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				
			$level = $db->fetchOne("SELECT level
										FROM simpeg_internal.tbl_struktur_organisasi
										WHERE kd_struktur_org = '$kd_org'");
										
			$nmLevel = $db->fetchOne("SELECT nm_level
										FROM simpeg_internal.tbl_struktur_organisasi
										WHERE kd_struktur_org = '$kd_org'");
			$nmOrganisasiLengkap = $level." ".$nmLevel;							
			$kdOrgInduk = $kd_org;							
			while ($kdOrgInduk != '0') {
				$kdOrgInduk = $db->fetchOne("SELECT kd_struktur_org_induk
											FROM simpeg_internal.tbl_struktur_organisasi
											WHERE kd_struktur_org = '$kdOrgInduk'");
											
				$level = $db->fetchOne("SELECT level
											FROM simpeg_internal.tbl_struktur_organisasi
											WHERE kd_struktur_org = '$kdOrgInduk'");

				$nmLevel = $db->fetchOne("SELECT nm_level
											FROM simpeg_internal.tbl_struktur_organisasi
											WHERE kd_struktur_org = '$kdOrgInduk'");			
	
				if(!$nmOrganisasiLengkap){
					$nmOrganisasiLengkap = $level." ".$nmLevel;
				} else {
					if($kdOrgInduk != 0) 
						$nmOrganisasiLengkap = $nmOrganisasiLengkap.", ".$level." ".$nmLevel;
					else
						$nmOrganisasiLengkap = $nmOrganisasiLengkap;
				}
												
			}
			
			return $nmOrganisasiLengkap;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	} */
	
	public function getNipPejabat($kd_org, $kd_jabatan) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$i_nik = $db->fetchOne("SELECT i_nik
									FROM v_jabatan_sekarang
									WHERE kd_struktur_org = '$kd_org' and
										kd_jabatan = '$kd_jabatan'");
										
							
			return $i_nik;						  
			
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
			
			$n_nama = $db->fetchOne("SELECT n_nama
									FROM tm_pegawai
									WHERE i_nik = '$i_nik'");
			return $n_nama;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	
	public function getNamaJabatan($kd_jabatan) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$nmJabatan = $db->fetchOne("SELECT nm_jabatan
									FROM simpeg_internal.tbl_jabatan
									WHERE kd_jabatan = '$kd_jabatan'");
			return $nmJabatan;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
}
?>