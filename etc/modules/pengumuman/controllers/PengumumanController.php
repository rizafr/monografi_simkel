<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pengumuman/Pengumuman_Pengumuman_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "service/blu/Blu_Bluanggaran_Service.php";


require_once "service/adm/Adm_Admkegiatan_Service.php";
require_once "service/adm/Adm_Admsubkegiatan_Service.php";
require_once "service/adm/Adm_Admkodemax_Service.php";
require_once "service/adm/Adm_Admprodi_Service.php";


class Pengumuman_PengumumanController extends Zend_Controller_Action {
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
	   // $ssopengumuman = new Zend_Session_Namespace('ssopengumuman');
	   //echo "TEST ".$ssopengumuman->n_pengumuman_grp." ".$ssopengumuman->i_pengumuman." ".$ssopengumuman->i_peg_level_position;
	   $this->pengumuman  = 'cdr';
	   
		$this->pengumuman_serv = Pengumuman_Pengumuman_Service::getInstance();
		$this->kegiatan_serv = Adm_Admkegiatan_Service::getInstance();
		$this->subkegiatan_serv = Adm_Admsubkegiatan_Service::getInstance();
		$this->kodemax_serv = Adm_Admkodemax_Service::getInstance();
		$this->prodi_serv = Adm_Admprodi_Service::getInstance();
		
		
		$this->blu_serv = Blu_Bluanggaran_Service::getInstance();


		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopengumuman = new Zend_Session_Namespace('ssopengumuman');
	    $this->iduser =$ssopengumuman->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function pengumumanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('pengumumanjs');
    }
	
	//test OPen report
	//----------------------
	public function pengumumanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 


		if ( $_REQUEST['param1']){$this->view->prodi= $_REQUEST['param1'];}
		else { $this->view->prodi = $_REQUEST['prodi'];
		}

		if ( $_REQUEST['param2']){$this->view->status= $_REQUEST['param2'];}
		else {$this->view->status 		= $_REQUEST['status'];
		}

		if ( $_REQUEST['param3']){$this->view->kegiatan= $_REQUEST['param3'];}
		else { $this->view->kegiatan	= $_REQUEST['kegiatan'];
		}

		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= $_POST['kategoriCari'];
		$sortBy			= 'n_nama';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"prodi" => $this->view->prodi,
							"status" => $this->view->status,
							"kegiatan" => $this->view->kegiatan,
							"jenisPengumuman" => '2',
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;

		$this->view->totpengumumanList = $this->pengumuman_serv->caripengumumanList($dataMasukan,0,0,0);
		$this->view->pengumumanList = $this->pengumuman_serv->caripengumumanList($dataMasukan,$currentPage, $numToDisplay,$this->view->totpengumumanList);		
	}
	
	public function pengumumanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$this->view->id = $_REQUEST['id'];

		if ( $_REQUEST['param1']){$this->view->prodi= $_REQUEST['param1'];}
		else { $this->view->prodi = $_REQUEST['prodi'];
		}

		if ( $_REQUEST['param2']){$this->view->status= $_REQUEST['param2'];}
		else {$this->view->status 		= $_REQUEST['status'];
		}

		if ( $_REQUEST['param3']){$this->view->kegiatan= $_REQUEST['param3'];}
		else { $this->view->kegiatan	= $_REQUEST['kegiatan'];
		}

		$this->view->detailpengumuman = $this->pengumuman_serv->detailpengumumanById($this->view->id);

	}

	
	public function pengumumanAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id							= $_POST['id'];       
		$e_pengumuman				= $_POST['e_pengumuman'];    

		$dataMasukan = array("e_pengumuman"			=>$e_pengumuman
							);
		$this->view->pengumumanInsert = $this->pengumuman_serv->pengumumanInsert($dataMasukan);

		$this->Logfile->createLog($this->view->namauser, "Insert data pengumuman", $n_pengumuman." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "pengumuman";
		$this->view->hasil = $this->view->pengumumanInsert;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}
	
	public function pengumumanupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id							= $_POST['id'];       
		$e_pengumuman				= $_POST['e_pengumuman'];    

		$dataMasukan = array("id"				=>$id,
							"e_pengumuman"			=>$e_pengumuman
							);
		
		$this->view->pengumumanUpdate = $this->pengumuman_serv->pengumumanUpdate($dataMasukan);
	
		
		$this->Logfile->createLog($this->view->namauser, "Ubah data pengumuman", $n_pengumuman." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "pengumuman";
		$this->view->hasil = $this->view->pengumumanUpdate;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}

	public function pengumumanhapusAction()
	{
		$this->view->prodi			= $_POST['prodi'];      
		$this->view->status 		= $_REQUEST['status'];
		$this->view->kegiatan		= $_REQUEST['kegiatan'];

		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->pengumumanUpdate = $this->pengumuman_serv->pengumumanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data pengumuman", $n_pengumuman." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "pengumuman";
		$this->view->hasil = $this->view->pengumumanUpdate;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}

	


}
?>