<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_kontakkami_Service.php";

class Portalmodule_KontakkamiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->kontakkami_serv = Cms_kontakkami_Service::getInstance();
		$this->view->idkontakkami= $this->idkontakkami;
		$this->view->jdlkontakkami= $this->jdlkontakkami;
		$this->view->tglkontakkami= $this->tglkontakkami;
		$this->view->detilkontakkami= $this->detilkontakkami;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }

public function kontakkamidetilAction() {  
	$id=$_GET['id'];
	$kontakkami=$this->kontakkami_serv->findkontakkamiByKey($id);
	$this->view->idkontakkami= $kontakkami[0]['c_kontakkami'];
	$this->view->jdlkontakkami= $kontakkami[0]['n_judul'];
	$this->view->detilkontakkami= $kontakkami[0]['n_detil'];
	$this->view->tglkontakkami= $kontakkami[0]['d_kontakkami'];	
}


	
}
?>