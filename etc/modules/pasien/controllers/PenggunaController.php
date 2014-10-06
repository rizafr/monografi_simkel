<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pasien/Pengguna_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Pasien_PenggunaController extends Zend_Controller_Action {
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
	    $this->pengguna  = 'cdr';
	   
		$this->pengguna_serv = Pengguna_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopengguna = new Zend_Session_Namespace('ssopengguna');
	    $this->iduser =$ssopengguna->user_id;
	   // $this->view->usernameuser = $this->sso_serv->getDataUserNama($this->iduser);
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
		//$this->view->agamaList = $this->ref_serv->getAgamaList();
		$this->view->groupList = $this->ref_serv->getStatusList();
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_pengguna';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"id_pendaftar"	=> trim($ssogroup->id),
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
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->userid			= $_REQUEST['userid'];
		$this->view->groupList		= $this->ref_serv->getGroupList();
		$this->view->detailPengguna	= $this->pengguna_serv->detailPenggunaById($this->view->userid);
	}
	
	public function penggunaAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->user_id =$ssogroup->user_id;

		$username							= $_POST['username'];
		$c_group							= $_POST['c_group'];
		$pass						= $_POST['pass'];
		$nama						= $_POST['nama'];

		$dataMasukan	= array("username"				=> $username,
								"c_group"				=> $c_group,
								"pass"					=> $pass,
								"nama"					=> $nama
								);

		$this->view->penggunaInsert = $this->pengguna_serv->penggunaInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Pengguna";
		$this->view->hasil = $this->view->penggunaInsert;
		
		$this->penggunalistAction();
		$this->render('penggunalist');

	}
	
	public function penggunaupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$userid							= $_POST['userid'];
		$jenisForm						= $_POST['jenisForm'];

		$username							= $_POST['username'];
		$c_group							= $_POST['c_group'];
		$pass						= $_POST['pass'];
		$nama						= $_POST['nama'];

		$dataMasukanUpd = array("userid"				=> $userid,
								"username"				=> $username,
								"c_group"				=> $c_group,
								"pass"					=> $pass,
								"nama"					=> $nama
								);
		$this->view->penggunaUpdate = $this->pengguna_serv->penggunaUpdate($dataMasukanUpd);

		$this->Logfile->createLog($this->view->usernameuser, "Ubah data", $username." (".$userid.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Pengguna";
		$this->view->hasil = $this->view->penggunaUpdate;
		
		$this->penggunalistAction();
		$this->render('penggunalist');
	}



	
	public function penggunahapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$userid						= $_REQUEST['userid'];

		$dataMasukan = array("userid" => $userid);

		$this->view->penggunaUpdate = $this->pengguna_serv->penggunaHapus($dataMasukan);
		$this->Logfile->createLog($this->view->usernameuser, "Hapus data pengguna user", $n_pengguna." (".$userid.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Pengguna";
		$this->view->hasil = $this->view->penggunaUpdate;
		
		$this->penggunalistAction();
		$this->render('penggunalist');
	}

	
}
?>