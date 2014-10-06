<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Groupuser_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Adm_GroupuserController extends Zend_Controller_Action {
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
	    $this->groupuser  = 'cdr';
	   
		$this->groupuser_serv = Groupuser_Service::getInstance();
		//$this->cabang_serv = Cabang_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroupuser = new Zend_Session_Namespace('ssogroupuser');
	    $this->iduser =$ssogroupuser->user_id;
	   // $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function groupuserjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('groupuserjs');
    }
	
	//test OPen report
	//----------------------
	public function groupuserlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->n_group =$ssogroup->n_group;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_groupuser';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGroupuserList = $this->groupuser_serv->cariGroupuserList($dataMasukan,0,0,0);
		$this->view->groupuserList = $this->groupuser_serv->cariGroupuserList($dataMasukan,$currentPage, $numToDisplay,$this->view->totGroupuserList);		
	}
	
	public function groupuserdataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->id			= $_REQUEST['id'];

		$this->view->groupList				= $this->groupuser_serv->getGroupList();
		$this->view->detailGroupuser	= $this->groupuser_serv->detailGroupuserById($this->view->id);
		
	}
	
	public function groupuserAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		
		
		$id				= $_REQUEST['id'];
		$n_group		= $_REQUEST['n_group'];
								
		if($ssogroup->user_id){
		$dataMasukanUpd = array("n_group"			=> $n_group
								);
		$this->view->groupuserInsert = $this->groupuser_serv->groupuserInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Insert data", $nama." (".$id.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->groupuserInsert;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}
	
	public function groupuserupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		
		$id				= $_REQUEST['id'];
		$n_group		= $_REQUEST['n_group'];
		
		if($ssogroup->user_id){
		$dataMasukanUpd = array("id"				=> $id,
								"n_group"			=> $n_group
								);
		$this->view->groupuserUpdate = $this->groupuser_serv->groupuserUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Ubah data", $nama." (".$id.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->groupuserUpdate;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}
	
	public function groupuserhapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("id" => $id);

		$this->view->groupuserUpdate = $this->groupuser_serv->groupuserHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data groupuser user", $n_groupuser." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->groupuserUpdate;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}


}
?>