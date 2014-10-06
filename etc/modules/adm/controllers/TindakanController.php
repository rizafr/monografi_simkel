<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Tindakan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Adm_TindakanController extends Zend_Controller_Action {
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
	    $this->tindakan  = 'cdr';
	   
		$this->tindakan_serv = Tindakan_Service::getInstance();
		//$this->cabang_serv = Cabang_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssotindakan = new Zend_Session_Namespace('ssotindakan');
	    $this->iduser =$ssotindakan->userid;
	   // $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function tindakanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('tindakanjs');
    }
	
	//test OPen report
	//----------------------
	public function tindakanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->n_tindakan =$ssogroup->n_tindakan;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_tindakan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totTindakanList = $this->tindakan_serv->cariTindakanList($dataMasukan,0,0,0);
		$this->view->tindakanList = $this->tindakan_serv->cariTindakanList($dataMasukan,$currentPage, $numToDisplay,$this->view->totTindakanList);		
	}
	
	public function tindakandataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->id_tindakan			= $_REQUEST['id_tindakan'];

		$this->view->groupList				= $this->tindakan_serv->getGroupList();
		$this->view->detailTindakan	= $this->tindakan_serv->detailTindakanById($this->view->id_tindakan);
		
	}
	
	public function tindakanAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_tindakan				= $_REQUEST['id_tindakan'];
		
		
		$id_tindakan				= $_REQUEST['id_tindakan'];
		$n_tindakan		= $_REQUEST['n_tindakan'];
								
		if($ssogroup->userid){
		$dataMasukanUpd = array("n_tindakan"			=> $n_tindakan
								);
		$this->view->tindakanInsert = $this->tindakan_serv->tindakanInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Insert data", $nama." (".$id_tindakan.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->tindakanInsert;
		
		$this->tindakanlistAction();
		$this->render('tindakanlist');
	}
	
	public function tindakanupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_tindakan				= $_REQUEST['id_tindakan'];
		
		$id_tindakan				= $_REQUEST['id_tindakan'];
		$n_tindakan		= $_REQUEST['n_tindakan'];
		
		if($ssogroup->userid){
		$dataMasukanUpd = array("id_tindakan"		=> $id_tindakan,
								"n_tindakan"			=> $n_tindakan
								);
		$this->view->tindakanUpdate = $this->tindakan_serv->tindakanUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Ubah data", $nama." (".$id_tindakan.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->tindakanUpdate;
		
		$this->tindakanlistAction();
		$this->render('tindakanlist');
	}
	
	public function tindakanhapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id_tindakan							= $_REQUEST['id_tindakan'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("id_tindakan" => $id_tindakan);

		$this->view->tindakanUpdate = $this->tindakan_serv->tindakanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data tindakan user", $n_tindakan." (".$id_tindakan.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->tindakanUpdate;
		
		$this->tindakanlistAction();
		$this->render('tindakanlist');
	}


}
?>