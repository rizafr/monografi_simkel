<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Klasifikasi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Adm_KlasifikasiController extends Zend_Controller_Action {
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
	    $this->klasifikasi  = 'cdr';
	   
		$this->klasifikasi_serv = Klasifikasi_Service::getInstance();
		//$this->cabang_serv = Cabang_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssoklasifikasi = new Zend_Session_Namespace('ssoklasifikasi');
	    $this->iduser =$ssoklasifikasi->userid;
	   // $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function klasifikasijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('klasifikasijs');
    }
	
	//test OPen report
	//----------------------
	public function klasifikasilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->n_klasifikasi =$ssogroup->n_klasifikasi;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_klasifikasi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"cabang"		=> trim($this->view->cabang),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKlasifikasiList = $this->klasifikasi_serv->cariKlasifikasiList($dataMasukan,0,0,0);
		$this->view->klasifikasiList = $this->klasifikasi_serv->cariKlasifikasiList($dataMasukan,$currentPage, $numToDisplay,$this->view->totKlasifikasiList);		
	}
	
	public function klasifikasidataAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm	= $_REQUEST['jenisForm'];
		$this->view->id_klasifikasi			= $_REQUEST['id_klasifikasi'];

		$this->view->groupList				= $this->klasifikasi_serv->getGroupList();
		$this->view->detailKlasifikasi	= $this->klasifikasi_serv->detailKlasifikasiById($this->view->id_klasifikasi);
		
	}
	
	public function klasifikasiAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_klasifikasi				= $_REQUEST['id_klasifikasi'];
		
		
		$id_klasifikasi				= $_REQUEST['id_klasifikasi'];
		$n_klasifikasi		= $_REQUEST['n_klasifikasi'];
								
		if($ssogroup->userid){
		$dataMasukanUpd = array("n_klasifikasi"			=> $n_klasifikasi
								);
		$this->view->klasifikasiInsert = $this->klasifikasi_serv->klasifikasiInsert($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Insert data", $nama." (".$id_klasifikasi.")");
		}
		$this->view->proses = "1";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->klasifikasiInsert;
		
		$this->klasifikasilistAction();
		$this->render('klasifikasilist');
	}
	
	public function klasifikasiupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$userid =$ssogroup->userid;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id_klasifikasi				= $_REQUEST['id_klasifikasi'];
		
		$id_klasifikasi				= $_REQUEST['id_klasifikasi'];
		$n_klasifikasi		= $_REQUEST['n_klasifikasi'];
		
		if($ssogroup->userid){
		$dataMasukanUpd = array("id_klasifikasi"		=> $id_klasifikasi,
								"n_klasifikasi"			=> $n_klasifikasi
								);
		$this->view->klasifikasiUpdate = $this->klasifikasi_serv->klasifikasiUpdate($dataMasukanUpd);
		$this->Logfile->createLog($this->view->namauser, "Ubah data", $nama." (".$id_klasifikasi.")");
		}
		$this->view->proses = "2";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->klasifikasiUpdate;
		
		$this->klasifikasilistAction();
		$this->render('klasifikasilist');
	}
	
	public function klasifikasihapusAction()
	{
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id_klasifikasi							= $_REQUEST['id_klasifikasi'];
		$this->view->cabang			= $_REQUEST['cabang'];

		$dataMasukan = array("id_klasifikasi" => $id_klasifikasi);

		$this->view->klasifikasiUpdate = $this->klasifikasi_serv->klasifikasiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data klasifikasi user", $n_klasifikasi." (".$id_klasifikasi.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->klasifikasiUpdate;
		
		$this->klasifikasilistAction();
		$this->render('klasifikasilist');
	}


}
?>