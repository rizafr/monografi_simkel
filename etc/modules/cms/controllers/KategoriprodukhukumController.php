<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_kategoriprodhukum.php";
require_once 'share/Portalconf.php'; 

class Cms_kategoriprodukhukumController extends Zend_Controller_Action {

		
    public function init() {
		//$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->kategoriprodhukum_serv = Cms_kategoriprodhukum_Service::getInstance();
		$this->view->idkategoriprodhukum= $this->idkategoriprodhukum;
		$this->view->jdlkategoriprodhukum= $this->jdlkategoriprodhukum;
		$this->view->detilkategoriprodhukum= $this->detilkategoriprodhukum;
		$this->view->status= $this->status;
		
		//sesion dari login
		//$ssologin = new Zend_Session_Namespace('ssologin');
		//$this->view->userid=$ssologin->userid;
		//$this->view->c_jabatan=$ssologin->c_jabatan;	
    }
	
    public function indexAction() {
    }
public function kategoriprodukhukumjsAction() 
{
	header('content-type : text/javascript');
	$this->render('kategoriprodukhukumjs');
}	
	

public function listkategoriprodukhukumAction() {    
    
	
	$key=$_GET['key'];
	$status=$_GET['status'];
	$par=$_GET['par'];
	
	if ($par=='cari'){
		if ($key){ if ($status=='J') {$key=strtoupper($key); $cari=" and upper(n_judul) like '%$key%'";}}
		else {if ($status=='D') {$cari=" and c_status=1";} if ($status=='T') {$cari=" and c_status=0";} }
		$this->view->key=$_GET['key'];
		$this->view->status=$_GET['status'];
	}
			
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 2;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalkategoriprodhukumList = $this->kategoriprodhukum_serv->getkategoriprodhukumList($cari, 0, 0 ,$orderBy);		
		$this->view->kategoriprodhukumList = $this->kategoriprodhukum_serv->getkategoriprodhukumList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
public function kategoriprodukhukumAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$c_kategori=$_GET['c_kategori'];
		$this->listDataByKey($c_kategori);
	}	
}
public function maintaindataAction() {

	$userlogin=$this->view->userid;
	$MaintainData = array("c_kategori"=>$_POST['c_kategori'],
							"n_judul"=>$_POST['n_judul'],
							"c_status"=>$_POST['c_status'],
							"i_entry"=>$userlogin,
							"d_entry"=>date("Y-m-d H:i:s"));				
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->kategoriprodhukum_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$this->listkategoriprodukhukumAction();
		$this->render('listkategoriprodukhukum');			
	}		
	else
	{
		$hasil = $this->kategoriprodhukum_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$c_kategori=$_POST['c_kategori'];
		$this->listDataByKey($c_kategori);
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$this->render('kategoriprodukhukum');	
	}
						
}

public function hapusdataAction() {
		$userlogin=$this->view->userid;
		$MaintainData = array("c_kategori"=>$_GET['c_kategori'],"i_entry"=>$userlogin);
		$hasil = $this->kategoriprodhukum_serv->maintainData($MaintainData,'delete');		
		$par="Hapus";
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$this->listkategoriprodukhukumAction();
		$this->render('listkategoriprodukhukum');
}	  
 
public function listDataByKey($c_kategori) {  
	$currentPage = 1;
	$numToDisplay = 10;
	$cari=" and c_kategori='$c_kategori'";
	$kategoriprodhukum=$this->kategoriprodhukum_serv->getkategoriprodhukumList($cari,$currentPage, $numToDisplay,$orderby) ;	
	$this->view->c_kategori= $kategoriprodhukum[0]['c_kategori'];
	$this->view->n_judul= $kategoriprodhukum[0]['n_judul'];
	$this->view->c_status= $kategoriprodhukum[0]['c_status'];

}	
	

}
?>