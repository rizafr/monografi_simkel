<?php
class ntfy_notifikasi_Service {
    private static $instance;
   
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
	
	
	public function getHitungSuratMasuk($iorg) {
	   $Tglsrt=date('Ymd');
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$data =  $db->fetchOne("select count(*) FROM e_srt_masuk_0_tm where c_srt_indikator='I'
										and to_char(d_entry,'yyyymmdd') = '$Tglsrt' and i_orgb='$iorg' 
										or i_srt_agenda in (select i_srt_agenda from e_srt_masuk_dtl_tm where 
										to_char(d_entry,'yyyymmdd') = '$Tglsrt' and	n_srt_dispkpd='$iorg'
										and i_srt_agenda in (select i_srt_agenda from e_srt_masuk_0_tm  ))
										or i_srt_agenda in (select i_srt_agenda from e_srt_masuk_kepada_tm where 
										to_char(d_entry,'yyyymmdd') = '$Tglsrt' and n_srt_kepada='$iorg'
										and i_srt_agenda in (select i_srt_agenda from e_srt_masuk_0_tm  ))" );										
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	} 
	
	public function getHitungPerpustakaan($ipegnip) {
	   $Tglsrt=date('Ymd');
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$data =  $db->fetchOne("select count(*)from e_dok_pinjam_0_tm 
			                        where i_peg_nip='$ipegnip'
									and now() > to_date(d_dok_pinjam,'YYYY-mm-dd') + 7 
									and d_dok_kembali is null" );										
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}

	public function getHitungCuti($ipegnip) {
	   $Tglsrt=date('Ymd');
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
		$data =  $db->fetchOne("select q_cuti_thnskrg from e_sdm_cuti_sisa_tm where i_peg_nip='$ipegnip'" );										
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}	

}

?>