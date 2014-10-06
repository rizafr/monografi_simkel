<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_agenda_Service.php";

class Portalmodule_agendaController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->agenda_serv = Cms_agenda_Service::getInstance();
		$this->view->idagenda= $this->idagenda;
		$this->view->jdlagenda= $this->jdlagenda;
		$this->view->tglagenda= $this->tglagenda;
		$this->view->detilagenda= $this->detilagenda;
		$this->view->tempat= $this->tempat;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function agendajsAction() 
{
	header('content-type : text/javascript');
	$this->render('agendajs');
}    

public function indeksagendaAction() {    
	$cari = " where c_status='1'";
	$orderBy =" order by d_agenda desc"; 
	$currentPage=$_GET['currentPage'];
	$tgl=$_GET['tgl'];
	$bln=$_GET['bln'];
	$thn=$_GET['thn'];
	$tanggal = $thn."-".$bln."-".$tgl;
	$key=strtoupper($_GET['key']);
	$cari=" where c_status='1' ";
	if ($key!='') {
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) or upper(n_tempat) like '%$key%') and to_char(d_agenda,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) or upper(n_tempat) like '%$key%')";
	} else { 
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and to_char(d_agenda,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" ";
	}
	
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalagendaList = $this->agenda_serv->getagendaList($cari, 0, 0 ,$orderBy);		
		$this->view->agendaList = $this->agenda_serv->getagendaList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }	
public function agendadetilAction() {  
	$id=$_GET['id'];
	$agenda=$this->agenda_serv->findagendaByKey($id);
	$this->view->idagenda= $agenda[0]['c_agenda'];
	$this->view->jdlagenda= $agenda[0]['n_judul'];
	$this->view->tempat= $agenda[0]['n_tempat'];
	$this->view->detilagenda= $agenda[0]['n_detil'];
	$this->view->tglagenda= $agenda[0]['d_agenda'];	
}


	
}
?>