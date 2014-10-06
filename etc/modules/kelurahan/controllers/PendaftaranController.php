<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pasien/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_PendaftaranController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->report_server = $registry->get('report_server'); 
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->pendaftaran  = 'cdr';
	   
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopendaftaran = new Zend_Session_Namespace('ssopendaftaran');
	    $this->iduser =$ssopendaftaran->user_id;
	   // $this->view->n_namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function pendaftaranjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('pendaftaranjs');
    }
	
	//test OPen report
	//----------------------
	public function pendaftaranlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}
		$this->view->agamaList = $this->ref_serv->getAgamaList();
		$this->view->statusList = $this->ref_serv->getStatusList();
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_pendaftaran';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"id_pendaftar"	=> trim($ssogroup->id),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totPendaftaranList = $this->pendaftaran_serv->cariPendaftaranList($dataMasukan,0,0,0);
		$this->view->pendaftaranList = $this->pendaftaran_serv->cariPendaftaranList($dataMasukan,$currentPage, $numToDisplay,$this->view->totPendaftaranList);		
	}
	
	public function pendaftarandataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		$this->view->agamaList		= $this->ref_serv->getAgamaList();
		$this->view->statusList		= $this->ref_serv->getStatusList();
		$this->view->propinsiList	= $this->ref_serv->getPropinsiList();
		$this->view->goldarList		= $this->ref_serv->getGoldarList();
		$this->view->detailPendaftaran				= $this->pendaftaran_serv->detailPendaftaranById($this->view->id);
	}
	
	public function pendaftaranAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->user_id =$ssogroup->user_id;

		$c_noreg							= $_POST['c_noreg'];
		$n_nama							= $_POST['n_nama'];
		$c_kelamin						= $_POST['c_kelamin'];
		$t_waktu							= $_POST['t_waktu'];
		$n_tempat							= $_POST['n_tempat'];
		$t_berat							= $_POST['t_berat'];
		$t_tinggi							= $_POST['t_tinggi'];
		$t_tekanan							= $_POST['t_tekanan'];
		$t_denyut						= $_POST['t_denyut'];
		$t_frequensi							= $_POST['t_frequensi'];
		$t_suhu						= $_POST['t_suhu'];
		$n_mata							= $_POST['n_mata'];
		$n_tht							= $_POST['n_tht'];
		$n_gigi						= $_POST['n_gigi'];
		$n_leher						= $_POST['n_leher'];
		$n_jantung					= $_POST['n_jantung'];
		$n_paru						= $_POST['n_paru'];
		$n_perut						= $_POST['n_perut'];
		$n_gerak						= $_POST['n_gerak'];
		$n_gizi						= $_POST['n_gizi'];
		$n_potensi					= $_POST['n_potensi'];
		$n_mental						= $_POST['n_mental'];
		$n_reproduksi					= $_POST['n_reproduksi'];
		$n_kematangan					= $_POST['n_kematangan'];
		$n_hb							= $_POST['n_hb'];
		$n_feses						= $_POST['n_feses'];
		$n_jasmani					= $_POST['n_jasmani'];
		$c_bayi						= $_POST['c_bayi'];
		$c_imunisasi1					= $_POST['c_imunisasi1'];
		$c_imunisasi2					= $_POST['c_imunisasi2'];
		$c_imunisasi3					= $_POST['c_imunisasi3'];
		
		

		if($d_lahir) {
		$bln = substr($d_lahir, 3, 2);$tgl = substr($d_lahir, 0, 2);$thn = substr($d_lahir, 6, 4);
		$d_lahir = $thn."-".$bln."-".$tgl; 
		} else {$d_lahir ="-";}

		$dataMasukan	= array("c_noreg"				=> $c_noreg,
								"n_nama"				=> $n_nama,
								"c_kelamin"				=> $c_kelamin,
								"t_waktu"				=> $t_waktu,
								"n_tempat"				=> $n_tempat,
								"t_berat"				=> $t_berat,
								"t_tinggi"		=>$t_tinggi,
								"t_tekanan"		=>$t_tekanan,
								"t_denyut"		=>$t_denyut,
								"t_frequensi"		=>$t_frequensi,
								"t_suhu"		=>$t_suhu,
								"n_mata"		=>$n_mata,
								"n_tht"		=>$n_tht,
								"n_gigi"		=>$n_gigi,
								"n_leher"		=>$n_leher,
								"n_jantung"	=>$n_jantung,
								"n_paru"		=>$n_paru,
								"n_perut"		=>$n_perut,
								"n_gerak"		=>$n_gerak,
								"n_gizi"		=>$n_gizi,
								"n_potensi"	=>$n_potensi,
								"n_mental"	=>$n_mental,
								"n_reproduksi"	=>$n_reproduksi,
								"n_kematangan"	=>$n_kematangan,
								"n_hb"	=>$n_hb,
								"n_feses"	=>$n_feses,
								"n_jasmani"	=>$n_jasmani,
								"c_bayi"	=>$c_bayi,
								"c_imunisasi1"	=>$c_imunisasi1,
								"c_imunisasi2"	=>$c_imunisasi2,
								"c_imunisasi3"	=>$c_imunisasi3,
						
								);

		$this->view->pendaftaranInsert = $this->pendaftaran_serv->pendaftaranInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->pendaftaranInsert;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');

	}
	
	public function pendaftaranupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id								= $_POST['id'];
		$jenisForm						= $_POST['jenisForm'];

		$c_noreg							= $_POST['c_noreg'];
		$n_nama							= $_POST['n_nama'];
		$c_kelamin						= $_POST['c_kelamin'];
		$t_waktu							= $_POST['t_waktu'];
		$n_tempat							= $_POST['n_tempat'];
		$t_berat							= $_POST['t_berat'];
		$t_tinggi							= $_POST['t_tinggi'];
		$t_tekanan							= $_POST['t_tekanan'];
		$t_denyut						= $_POST['t_denyut'];
		$t_frequensi							= $_POST['t_frequensi'];
		$t_suhu						= $_POST['t_suhu'];
		$n_mata							= $_POST['n_mata'];
		$n_tht							= $_POST['n_tht'];
		$n_gigi						= $_POST['n_gigi'];
		$n_leher						= $_POST['n_leher'];
		$n_jantung					= $_POST['n_jantung'];
		$n_paru						= $_POST['n_paru'];
		$n_perut						= $_POST['n_perut'];
		$n_gerak						= $_POST['n_gerak'];
		$n_gizi						= $_POST['n_gizi'];
		$n_potensi					= $_POST['n_potensi'];
		$n_mental						= $_POST['n_mental'];
		$n_reproduksi					= $_POST['n_reproduksi'];
		$n_kematangan					= $_POST['n_kematangan'];
		$n_hb							= $_POST['n_hb'];
		$n_feses						= $_POST['n_feses'];
		$n_jasmani					= $_POST['n_jasmani'];
		$c_bayi						= $_POST['c_bayi'];
		$c_imunisasi1					= $_POST['c_imunisasi1'];
		$c_imunisasi2					= $_POST['c_imunisasi2'];
		$c_imunisasi3					= $_POST['c_imunisasi3'];
		if($d_lahir) {
		$bln = substr($d_lahir, 3, 2);$tgl = substr($d_lahir, 0, 2);$thn = substr($d_lahir, 6, 4);
		$d_lahir = $thn."-".$bln."-".$tgl; 
		} else {$d_lahir ="-";}


		$dataMasukanUpd = array("c_noreg"				=> $c_noreg,
								"n_nama"				=> $n_nama,
								"c_kelamin"				=> $c_kelamin,
								"t_waktu"				=> $t_waktu,
								"n_tempat"				=> $n_tempat,
								"t_berat"				=> $t_berat,
								"t_tinggi"		=>$t_tinggi,
								"t_tekanan"		=>$t_tekanan,
								"t_denyut"		=>$t_denyut,
								"t_frequensi"		=>$t_frequensi,
								"t_suhu"		=>$t_suhu,
								"n_mata"		=>$n_mata,
								"n_tht"		=>$n_tht,
								"n_gigi"		=>$n_gigi,
								"n_leher"		=>$n_leher,
								"n_jantung"	=>$n_jantung,
								"n_paru"		=>$n_paru,
								"n_perut"		=>$n_perut,
								"n_gerak"		=>$n_gerak,
								"n_gizi"		=>$n_gizi,
								"n_potensi"	=>$n_potensi,
								"n_mental"	=>$n_mental,
								"n_reproduksi"	=>$n_reproduksi,
								"n_kematangan"	=>$n_kematangan,
								"n_hb"	=>$n_hb,
								"n_feses"	=>$n_feses,
								"n_jasmani"	=>$n_jasmani,
								"c_bayi"	=>$c_bayi,
								"c_imunisasi1"	=>$c_imunisasi1,
								"c_imunisasi2"	=>$c_imunisasi2,
								"c_imunisasi3"	=>$c_imunisasi3,
								);
		$this->view->pendaftaranUpdate = $this->pendaftaran_serv->pendaftaranUpdate($dataMasukanUpd);

		$this->Logfile->createLog($this->view->n_namauser, "Ubah data", $n_nama." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Umum Pendaftaran";
		$this->view->hasil = $this->view->pendaftaranUpdate;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');
	}



	
	public function pendaftaranhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];

		$dataMasukan = array("id" => $id);

		$this->view->pendaftaranUpdate = $this->pendaftaran_serv->pendaftaranHapus($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Hapus data pendaftaran user", $n_pendaftaran." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->pendaftaranUpdate;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');
	}

	public function cetakkartupdfAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];

		$this->view->detailPasien	= $this->pendaftaran_serv->detailPendaftaranById($this->view->id);
		$this->view->medrecList		= $this->pendaftaran_serv->medrecList($this->view->detailPasien['kode_pasien']);
	}

	public function dosenlistAction() {
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {$pageNumber = 1;}

		$itemPerPage = $_REQUEST['numToDisplay'];
		if(!$itemPerPage) {$itemPerPage 	= 30;}
		
		$this->view->numToDisplay = $itemPerPage;
		$this->view->currentPage = $pageNumber;
		$ssologin = new Zend_Session_Namespace('login_data');
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->jum 			= $_REQUEST['jum'];
		
		$dataMasukan = array("kategoriCari" => trim($this->view->kategoriCari),
							"katakunciCari" => trim($this->view->carii)
							 );
		
							 
		$this->view->totDosenList 			= $this->ref_pendaftaran_serv->getDosenList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage			= $itemPerPage;
		$this->view->dosenList 				= $this->ref_pendaftaran_serv->getDosenList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totDosenList);
	}

	public function prodilistAction() {
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {$pageNumber = 1;}

		$itemPerPage = $_REQUEST['numToDisplay'];
		if(!$itemPerPage) {$itemPerPage 	= 30;}
		
		$this->view->numToDisplay = $itemPerPage;
		$this->view->currentPage = $pageNumber;
		$ssologin = new Zend_Session_Namespace('login_data');
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$c_prodi 					= $_REQUEST['c_prodi'];
		
		$dataMasukan = array("kategoriCari" => trim($this->view->kategoriCari),
							"katakunciCari" => trim($this->view->carii),
							"c_prodi"		=> trim($c_prodi)
							 );
							 
		$this->view->totProdiList 		= $this->ref_pendaftaran_serv->getProdiList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage			= $itemPerPage;
		$this->view->prodiList 			= $this->ref_pendaftaran_serv->getProdiList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totProdiList);
		
	}

	public function getKabAction(){
		$this->view->list		=  $this->ref_serv->getKabByPropList(trim($_REQUEST["propinsi"]));
	}

}
?>