<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_Shoutbox_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_shoutboxController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		$this->view->photoPath = $registry->get('photoPath');
		 
		$this->shoutbox_serv = Cms_shoutbox_Service::getInstance();
		$this->view->id= $this->id;
		$this->view->jdlshoutbox= $this->jdlshoutbox;
		$this->view->tglshoutbox= $this->tglshoutbox;
		$this->view->userid= $this->userid;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function shoutboxjsAction() 
{
	header('content-type : text/javascript');
	$this->render('shoutboxjs');
}	
	

public function listshoutboxAction() {    
    
	$cat=$_GET['cat'];
	$key=strtoupper($_GET['key']);
	if ($cat!=''){
		if ($key!='') {
		  $cari = " where upper($cat) like '%$key%'";
		} else {
		  $cari = "";
		}
	}
	else if ($key='') $cari ="";
	else $cari ="";
	//echo "c=".$cari;
	$orderBy=$_GET['orderBy'];
	$order=$_GET['order'];
	if (!$_GET['order']){$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	else{
		if ($_GET['order']=='desc'){	$this->view->orderbya="desc";$this->view->orderbyb="asc";}
		else{$this->view->orderbya="asc";$this->view->orderbyb="desc";}
	}
	if ($_GET['orderBy']) $orderBy=" order by $orderBy $order";
	else $orderBy=" order by d_entri desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalshoutboxList = $this->shoutbox_serv->getshoutboxallList($cari, 0, 0 ,$orderBy);		
		$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxallList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
public function shoutboxAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		//$this->view->maxid=$this->shoutbox_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$id=$_GET['id'];
		if (!$id){$id=$this->view->id;}
		$this->listDataByKey($id);
	}	
}
public function listDataByKey($id) { 
	$shoutbox=$this->shoutbox_serv->findshoutboxByKey($id );
	$this->view->id= $shoutbox[0]['id'];
	$this->view->userid= $shoutbox[0]['n_userid'];
	$this->view->nama= $shoutbox[0]['n_name'];
	$this->view->message= $shoutbox[0]['n_message'];	
	$this->view->dentri= $shoutbox[0]['d_entri'];	

}	


public function hapusdataAction() {
 	$id=$_GET['id'];
	$userlogin='admin';
	$hasil = $this->shoutbox_serv->maintainHapusData($id);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalshoutboxList = $this->shoutbox_serv->getshoutboxallList($cari, 0, 0 ,$orderBy);		
		$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxallList($cari, $currentPage, $numToDisplay,$orderBy );	
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listshoutbox');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin="admin";

	$MaintainData = array("id"=>$_POST['id'],
							"n_userid"=>$_POST['userid'],
							"n_name"=>$_POST['nama'],
							"n_message"=>$_POST['message'],
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['id'];							

if ($_POST['userid']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->shoutbox_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->shoutbox_serv->maintainDataedit($MaintainData);
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['id']) ;
	}
}
else{ $hasil="gagal";}	

/// simpan file



	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('shoutbox');							
   }
	  
 

	
}
?>