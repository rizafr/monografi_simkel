<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/portal/Portal_Shoutbox_Service.php";
require_once 'share/Portalconf.php'; 

class Portalmodule_shoutboxController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		
		$this->shoutbox_serv = Portal_shoutbox_Service::getInstance();
		$this->view->id= $this->id;
		$this->view->n_userid= $this->n_userid;
		$this->view->n_name= $this->n_name;
		$this->view->n_message= $this->n_message;
		$this->view->d_entri= $this->d_entri;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function shoutboxjsAction() 
{
	header('content-type : text/javascript');
//	$this->render('shoutboxjs');
}	
public function showdataAction() {
	//echo "user=".$_POST['userid'];
		$MaintainData = array(
							"n_userid"=>$_GET['userid'],
							"n_name"=>$_GET['name'],
							"n_message"=>$_GET['message'],
							"d_entri"=>date("Y-m-d H:i:s"));
		$hasil = $this->shoutbox_serv->maintainData($MaintainData);
		$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	
		//$this->render('showdata');
		}	

public function listshoutboxAction() {    
    
	$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	 
    }

public function hapusdataAction() {
 	$id=$_GET['id'];
	$hasil = $this->shoutbox_serv->maintainHapusData($id);

	
	$this->view->shoutboxList = $this->shoutbox_serv->getshoutboxList();	
	
	$pesan="Hapus data ".$hasil;
	$this->render('listshoutbox');
}

public function maintaindataAction() {

//$userlogin="admin";
//echo $_POST['userid'];
	$MaintainData = array("id"=>$_POST['id'],
							"n_userid"=>$_POST['userid'],
							"n_nama"=>$_POST['name'],
							"n_message"=>$_POST['message'],
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idshoutbox'];							

if ($_POST['userid']){	
	$hasil = $this->shoutbox_serv->maintainData($MaintainData,'insert');		
}
else{ $hasil="gagal";}	

/// simpan file

	$this->render('main');							
   }
	  
 

	
}
?>