<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/portal/Portal_Berita_Service.php";

class Portal_BeritaController extends Zend_Controller_Action {

		
    public function init() {
		//$this->_helper->layout->setLayout('target-column');
		//$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->berita_serv = Portal_Berita_Service::getInstance();
		$this->view->idberita= $this->idberita;
		$this->view->jdlberita= $this->jdlberita;
		$this->view->tglberita= $this->tglberita;
		$this->view->detilberita= $this->detilberita;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function beritajsAction() 
{
	header('content-type : text/javascript');
	$this->render('beritajs');
}	

public function indeksberitaAction() {    

	$cari = " where c_status='1'";
	$orderBy =" order by d_berita desc"; 
	$currentPage=$_GET['currentPage'];

	$tgl=$_GET['tgl'];
	$bln=$_GET['bln'];
	$thn=$_GET['thn'];
	$tanggal = $thn."-".$bln."-".$tgl;
	$key=strtoupper($_GET['key']);
	$cari=" where c_status='1' ";
	if ($key!='') {
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%') and to_char(d_berita,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	} else { 
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and to_char(d_berita,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" ";
	}	
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalBeritaList = $this->berita_serv->getBeritaList($cari, 0, 0 ,$orderBy);		
		$this->view->BeritaList = $this->berita_serv->getBeritaList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }	
public function beritadetilAction() {  
	$id=$_GET['id'];
	$berita=$this->berita_serv->findBeritaByKey($id);
	$this->view->idberita= $berita[0]['c_berita'];
	$this->view->jdlberita= $berita[0]['n_judul'];
	$this->view->detilberita= $berita[0]['n_detil'];
	$this->view->tglberita= $berita[0]['d_berita'];	
}


	
}
?>