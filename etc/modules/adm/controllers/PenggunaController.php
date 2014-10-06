<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Pengguna_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";


class Adm_PenggunaController extends Zend_Controller_Action {
	private $auditor_serv;
	private $userid;
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
	    $this->pengguna  = 'cdr';
	   
		$this->pengguna_serv = Pengguna_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopengguna = new Zend_Session_Namespace('ssopengguna');
	    $this->useridpengguna =$ssopengguna->pengguna_userid;
	   // $this->view->namapengguna = $this->sso_serv->getDataPenggunaNama($this->useridpengguna);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function penggunajsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('penggunajs');
    }
	
	//test OPen report
	//----------------------
	public function penggunalistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_pengguna';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totPenggunaList = $this->pengguna_serv->cariPenggunaList($dataMasukan,0,0,0);
		$this->view->penggunaList = $this->pengguna_serv->cariPenggunaList($dataMasukan,$currentPage, $numToDisplay,$this->view->totPenggunaList);		
	}
	
	public function penggunadataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->userid			= $_REQUEST['userid'];

		$this->view->groupList				= $this->pengguna_serv->getGroupList();
		$this->view->detailPengguna	= $this->pengguna_serv->detailPenggunaById($this->view->userid);
		
	}
	
	public function penggunaAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$pengguna_userid =$ssogroup->pengguna_userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->userid				= $_REQUEST['userid'];
		
		
		$userid				= $_REQUEST['userid'];
		$username		= $_REQUEST['username'];
		$nama			= $_REQUEST['nama'];
		$password		= $_REQUEST['password'];
		$c_group		= $_REQUEST['c_group'];
		$c_status		= $_REQUEST['c_status'];
								
		if($ssogroup->pengguna_userid){
		$dataMasukanUpd = array("username"			=> $username,
								"nama"				=> $nama,
								"c_group"			=> $c_group,
								"password"			=> $password,
								"c_status"			=> $c_status
								);
		$this->view->penggunaInsert = $this->pengguna_serv->penggunaInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namapengguna, "Insert data", $nama." (".$userid.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->penggunaInsert;
		
		$this->penggunalistAction();
		$this->render('penggunalist');
	}
	
	public function penggunaupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$pengguna_userid =$ssogroup->pengguna_userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->userid				= $_REQUEST['userid'];
		$userid				= $_REQUEST['userid'];
		$username		= $_REQUEST['username'];
		$nama			= $_REQUEST['nama'];
		$password		= $_REQUEST['password'];
		$c_group		= $_REQUEST['c_group'];
		$c_status		= $_REQUEST['c_status'];
		
		if($ssogroup->pengguna_userid){
		$dataMasukanUpd = array("userid"				=> $userid,
								"username"			=> $username,
								"nama"				=> $nama,
								"c_group"			=> $c_group,
								"password"			=> $password,
								"c_status"			=> $c_status
								);
		$this->view->penggunaUpdate = $this->pengguna_serv->penggunaUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namapengguna, "Ubah data", $nama." (".$userid.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->penggunaUpdate;
		
		$this->penggunalistAction();
		$this->render('penggunalist');
	}
	
	public function penggunahapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$userid							= $_REQUEST['userid'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("userid" => $userid);

		$this->view->penggunaUpdate = $this->pengguna_serv->penggunaHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namapengguna, "Hapus data pengguna pengguna", $n_pengguna." (".$userid.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->penggunaUpdate;
		
		$this->penggunalistAction();
		$this->render('penggunalist');
	}


}
?>