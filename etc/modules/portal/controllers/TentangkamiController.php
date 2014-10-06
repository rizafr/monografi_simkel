<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_tentangkami_Service.php";

class Portalmodule_tentangkamiController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->tentangkami_serv = Cms_tentangkami_Service::getInstance();
		$this->view->idtentangkami= $this->idtentangkami;
		$this->view->jdltentangkami= $this->jdltentangkami;
		$this->view->tgltentangkami= $this->tgltentangkami;
		$this->view->detiltentangkami= $this->detiltentangkami;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }

public function tentangkamidetilAction() {  
	$id=$_GET['id'];
	$tentangkami=$this->tentangkami_serv->findtentangkamiByKey($id);
	$this->view->idtentangkami= $tentangkami[0]['c_tentangkami'];
	$this->view->jdltentangkami= $tentangkami[0]['n_judul'];
	$this->view->detiltentangkami= $tentangkami[0]['n_detil'];
	$this->view->tgltentangkami= $tentangkami[0]['d_tentangkami'];	
}


	
}
?>