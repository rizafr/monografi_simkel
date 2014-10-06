<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/tamu/Tamu_Kritik_Service.php";
require_once "service/aplikasi/Aplikasi_Referensi_Service.php";
require_once "share/format_date.php";

class Cms_ForumController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	   // $this->user  = 'cdr';

		$this->kritik_serv = Tamu_Kritik_Service::getInstance();
		$this->ref_serv = Aplikasi_Referensi_Service::getInstance();
		
		$ssousergroup = new Zend_Session_Namespace('ssousergroup');
		$this->view->user =$ssousergroup->par;
    }
	
    public function indexAction() {
	   
    }
	
	public function kritikjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kritikjs');
    }
	
	//test OPen report
	//----------------------
	public function kritiklistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari'];
		$katakunciCari 	= $_REQUEST['katakunciCari'];
		$sortBy			= 'NAMA';
		$sort			= 'desc';
		
		$ssousergroup = new Zend_Session_Namespace('ssousergroup');
		$this->view->user =$ssousergroup->user;

		$dataMasukan = array(
							"kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 60;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totkritikList = $this->kritik_serv->cariKritikList($dataMasukan,0,0,0);
		
		$this->view->kritikList = $this->kritik_serv->cariKritikList($dataMasukan,$currentPage, $numToDisplay,$this->view->totkritikList);
		
	}

	public function kritikolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$this->view->id = $_REQUEST['id'];
		$this->view->detailKritik = $this->kritik_serv->detailKritikById($this->view->id);
		$iEntry 		= $this->user;

	}

	
	public function kritikAction()
	{
		$format_date	= new format_date();
		$this->view->id	= $_REQUEST['id'];
		$NAMA 			= $_POST['NAMA'];
		$TELP 			= $_POST['TELP'];
		$SARAN 			= $_POST['SARAN'];
								
		$iEntry 		= $this->user;

		$dataMasukan = array("NAMA"			=>$NAMA,
							 "TELP"			=>$TELP,
							 "SARAN"       	=>$SARAN,
							 "TGL_SARAN"	=>date('m/d/Y')
								);	

		$hasil = $this->kritik_serv->kritikInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Data Tamu";
		$this->view->hasil = $hasil;
		$this->kritiklistAction();
		$this->render('kritiklist');
	}

	public function ubahStatusAction()
	{
		$format_date		= new format_date();
		$this->view->id		= $_REQUEST['id'];
		$status 			= $_POST['status'];
								
		$iEntry 			= $this->user;

		$dataMasukan = array("id"				=>$id,
							 "STATUS"			=>$status
							);	

		$hasil = $this->kritik_serv->ubahStatus($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Data Tamu";
		$this->view->hasil = $hasil;
		$this->kritiklistAction();
		$this->render('kritiklist');
	}

	public function kritikupdateAction()
	{
		$format_date = new format_date();
		$this->view->id	= $_REQUEST['id'];
		$NAMA 			= $_POST['NAMA'];
		$TELP 			= $_POST['TELP'];
		$SARAN 			= $_POST['SARAN'];

	
		$iEntry 		= $this->user;
		
		$dataMasukan = array("id"			=>$this->view->id,
							 "NAMA"			=>$NAMA,
							 "TELP"			=>$TELP,
							 "SARAN"       	=>$SARAN
								);	

		$hasil = $this->kritik_serv->kritikUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Kritik";
		$this->view->hasil = $hasil;
		$this->kritiklistAction();
		$this->render('kritiklist');
	}
	
	public function kritikhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("i_kritik" => $id);
		
		$hasil = $this->kritik_serv->kritikHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Kritik";
		$this->view->hasil = $hasil;
		
		$this->kritiklistAction();
		$this->render('kritiklist');
	}
	
	public function detailkritikAction()
	{
		$idKritik = $_REQUEST['idKritik'];
		
		$this->view->detailKritik = $this->kritik_serv->detailKritikById($idKritik);
	}
}
?>