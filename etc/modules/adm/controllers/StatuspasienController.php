<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Statuspasien_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Adm_StatuspasienController extends Zend_Controller_Action {
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
	    $this->statuspasien  = 'cdr';
	   
		$this->statuspasien_serv = Statuspasien_Service::getInstance();
		//$this->cabang_serv = Cabang_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssostatuspasien = new Zend_Session_Namespace('ssostatuspasien');
	    $this->iduser =$ssostatuspasien->userid;
	   // $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function statuspasienjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statuspasienjs');
    }
	
	//test OPen report
	//----------------------
	public function statuspasienlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->n_status =$ssogroup->n_status;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_status';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatuspasienList = $this->statuspasien_serv->cariStatuspasienList($dataMasukan,0,0,0);
		$this->view->statuspasienList = $this->statuspasien_serv->cariStatuspasienList($dataMasukan,$currentPage, $numToDisplay,$this->view->totStatuspasienList);		
	}
	
	public function statuspasiendataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->id_status			= $_REQUEST['id_status'];

		$this->view->groupList				= $this->statuspasien_serv->getGroupList();
		$this->view->detailStatuspasien	= $this->statuspasien_serv->detailStatuspasienById($this->view->id_status);
		
	}
	
	public function statuspasienAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_status				= $_REQUEST['id_status'];
		
		
		$id_status				= $_REQUEST['id_status'];
		$n_status		= $_REQUEST['n_status'];
								
		if($ssogroup->userid){
		$dataMasukanUpd = array("n_status"			=> $n_status
								);
		$this->view->statuspasienInsert = $this->statuspasien_serv->statuspasienInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Insert data", $nama." (".$id_status.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->statuspasienInsert;
		
		$this->statuspasienlistAction();
		$this->render('statuspasienlist');
	}
	
	public function statuspasienupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_status				= $_REQUEST['id_status'];
		
		$id_status				= $_REQUEST['id_status'];
		$n_status		= $_REQUEST['n_status'];
		
		if($ssogroup->userid){
		$dataMasukanUpd = array("id_status"		=> $id_status,
								"n_status"			=> $n_status
								);
		$this->view->statuspasienUpdate = $this->statuspasien_serv->statuspasienUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Ubah data", $nama." (".$id_status.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->statuspasienUpdate;
		
		$this->statuspasienlistAction();
		$this->render('statuspasienlist');
	}
	
	public function statuspasienhapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id_status							= $_REQUEST['id_status'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("id_status" => $id_status);

		$this->view->statuspasienUpdate = $this->statuspasien_serv->statuspasienHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data statuspasien user", $n_status." (".$id_status.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->statuspasienUpdate;
		
		$this->statuspasienlistAction();
		$this->render('statuspasienlist');
	}


}
?>