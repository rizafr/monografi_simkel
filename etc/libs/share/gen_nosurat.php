<?php 
class gen_nosurat {
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
	public function bulanRomawi($bulanDec)
	{
		$romawi = array("01" => "I",
						"02" => "II",
						"03" => "III",
						"04" => "IV",
						"05" => "V",
						"06" => "VI",
						"07" => "VII",
						"08" => "VIII",
						"09" => "IX",
						"10" => "X",
						"11" => "XI",
						"12" => "XII");
						
		return $romawi[$bulanDec];				
	}
	//jenissurat = 'AGENDA SURAT MASUK' ; 'MEMO'
	public function genNoAgendamasuk($jenissurat, $tanggal) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$genNosurat = new gen_nosurat();
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$tahun = substr($tanggal, 0,4);
			$bulanDec = substr($tanggal, 5,2);
			$sqlProses = "select gen_nosurat('$jenissurat', '$tahun')";	
			$noUrutAgendamasuk = $db->fetchOne($sqlProses);	
			
			$getBulanRomawi = $genNosurat->bulanRomawi($bulanDec);
			$noAgendamasuk = "$noUrutAgendamasuk/Setkab/TU/$getBulanRomawi/$tahun";
			return $noAgendamasuk;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}   
	
	public function genNoSuratKeluar($jenissurat, $tanggal) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$genNosurat = new gen_nosurat();
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$tahun = substr($tanggal, 0,4);
			$bulanDec = substr($tanggal, 5,2);
			$sqlProses = "select gen_nosurat('$jenissurat', '$tahun')";	
			$noUrutSuratKeluar = $db->fetchOne($sqlProses);	
			
			$getBulanRomawi = $genNosurat->bulanRomawi($bulanDec);
			$noSuratKeluar = "$noUrutSuratKeluar/Setkab/TU/$getBulanRomawi/$tahun";
			return $noSuratKeluar;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}  
	
	public function genNoTandaTerima($jenissurat, $tanggal) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$genNosurat = new gen_nosurat();
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$tahun = substr($tanggal, 0,4);
			$bulanDec = substr($tanggal, 5,2);
			$sqlProses = "select gen_nosurat('$jenissurat', '$tahun')";	
			
			$noUrutTandaTerima = $db->fetchOne($sqlProses);	
			
			$getBulanRomawi = $genNosurat->bulanRomawi($bulanDec);
			$noTandaTerima = "$noUrutTandaTerima/$getBulanRomawi/$tahun";
			return $noTandaTerima;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function genNoMemo($jenissurat, $tanggal) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		$genNosurat = new gen_nosurat();
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$tahun = substr($tanggal, 0,4);
			$bulanDec = substr($tanggal, 5,2);
			$sqlProses = "select gen_nosurat('$jenissurat', '$tahun')";	
			$noUrutMemo = $db->fetchOne($sqlProses);	
			
			$getBulanRomawi = $genNosurat->bulanRomawi($bulanDec);
			$noMemo = "M-$noUrutMemo/Setkab/TU/$getBulanRomawi/$tahun";
			return $noMemo;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
}
?>