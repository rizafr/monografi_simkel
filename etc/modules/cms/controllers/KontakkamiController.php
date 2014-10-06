<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_kontakkami_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_kontakkamiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->kontakkami_serv = Cms_kontakkami_Service::getInstance();
		$this->view->idkontakkami= $this->idkontakkami;
		$this->view->jdlkontakkami= $this->jdlkontakkami;
		$this->view->detilkontakkami= $this->detilkontakkami;
		$this->view->status= $this->status;
		
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_jabatan=$ssologin->c_jabatan;	
    }
	
    public function indexAction() {
    }
public function kontakkamijsAction() 
{
	header('content-type : text/javascript');
	$this->render('kontakkamijs');
}	
	

public function listkontakkamiAction() {    
		$this->view->kontakkamiList = $this->kontakkami_serv->getkontakkamiList();	 
    }
public function kontakkamiAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$this->view->maxid=$this->kontakkami_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idkontakkami=$_GET['idkontakkami'];
		if (!$idkontakkami){$idkontakkami=$this->view->idkontakkami;}
		$this->listDataByKey($idkontakkami);
	}	
}
public function listDataByKey($idkontakkami) {  
	$kontakkami=$this->kontakkami_serv->findkontakkamiByKey($idkontakkami );
	$this->view->idkontakkami= $kontakkami[0]['c_kontakkami'];
	$this->view->jdlkontakkami= $kontakkami[0]['n_judul'];
	$this->view->detilkontakkami= $kontakkami[0]['n_detil'];
	$this->view->status= $kontakkami[0]['c_status'];	
	$this->view->ientri=$kontakkami[0]['i_entri'];
	$this->view->dentri=$kontakkami[0]['d_entri'];

}	
public function kontakkamidetilAction() {  
	$idkontakkami=$_GET['idkontakkami'];
	$kontakkami=$this->kontakkami_serv->getkontakkamiDtl($idkontakkami );
	$this->view->idkontakkami= $kontakkami[0]['c_kontakkami'];
	$this->view->jdlkontakkami= $kontakkami[0]['n_judul'];
	$this->view->detilkontakkami= $kontakkami[0]['n_detil'];
}

public function hapusdataAction() {
 	$idkontakkami=$_GET['idkontakkami'];
	$userlogin=$this->view->userid;
	$hasil = $this->kontakkami_serv->maintainHapusData($idkontakkami,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalkontakkamiList = $this->kontakkami_serv->getkontakkamiList($cari, 0, 0 ,$orderBy);		
		$this->view->kontakkamiList = $this->kontakkami_serv->getkontakkamiList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listkontakkami');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin=$this->view->userid;
	$MaintainData = array("c_kontakkami"=>$_POST['idkontakkami'],
							"n_judul"=>$_POST['title'],
							"n_detil"=>stripslashes($_POST['content']),
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idkontakkami'];							

if ($_POST['idkontakkami']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->kontakkami_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->kontakkami_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['idkontakkami']) ;
	}
}
else{ $hasil="gagal";}	



	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('kontakkami');							
   }
	  
 

	
}
?>