<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/kelurahan/Kejadian_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_KejadianController extends Zend_Controller_Action {
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
	    $this->kejadian  = 'cdr';
	   
		$this->kejadian_serv = Kejadian_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();
		$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
		$this->kd_kel = $ssogroup->kd_kel;
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssokejadian = new Zend_Session_Namespace('ssokejadian');
	    $this->kd_kel =$ssokejadian->kd_kel;
		$this->kd_kel =$ssousergroup->kd_kel;
	   // $this->view->n_namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
	function anti_injection($data){
		$filter = trim(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));

		return $filter;

	}
	
    public function indexAction() {
	   
    }
	
	public function kejadianjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kejadianjs');
    }
	
	//test OPen report
	//----------------------
	public function kejadianlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->kelurahan =$ssogroup->kelurahan;
		$kd_kel =$ssogroup->kd_kel;
		$this->view->kd_kel =$ssogroup->kd_kel;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}
		
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= trim(stripslashes(strip_tags(htmlspecialchars($_REQUEST['carii']))));
		
		
		$sortBy			= 'kd_kel';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"kd_kel"	=> 		$kd_kel,
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKejadianList = $this->kejadian_serv->cariKejadianList($dataMasukan,0,0,0);
		$this->view->kejadianList = $this->kejadian_serv->cariKejadianList($dataMasukan,$currentPage, $numToDisplay,$this->view->totkejadianList);
		//var_dump($this->view->kejadianList );		
	}
	
	public function kejadiandataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->kd_kel			= $_REQUEST['kd_kel'];
		$this->view->idx_kejadian	= $_REQUEST['idx_kejadian'];
		
		$this->view->detailKejadian				= $this->kejadian_serv->detailKejadianById($this->view->kd_kel,$this->view->idx_kejadian);
		// var_dump($this->view->kd_kel);
		// var_dump($this->view->idx_kejadian);
		// var_dump($this->view->detailKejadian);
	}
	
	public function kejadianAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->group_id =$ssogroup->group_id;
		$this->view->user_id =$ssogroup->user_id;

		$kd_kel				= $_POST['kd_kel'];
		$hari				= $_POST['hari'];
		$tanggal			= $_POST['tanggal'];
		$uraian				= trim($_POST['uraian']);
		$waktu				= $_POST['waktu'];
		$lokasi				= trim($_POST['lokasi']);
		$kerugian			= $_POST['kerugian'];
		$nominal			= $_POST['nominal'];
		$pelapor			= $_POST['pelapor'];
		$keterangan			= trim($_POST['keterangan']);
		$lampiran			= $_POST['lampiran'];
	

		$dataMasukan	= array("kd_kel"		=> $kd_kel,
								"hari"			=> $hari,
								"tanggal"		=> $tanggal,
								"uraian"		=> $uraian,
								"waktu"			=> $waktu,
								"lokasi"		=> $lokasi,
								"kerugian"		=> $kerugian,
								"nominal"		=> $nominal,
								"pelapor"		=> $pelapor,
								"keterangan"	=> $keterangan,
								"lampiran"		=> $lampiran

								);

		$this->view->kejadianInsert = $this->kejadian_serv->kejadianInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->kejadianInsert;
		
		$this->kejadianlistAction();
		$this->render('kejadianlist');

	}
	
	public function kejadianupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$idx_kejadian		= $_POST['idx_kejadian'];
		$jenisForm			= $_POST['jenisForm'];

		$kd_kel			= $_POST['kd_kel'];
		$hari				= $_POST['hari'];
		$tanggal			= $_POST['tanggal'];
		$uraian				= trim($_POST['uraian']);
		$waktu				= $_POST['waktu'];
		$lokasi				= trim($_POST['lokasi']);
		$kerugian			= $_POST['kerugian'];
		$nominal			= $_POST['nominal'];
		$pelapor			= $_POST['pelapor'];
		$keterangan			= trim($_POST['keterangan']);
		$lampiran			= $_POST['lampiran'];
		
		


		$dataMasukan	= array(
								"idx_kejadian"	=> $idx_kejadian,
								"kd_kel"		=> $kd_kel,
								"hari"			=> $hari,
								"tanggal"		=> $tanggal,
								"uraian"		=> $uraian,
								"waktu"			=> $waktu,
								"lokasi"		=> $lokasi,
								"kerugian"		=> $kerugian,
								"nominal"		=> $nominal,
								"pelapor"		=> $pelapor,
								"keterangan"	=> $keterangan,
								"lampiran"		=> $lampiran

								);
								
		$this->view->kejadianUpdate = $this->kejadian_serv->kejadianUpdate($dataMasukan);
		
		$this->view->kd_kel = $kd_kel	;
		// var_dump($this->view->kelurahan);
		// var_dump($dataMasukan);
		// var_dump($this->view->kejadianUpdate);
		$this->Logfile->createLog($this->view->kelurahan," Ubah data ", $kd_kel." (".$user_id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Umum kejadian";
		$this->view->hasil = $this->view->kejadianUpdate;
		
		$this->kejadianlistAction();
		$this->render('kejadianlist');
	}



	
	public function kejadianhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$idx_kejadian				= $_REQUEST['idx_kejadian'];
		$kd_kel						= $_REQUEST['kd_kel'];

		$dataMasukan = array(
							"kd_kel"=> $kd_kel,
							"idx_kejadian"=> $idx_kejadian
							);

		$this->view->kejadianUpdate = $this->kejadian_serv->kejadianHapus($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Hapus data kejadian user", $n_kejadian." (".$idx_kejadian.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->kejadianUpdate;
		
		$this->kejadianlistAction();
		$this->render('kejadianlist');
	}

	public function cetakkartupdfAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];

		$this->view->detailPasien	= $this->kejadian_serv->detailkejadianById($this->view->id);
		$this->view->medrecList		= $this->kejadian_serv->medrecList($this->view->detailPasien['kode_pasien']);
	}



	public function getKabAction(){
		$this->view->list		=  $this->ref_serv->getKabByPropList(trim($_REQUEST["propinsi"]));
	}

}
?>