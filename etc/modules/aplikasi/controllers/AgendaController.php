<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/aplikasi/Aplikasi_Agenda_Service.php";
require_once "service/aplikasi/Aplikasi_Referensi_Service.php";
require_once "share/format_date.php";

class Aplikasi_AgendaController extends Zend_Controller_Action {
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
	   // $ssogroup = new Zend_Session_Namespace('ssogroup');
	   //echo "TEST ".$ssogroup->n_user_grp." ".$ssogroup->i_user." ".$ssogroup->i_peg_nip;
	   $this->user  = 'cdr';
	   // $this->username  = 'Yuliah';
	   //$this->nip  = strtoupper($this->id['nip']);
	   // $this->usernip  = '060046350';
	   // $this->kdorg = 'SK1201';
	 // $this->modul    = '5';
	 // $this->category = 'A';
		
		$this->agenda_serv = Aplikasi_Agenda_Service::getInstance();
		$this->ref_serv = Aplikasi_Referensi_Service::getInstance();
		
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		
    }
	
    public function indexAction() {
	   
    }
	
	public function agendajsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('agendajs');
    }
	
	//test OPen report
	//----------------------
	public function agendalistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari'];
		$katakunciCari 	= $_REQUEST['katakunciCari'];
		$sortBy			= 'i_agenda';
		$sort			= 'desc';
		
		$dataMasukan = array(
							"kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		//var_dump($dataMasukan);
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totagendaList = $this->agenda_serv->cariAgendaList($dataMasukan,0,0,0);
		
		$this->view->agendaList = $this->agenda_serv->cariAgendaList($dataMasukan,$currentPage, $numToDisplay,$this->view->totagendaList);
		
	}
	
	public function daftarpegawaiAction()
	{
		$this->view->par = $_REQUEST['par'];
		
		$kategoriCari = $_REQUEST['kategoriCari'];
		$kataKunci = $_REQUEST['kataKunci'];
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							 "kataKunci" => $kataKunci);
		$this->view->pejabatList = $this->ref_serv->pencarianPegawai($dataMasukan);	
	}
	
	public function agendaAction()
	{
		$format_date = new format_date();
		//$iAgenda 	= $_POST['iAgenda'];
		$eAgendaPesan 	= $_POST['eAgendaPesan'];
		$dAgenda 		= $_POST['dAgenda'];
		//$dAgenda = null;
		if($dAgenda){ $dAgenda = $format_date->convertTglHumanToMachine($dAgenda);}
		else {$dAgenda = null;}
		
		
		
		$iEntry 		= $this->user;
		
		$dataMasukan = array("eAgendaPesan" => $eAgendaPesan,
							"dAgenda" => $dAgenda,
							"iEntry" => $iEntry);
		
		
		
		$hasil = $this->agenda_serv->agendaInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Agenda";
		$this->view->hasil = $hasil;
		//echo "hasil = $hasil";
		$this->agendalistAction();
		$this->render('agendalist');
	}
	
	public function agendaolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iAgenda = $_REQUEST['iAgenda'];
		$this->view->detailAgenda = $this->agenda_serv->detailAgendaById($iAgenda);
	}
	
	
	
	public function agendaupdateAction()
	{
		$format_date = new format_date();
		$iAgenda 	= $_POST['iAgenda'];
		$iAgendaHidden 	= $_POST['iAgendaHidden'];
		$eAgendaPesan 	= $_POST['eAgendaPesan'];
		$dAgenda 		= $_POST['dAgenda'];
		
		if($dAgenda){ $dAgenda = $format_date->convertTglHumanToMachine($dAgenda);}
		else {$dAgenda = null;}
		
		
		$iEntry 		= $this->user;
		
		$dataMasukan = array("iAgenda" => $iAgenda,
							"iAgendaHidden" => $iAgendaHidden,
							"eAgendaPesan" => $eAgendaPesan,
							"dAgenda" => $dAgenda,
							"iEntry" => $iEntry);
		//var_dump($dataMasukan);
		$hasil = $this->agenda_serv->agendaUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Agenda";
		$this->view->hasil = $hasil;
		$this->agendalistAction();
		$this->render('agendalist');
	}
	
	public function agendahapusAction()
	{
		$iAgenda 		= $_REQUEST['iAgenda'];
		
		$dataMasukan = array("i_agenda" => $iAgenda);
		
		$hasil = $this->agenda_serv->agendaHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Agenda";
		$this->view->hasil = $hasil;
		
		$this->agendalistAction();
		$this->render('agendalist');
	}
	
	public function detailagendaAction()
	{
		$idAgenda = $_REQUEST['idAgenda'];
		
		$this->view->detailAgenda = $this->agenda_serv->detailAgendaById($idAgenda);
	}
}
?>