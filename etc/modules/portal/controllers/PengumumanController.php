<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_pengumuman_Service.php";

class Portalmodule_pengumumanController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->pengumuman_serv = Cms_pengumuman_Service::getInstance();
		$this->view->idpengumuman= $this->idpengumuman;
		$this->view->jdlpengumuman= $this->jdlpengumuman;
		$this->view->tglpengumuman= $this->tglpengumuman;
		$this->view->detilpengumuman= $this->detilpengumuman;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function pengumumanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pengumumanjs');
}	    

public function indekspengumumanAction() {    
	$cari = " where c_status='1'";
	$orderBy =" order by d_pengumuman desc"; 
	$currentPage=$_GET['currentPage'];
	
	$tgl=$_GET['tgl'];
	$bln=$_GET['bln'];
	$thn=$_GET['thn'];
	$tanggal = $thn."-".$bln."-".$tgl;
	$key=strtoupper($_GET['key']);
	$cari=" where c_status='1' ";
	if ($key!='') {
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%') and to_char(d_pengumuman,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	} else { 
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and to_char(d_pengumuman,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" ";
	}	
	
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpengumumanList = $this->pengumuman_serv->getpengumumanList($cari, 0, 0 ,$orderBy);		
		$this->view->pengumumanList = $this->pengumuman_serv->getpengumumanList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }	
public function pengumumandetilAction() {  
	$id=$_GET['id'];
	$pengumuman=$this->pengumuman_serv->findpengumumanByKey($id);
	$this->view->idpengumuman= $pengumuman[0]['c_pengumuman'];
	$this->view->jdlpengumuman= $pengumuman[0]['n_judul'];
	$this->view->detilpengumuman= $pengumuman[0]['n_detil'];
	$this->view->tglpengumuman= $pengumuman[0]['d_pengumuman'];	
}


	
}
?>