<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_tentangkami_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_tentangkamiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->tentangkami_serv = Cms_tentangkami_Service::getInstance();
		$this->view->idtentangkami= $this->idtentangkami;
		$this->view->jdltentangkami= $this->jdltentangkami;
		$this->view->detiltentangkami= $this->detiltentangkami;
		$this->view->status= $this->status;
		
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_jabatan=$ssologin->c_jabatan;
    }
	
    public function indexAction() {
    }
public function tentangkamijsAction() 
{
	header('content-type : text/javascript');
	$this->render('tentangkamijs');
}	
	

public function listtentangkamiAction() {    
		$this->view->tentangkamiList = $this->tentangkami_serv->gettentangkamiList();	 
    }
public function tentangkamiAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$this->view->maxid=$this->tentangkami_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idtentangkami=$_GET['idtentangkami'];
		if (!$idtentangkami){$idtentangkami=$this->view->idtentangkami;}
		$this->listDataByKey($idtentangkami);
	}	
}
public function listDataByKey($idtentangkami) {  
	$tentangkami=$this->tentangkami_serv->findtentangkamiByKey($idtentangkami );
	$this->view->idtentangkami= $tentangkami[0]['c_tentangkami'];
	$this->view->jdltentangkami= $tentangkami[0]['n_judul'];
	$this->view->detiltentangkami= $tentangkami[0]['n_detil'];
	$this->view->status= $tentangkami[0]['c_status'];	
	$this->view->ientri=$tentangkami[0]['i_entri'];
	$this->view->dentri=$tentangkami[0]['d_entri'];

}	
public function tentangkamidetilAction() {  
	$idtentangkami=$_GET['idtentangkami'];
	$tentangkami=$this->tentangkami_serv->gettentangkamiDtl($idtentangkami );
	$this->view->idtentangkami= $tentangkami[0]['c_tentangkami'];
	$this->view->jdltentangkami= $tentangkami[0]['n_judul'];
	$this->view->detiltentangkami= $tentangkami[0]['n_detil'];
}

public function hapusdataAction() {
 	$idtentangkami=$_GET['idtentangkami'];
	$userlogin=$this->view->userid;
	$hasil = $this->tentangkami_serv->maintainHapusData($idtentangkami,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totaltentangkamiList = $this->tentangkami_serv->gettentangkamiList($cari, 0, 0 ,$orderBy);		
		$this->view->tentangkamiList = $this->tentangkami_serv->gettentangkamiList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listtentangkami');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin=$this->view->userid;
	$MaintainData = array("c_tentangkami"=>$_POST['idtentangkami'],
							"n_judul"=>$_POST['title'],
							"n_detil"=>stripslashes($_POST['content']),
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idtentangkami'];							

if ($_POST['idtentangkami']){	
echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->tentangkami_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->tentangkami_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['idtentangkami']) ;
	}
}
else{ $hasil="gagal";}	



	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('tentangkami');							
   }
	  
 

	
}
?>