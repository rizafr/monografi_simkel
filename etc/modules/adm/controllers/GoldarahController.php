<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Goldarah_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Adm_GoldarahController extends Zend_Controller_Action {
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
	    $this->goldarah  = 'cdr';
	   
		$this->goldarah_serv = Goldarah_Service::getInstance();
		//$this->cabang_serv = Cabang_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogoldarah = new Zend_Session_Namespace('ssogoldarah');
	    $this->iduser =$ssogoldarah->userid;
	   // $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function goldarahjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('goldarahjs');
    }
	
	//test OPen report
	//----------------------
	public function goldarahlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->n_goldar =$ssogroup->n_goldar;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_goldar';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGoldarahList = $this->goldarah_serv->cariGoldarahList($dataMasukan,0,0,0);
		$this->view->goldarahList = $this->goldarah_serv->cariGoldarahList($dataMasukan,$currentPage, $numToDisplay,$this->view->totGoldarahList);		
	}
	
	public function goldarahdataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->id_goldar			= $_REQUEST['id_goldar'];

		$this->view->groupList				= $this->goldarah_serv->getGroupList();
		$this->view->detailGoldarah	= $this->goldarah_serv->detailGoldarahById($this->view->id_goldar);
		
	}
	
	public function goldarahAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_goldar				= $_REQUEST['id_goldar'];
		
		
		$id_goldar				= $_REQUEST['id_goldar'];
		$n_goldar		= $_REQUEST['n_goldar'];
								
		if($ssogroup->userid){
		$dataMasukanUpd = array("n_goldar"			=> $n_goldar
								);
		$this->view->goldarahInsert = $this->goldarah_serv->goldarahInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Insert data", $nama." (".$id_goldar.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->goldarahInsert;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}
	
	public function goldarahupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_goldar				= $_REQUEST['id_goldar'];
		
		$id_goldar				= $_REQUEST['id_goldar'];
		$n_goldar		= $_REQUEST['n_goldar'];
		
		if($ssogroup->userid){
		$dataMasukanUpd = array("id_goldar"		=> $id_goldar,
								"n_goldar"			=> $n_goldar
								);
		$this->view->goldarahUpdate = $this->goldarah_serv->goldarahUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Ubah data", $nama." (".$id_goldar.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->goldarahUpdate;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}
	
	public function goldarahhapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id_goldar							= $_REQUEST['id_goldar'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("id_goldar" => $id_goldar);

		$this->view->goldarahUpdate = $this->goldarah_serv->goldarahHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data goldarah user", $n_goldar." (".$id_goldar.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->goldarahUpdate;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}


}
?>