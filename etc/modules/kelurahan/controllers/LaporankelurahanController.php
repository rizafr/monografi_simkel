<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/kelurahan/Rekamkelurahan_Service.php";
require_once "service/kelurahan/Laporankelurahan_Service.php";
require_once "service/kelurahan/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_LaporankelurahanController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->report_server = $registry->get('report_server'); 
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->baseData = "../etc/data/kelurahan/";	

		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->rekamkelurahan  = 'cdr';
	   
		$this->rekamkelurahan_serv = Rekamkelurahan_Service::getInstance();
		$this->laporankelurahan_serv = Laporankelurahan_Service::getInstance();
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssorekamkelurahan = new Zend_Session_Namespace('ssorekamkelurahan');
	    $this->iduser =$ssorekamkelurahan->user_id;
	   // $this->view->t_tensiuser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function laporankelurahanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('laporankelurahanjs');
    }
	
	public function laporankejadiankelurahanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('laporankejadiankelurahanjs');
    }
	
	//LAPORAN MONOGRAFI KELURAHAN
	//----------------------
	public function laporankelurahanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;

		if ( $_REQUEST['param1']){ $this->view->t_awal= $_REQUEST['param1'];}
		else {  $this->view->t_awal= $_REQUEST['t_awal']; 
		}

		if ( $_REQUEST['param2']){ $this->view->t_akhir= $_REQUEST['param2'];}
		else {  $this->view->t_akhir= $_REQUEST['t_akhir']; 
		}
		
	
	

		$detailKelurahan = $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel);
		$this->view->detailKelurahan = $detailKelurahan;
		
		$sortBy			= 'bulan';
		$sort			= 'asc';
		
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
	
		$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
		
		$this->view->kode_kel = $ssogroup->kd_kel;
		
		$this->view->kd_kel = $_REQUEST['kd_kel'];
		$this->view->bulan = $_REQUEST['bulan'];
		$this->view->tahun = $_REQUEST['tahun'];
		$this->view->kelurahanList	= $this->rekamkelurahan_serv->getKelurahanList();
		
		$this->view->laporankelurahanlist = $this->laporankelurahan_serv->getCariLaporanList($this->view->kd_kel,$this->view->bulan,$this->view->tahun);
		// var_dump($this->view->laporankelurahanlist);
	
	}
	
	public function laporankelurahandataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		
		$this->view->detaillaporankelurahan				= $this->laporankelurahan_serv->detaillaporankelurahanById($this->view->id);
		$this->view->detaillaporankelurahanList			= $this->laporankelurahan_serv->laporankelurahanList($this->view->id);
	}
	


	//LAPORAN KEJADIAN KELURAHAN
	//----------------------
	public function laporankejadiankelurahanlistAction()
	{
		
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		
		if ( $_REQUEST['bulan']){ $this->view->t_awal= $_REQUEST['param1'];}
		else {  $this->view->t_awal= $_REQUEST['t_awal']; 
		}

		if ( $_REQUEST['param2']){ $this->view->t_akhir= $_REQUEST['param2'];}
		else {  $this->view->t_akhir= $_REQUEST['t_akhir']; 
		}	

		$detailKelurahan = $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel);
		$this->view->detailKelurahan = $detailKelurahan;
		
		$sortBy			= 'bulan';
		$sort			= 'asc';
		
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
	
		$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
		
		$this->view->kode_kel = $ssogroup->kd_kel;
		
		$this->view->kd_kel = $_REQUEST['kd_kel'];
		$this->view->bulan = $_REQUEST['bulan'];
		$this->view->tahun = $_REQUEST['tahun'];
		 $this->view->kelurahanList	= $this->rekamkelurahan_serv->getKelurahanList();
		
		$this->view->laporankejadiankelurahanlist = $this->laporankelurahan_serv->getCariLaporanKejadianList($this->view->kd_kel,$this->view->bulan,$this->view->tahun);
		
		
		
		
	}
	
	public function laporankejadiankelurahandataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->bulan			= $_REQUEST['bulan'];
		$this->view->tahun		= $_REQUEST['tahun'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		
		$this->view->detaillaporankejadiankelurahan	= $this->laporankelurahan_serv->detaillaporankejadiankelurahanById($this->view->id);
		$this->view->detaillaporankejadiankelurahanList			= $this->laporankelurahan_serv->laporankejadiankelurahanList($this->view->id);
	}
	
		public function cetaklaporankejadiankelurahanAction(){
			$kd_kel = $this->_getParam("kd_kel");
			$bulan = $this->_getParam("bulan");
			$tahun = $this->_getParam("tahun");	
			$kelurahan = $this->_getParam("kelurahan");	
			$this->view->kd_kel = $kd_kel;
			$this->view->bulan = $bulan;
			$this->view->tahun = $tahun;
			$this->view->kelurahan = $kelurahan;
			
			$this->view->laporankejadiankelurahanlist = $this->laporankelurahan_serv->getCariLaporanKejadianList($this->view->kd_kel,$this->view->bulan,$this->view->tahun);
			// var_dump($this->view->laporankejadiankelurahanlist);
	}
	
	
	

	

	

}
?>