<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_kategoriprodhukum.php";
require_once "service/cms/Cms_produkhukum.php";

class Portalmodule_produkhukumController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->produkhukum_serv = Cms_produkhukum_Service::getInstance();
		$this->view->idprodukhukum= $this->idprodukhukum;
		$this->view->jdlprodukhukum= $this->jdlprodukhukum;
		$this->view->tglprodukhukum= $this->tglprodukhukum;
		$this->view->detilprodukhukum= $this->detilprodukhukum;
		$this->view->kategoriprodukhukum= $this->kategoriprodukhukum;
		$this->kategoriprodhukum_serv = Cms_kategoriprodhukum_Service::getInstance();
		$this->view->njudul= $this->njudul;
		$this->view->basePath = $registry->get('basepath'); 
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function produkhukumjsAction() 
{
	header('content-type : text/javascript');
	$this->render('produkhukumjs');
}	
public function indeksprodukhukumbycategoryAction() {    

	$idkategori=$_GET['idkategori'];
	$cari = " and c_status='1' and c_kategori='$idkategori'";
	$orderBy =" order by d_tahun_produkhukum desc"; 
	$currentPage=$_GET['currentPage'];

	$thn=$_GET['thn'];
	$no=$_GET['no'];
	$key=strtoupper($_GET['key']);
	if ($key!='') {
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%') and to_char(d_tahun_produkhukum,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	} else { 
	   if ($thn!='' && $bln!='' && $tgl!='') $cari .=" and to_char(d_tahun_produkhukum,'YYYY-MM-DD')='$tanggal' ";
	   else  $cari .=" ";
	}	
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalprodukhukumList = $this->produkhukum_serv->getkategoriprodhukumList($cari, 0, 0 ,$orderBy);		
		$this->view->produkhukumList = $this->produkhukum_serv->getprodukhukumList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }	
public function indeksprodukhukumAction() {    

	$idkategori=$_GET['idkategori'];
	if ($idkategori) $cari = " and c_status='1' and c_kategori='$idkategori'";
	else $cari = " and c_status='1'";
	$orderBy =" order by d_tahun_produkhukum desc"; 
	$currentPage=$_GET['currentPage'];

	$pil=$_GET['pil'];
	$key=strtoupper($_GET['key']);
	if ($pil!='') {
	   if ($pil=='thn')  $cari .=" and  d_tahun_produkhukum='$key' ";
	   else if  ($pil=='no') $cari .=" and  upper(i_nomor_produkhukum) like '%$key%'";
	   else if  ($pil=='hal') $cari .=" and  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
	   
	}
	else  $cari .=" ";	
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$carix="and c_kategori='$idkategori'";
		//$this->view->nkategori = $this->produkhukum_serv->getkategoriprodhukumList($carix, 0, 0 ,$orderBy);
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalprodukhukumList = $this->produkhukum_serv->getprodukhukumPubList($cari, 0, 0 ,$orderBy);		
		$this->view->produkhukumList = $this->produkhukum_serv->getprodukhukumPubList($cari, $currentPage, $numToDisplay,$orderBy );	 
		$this->view->kategoriprodhukumList = $this->kategoriprodhukum_serv->getkategoriprodukhukumPubList($carix);	 
    }	
public function produkhukumdetilAction() {  
	$id=$_GET['id'];
	$currentPage = 1;
	$numToDisplay = 10;
	$cari=" and c_produkhukum='$id' ";
	$produkhukum=$this->produkhukum_serv->getprodukhukumPubList($cari,$currentPage, $numToDisplay,$orderby) ;		
	$this->view->c_produkhukum= $produkhukum[0]['c_produkhukum'];
	$this->view->c_kategori= $produkhukum[0]['c_kategori'];
	$this->view->n_judul= $produkhukum[0]['n_judul'];
	$this->view->c_status= $produkhukum[0]['c_status'];
	$this->view->n_detil= $produkhukum[0]['n_detil'];
	$this->view->n_file= $produkhukum[0]['n_file'];
	$this->view->i_nomor_produkhukum= $produkhukum[0]['i_nomor_produkhukum'];
	$this->view->d_tahun_produkhukum= $produkhukum[0]['d_tahun_produkhukum'];
}

public function listDataByKey($c_produkhukum) {  
	$currentPage = 1;
	$numToDisplay = 10;
	$cari=" and c_produkhukum='$c_produkhukum' ";
	$produkhukum=$this->produkhukum_serv->getprodukhukumList($cari,$currentPage, $numToDisplay,$orderby) ;		
	$this->view->c_produkhukum= $produkhukum[0]['c_produkhukum'];
	$this->view->c_kategori= $produkhukum[0]['c_kategori'];
	$this->view->n_judul= $produkhukum[0]['n_judul'];
	$this->view->c_status= $produkhukum[0]['c_status'];
	$this->view->n_detil= $produkhukum[0]['n_detil'];
	$this->view->n_file= $produkhukum[0]['n_file'];
	$this->view->i_nomor_produkhukum= $produkhukum[0]['i_nomor_produkhukum'];
	$this->view->d_tahun_produkhukum= $produkhukum[0]['d_tahun_produkhukum'];

}	

	
}
?>