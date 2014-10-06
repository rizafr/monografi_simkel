<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pegawai/Pegawai_Pegawai_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "service/adm/Upt_Service.php";
require_once "service/adm/Divre_Service.php";
require_once "service/adm/Jabatan_Service.php";

class Pegawai_PegawaiController extends Zend_Controller_Action {
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
	   
		$this->pegawai_serv = Pegawai_Pegawai_Service::getInstance();
		$this->upt_serv = Upt_Service::getInstance();
		$this->divre_serv = Divre_Service::getInstance();
		$this->jabatan_serv = Jabatan_Service::getInstance();
		
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopegawai = new Zend_Session_Namespace('ssopegawai');
	    $this->iduser =$ssopegawai->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
    }
	
	public function pegawaijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('pegawaijs');
    }
	
	//test OPen report
	//----------------------
	public function pegawailistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$c_uptU =$ssogroup->c_upt;
		$c_group =$ssogroup->c_group;
		$this->view->c_group =$ssogroup->c_group;
		
		if ( $_REQUEST['param1']){$this->view->divre= $_REQUEST['param1'];}
		else { $this->view->divre= $_REQUEST['divre'];
		}
		if ( $_REQUEST['param2']){$this->view->upt= $_REQUEST['param2'];}
		else { $this->view->upt= $_REQUEST['upt'];
		}
		if ( $_REQUEST['param3']){$this->view->jabatan= $_REQUEST['param3'];}
		else {$this->view->jabatan 		= $_REQUEST['jabatan'];
		}

		
		$this->view->divreList = $this->divre_serv->getDivreListAll();
		$this->view->uptList	= $this->upt_serv->getUptListByDivre($this->view->divre);
		$this->view->jabatanList	= $this->jabatan_serv->getJabatanListAll();

		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= $_POST['kategoriCari'];
		$sortBy			= 'n_nama';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"divre" => $this->view->divre,
							"upt" => $this->view->upt,
							"jabatan" => $this->view->jabatan,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 40;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;

		$this->view->totpegawaiList = $this->pegawai_serv->caripegawaiList($dataMasukan,0,0,0);
		$this->view->pegawaiList = $this->pegawai_serv->caripegawaiList($dataMasukan,$currentPage, $numToDisplay,$this->view->totpegawaiList);		
	}
	
	public function pegawaiolahdataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$c_uptU =$ssogroup->c_upt;
		$c_group =$ssogroup->c_group;
		$this->view->c_group =$ssogroup->c_group;
		
		
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$this->view->id = $_REQUEST['id'];

		$this->view->divre 			= $_REQUEST['divre'];
		$this->view->upt			= $_REQUEST['upt'];
		$this->view->jabatan 		= $_REQUEST['jabatan'];

		
		$this->view->detailpegawai	= $this->pegawai_serv->detailpegawaiById($this->view->id);
		
		if($this->view->jenisForm == 'insert'){
		$this->view->divre 			= $this->view->divre;
		}

		if($this->view->jenisForm == 'update'){
		$this->view->divre 			= $this->view->detailpegawai['c_divre'];
		}
		
		$detailDivre				= $this->divre_serv->getNamaDivre($this->view->divre);
		$this->view->n_divre 		= $detailDivre['n_divre'];
		
		if($this->view->jenisForm == 'insert'){
		$this->view->uptList		= $this->upt_serv->getUptListByDivre($this->view->divre);
		}

		if($this->view->jenisForm == 'update'){
		$this->view->uptList		= $this->upt_serv->getUptListAll();
		}
	//	$this->view->uptList		= $this->upt_serv->getUptListByDivre($this->view->divre);
		$this->view->jabatanList	= $this->jabatan_serv->getJabatanListAll();

	}

	
	public function pegawaiAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id							= $_POST['id'];       
		$this->view->divre 			= $_REQUEST['divre'];
		$this->view->upt			= $_REQUEST['upt'];
		$this->view->jabatan 		= $_REQUEST['jabatan'];
		
		$c_upt						= $_POST['c_upt'];    
		$n_nama						= $_POST['n_nama'];    
		
		$namaDivre					= $this->upt_serv->detailUptById($c_upt);
		
		$n_nip						= $_POST['n_nip'];      
		$n_jabatan					= $_POST['n_jabatan'];  
		
		$k_npwp1						= $_POST['k_npwp1']; 
		$k_npwp2						= $_POST['k_npwp2']; 
		$k_npwp3						= $_POST['k_npwp3']; 
		$k_npwp4						= $_POST['k_npwp4']; 
		$k_npwp5						= $_POST['k_npwp5']; 
		$k_npwp6						= $_POST['k_npwp6']; 
		
		$n_npwp						= $k_npwp1.".".$k_npwp2.".".$k_npwp3.".".$k_npwp4."-".$k_npwp5.".".$k_npwp6; 

		
		$cekData = $this->pegawai_serv->cekData(trim($n_nip));
		$dataMasukan = array(
							"n_nama"			=>trim($n_nama),
							"n_jabatan"			=>trim($n_jabatan),
							"n_nip"				=>trim($n_nip),
							"c_upt"				=>trim($namaDivre['nopend']),
							"c_divre"			=>trim($namaDivre['Idwilayah']),
							"n_npwp" 			=> $n_npwp,
							"k_npwp1"			=> $k_npwp1,
							"k_npwp2"			=> $k_npwp2,
							"k_npwp3"			=> $k_npwp3,
							"k_npwp4"			=> $k_npwp4,
							"k_npwp5"			=> $k_npwp5,
							"k_npwp6"			=> $k_npwp6,
							"i_entry"			=> $user_id
							);
		
		if($cekData['id']){
		$this->view->pegawaiInsert = "Pegawai dengan NIPPOS : ".trim($n_nip);		
		}else {
		$this->view->pegawaiInsert = $this->pegawai_serv->pegawaiInsert($dataMasukan);		
		}
		
		$this->Logfile->createLog($this->view->namauser, "Insert data pegawai", $n_pegawai." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "pegawai";
		$this->view->hasil = $this->view->pegawaiInsert;
		
		$this->pegawailistAction();
		$this->render('pegawailist');
	}
	
	public function pegawaiupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id							= $_POST['id'];       
		$this->view->divre 			= $_REQUEST['divre'];
		$this->view->upt			= $_REQUEST['upt'];
		$this->view->jabatan 		= $_REQUEST['jabatan'];
		
		$c_upt						= $_POST['c_upt'];    
		$n_nama						= $_POST['n_nama'];    
		
		$n_nip						= $_POST['n_nip'];      
		$n_jabatan					= $_POST['n_jabatan'];  

		//if($n_npwp == '-' || $n_npwp == ''){$n_npwp ='-';}
		$namaDivre					= $this->upt_serv->detailUptById($c_upt);
		$k_npwp1						= $_POST['k_npwp1']; 
		$k_npwp2						= $_POST['k_npwp2']; 
		$k_npwp3						= $_POST['k_npwp3']; 
		$k_npwp4						= $_POST['k_npwp4']; 
		$k_npwp5						= $_POST['k_npwp5']; 
		$k_npwp6						= $_POST['k_npwp6']; 
		
		$n_npwp					= $k_npwp1.".".$k_npwp2.".".$k_npwp3.".".$k_npwp4."-".$k_npwp5.".".$k_npwp6; 

		$dataMasukan = array("id"				=>$id,
							"n_nama"			=>trim($n_nama),
							"n_jabatan"			=>trim($n_jabatan),
							"n_nip"				=>trim($n_nip),
							"c_upt"				=>trim($namaDivre['nopend']),
							"c_divre"			=>trim($namaDivre['Idwilayah']),
							"n_npwp" 			=> $n_npwp,
							"k_npwp1"			=> $k_npwp1,
							"k_npwp2"			=> $k_npwp2,
							"k_npwp3"			=> $k_npwp3,
							"k_npwp4"			=> $k_npwp4,
							"k_npwp5"			=> $k_npwp5,
							"k_npwp6"			=> $k_npwp6,
							"i_entry"			=> $user_id
							);

		$this->view->pegawaiUpdate = $this->pegawai_serv->pegawaiUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data pegawai", $n_pegawai." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "pegawai";
		$this->view->hasil = $this->view->pegawaiUpdate;
		
		$this->pegawailistAction();
		$this->render('pegawailist');
	}

	public function pegawaihapusAction()
	{
		$this->view->divre 			= $_REQUEST['divre'];
		$this->view->upt			= $_REQUEST['upt'];
		$this->view->jabatan 		= $_REQUEST['jabatan'];
		
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->pegawaiUpdate = $this->pegawai_serv->pegawaiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data pegawai", $n_pegawai." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "pegawai";
		$this->view->hasil = $this->view->pegawaiUpdate;
		
		$this->pegawailistAction();
		$this->render('pegawailist');
	}

	


}
?>