<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_produkhukum.php";
require_once 'share/Portalconf.php'; 

class Cms_produkhukumController extends Zend_Controller_Action {

		
    public function init() {
		//$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->produkhukum_serv = Cms_produkhukum_Service::getInstance();
		$this->view->status= $this->status;
        $this->view->basePath = $registry->get('basepath');
        $this->view->baseData = $registry->get('baseData');        
       // $this->view->leftMenu = $registry->get('leftMenu');
        
        //$this->dataPerPage = $registry->get('dataPerPage');
        
        //$this->view->photoPath = $registry->get('photoPath');
         
        
  		//sesion dari login
		//$ssologin = new Zend_Session_Namespace('ssologin');
		//$this->view->userid=$ssologin->userid;
		//$this->view->c_jabatan=$ssologin->c_jabatan;

    }		
		
	
    public function indexAction() {
    }
public function produkhukumjsAction() 
{
	header('content-type : text/javascript');
	$this->render('produkhukumjs');
}	
	

public function listprodukhukumAction() {	
	$key=$_GET['key'];
	$status=$_GET['status'];
	$par=$_GET['par'];
	
	if ($par=='cari'){
		if ($key){ if ($status=='J') {$key=strtoupper($key); $cari=" and upper(n_judul) like '%$key%'";} if ($status=='I') {$key=strtoupper($key); $cari=" and upper(n_detil) like '%$key%'";}}
		else {if ($status=='D') {$cari=" and c_status=1";} if ($status=='T') {$cari=" and c_status=0";} }
		$this->view->key=$_GET['key'];
		$this->view->status=$_GET['status'];
	}			
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$orderBy=" order by d_tahun_produkhukum desc, n_judul asc ";
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalkategoriprodhukumList = $this->produkhukum_serv->getprodukhukumList($cari, 0, 0 ,$orderBy);		
		$this->view->kategoriprodhukumList = $this->produkhukum_serv->getprodukhukumList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
	
public function produkhukumAction() {
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
		$c_produkhukum=$_GET['c_produkhukum'];
		$this->listDataByKey($c_produkhukum);
	}
	$this->view->KategoriHukumList = $this->produkhukum_serv->getKategoriHukum(); 	
}
public function maintaindataAction() {

$userlogin=$this->view->userid;
//simpan file	
if (!empty($_FILES['n_file'])) {
		if ($_POST['a_file'])
		{
				$namefile=trim($_POST['c_kategori']).date('Ymdhms');
				$FileName_dat = $namefile;				
				$fileName = $_FILES['n_file']['name'];
				$extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
				$FileName_pdf = $FileName_dat.'.'.$extention;
				$destDir = "$FileName_pdf";	
		}
}
else{$destDir=$_POST['a_file'];}
//----------------------------------------------------------------------------		
	$MaintainData = array("c_produkhukum"=>$_POST['c_produkhukum'],
							"c_kategori"=>$_POST['c_kategori'],
							"n_judul"=>$_POST['n_judul'],
							"n_detil"=>$_POST['n_detil'],
							"c_status"=>$_POST['c_status'],
							"d_tahun_produkhukum"=>$_POST['d_tahun_produkhukum']*1,
							"i_nomor_produkhukum"=>$_POST['i_nomor_produkhukum'],							
							"n_file"=>$destDir,							
							"i_entry"=>$userlogin,
							"d_entry"=>date("Y-m-d H:i:s"));				
	if ($_POST['action']=='insert')	
	{
		$hasil = $this->produkhukum_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$this->listprodukhukumAction();
		$namefile=trim($_POST['c_kategori']).date('Ymdhms');	
	}		
	else
	{
		$hasil = $this->produkhukum_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$c_produkhukum=$_POST['c_produkhukum'];
		$this->listDataByKey($c_produkhukum);
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$namefile=trim($_POST['c_kategori']).date('Ymdhms')	;		
	}
if ($hasil=='sukses')
{			
			$FileName_pdf;
			$fileNamex = $_FILES['n_file']['name'];
			$extentionx = strtolower(substr($fileNamex, strrpos($fileNamex, '.') + 1));				
				
		    if (!empty($_FILES['n_file'])) 
				{$FileName_pdf = $FileName_dat.'.'.$extentionx;}
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.'.$extentionx;
					
				if (!empty($_FILES['n_file'])) 	   {

				   $fileName = $_FILES['n_file']['name'];
				   $extention = strtolower(substr($fileName, strrpos($fileName, '.') + 1));				
						$destDir = "../public/data/cms/produkhukum/$FileName_pdf";
						if (move_uploaded_file($_FILES['n_file']['tmp_name'], $destDir)) { 
							$lampiran ="file";
						}
				}

}
	$this->view->KategoriHukumList = $this->produkhukum_serv->getKategoriHukum(); 
	
	if ($_POST['action']=='insert')	
	{$this->render('listprodukhukum');}		
	else
	{$this->render('produkhukum');}
						
}

public function hapusdataAction() {
	$userlogin=$this->view->userid;
		$MaintainData = array("c_produkhukum"=>$_GET['c_produkhukum'],"i_entry"=>$userlogin);
		$hasil = $this->produkhukum_serv->maintainData($MaintainData,'delete');		
		$par="Hapus";
		$pesan=$par." data ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$this->listprodukhukumAction();
		$this->render('listprodukhukum');
}	  
 
public function listDataByKey($c_produkhukum) {  
	$currentPage = 1;
	$numToDisplay = 10;
	$cari=" and c_produkhukum='$c_produkhukum' ";
	$produkhukum=$this->produkhukum_serv->getprodukhukumList($cari,$currentPage, $numToDisplay,$orderby) ;		
	$this->view->c_produkhukum= trim($produkhukum[0]['c_produkhukum']);
	$this->view->c_kategori= trim($produkhukum[0]['c_kategori']);
	$this->view->n_judul= $produkhukum[0]['n_judul'];
	$this->view->c_status= $produkhukum[0]['c_status'];
	$this->view->n_detil= $produkhukum[0]['n_detil'];
	$this->view->n_file= $produkhukum[0]['n_file'];
	$this->view->i_nomor_produkhukum= $produkhukum[0]['i_nomor_produkhukum'];
	$this->view->d_tahun_produkhukum= $produkhukum[0]['d_tahun_produkhukum'];

}	
	

}
?>