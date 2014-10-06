<?php
require_once 'Zend/Controller/Action.php';

class Util_TampilfotoController extends Zend_Controller_Action {

    public function init() {
 	   $registry = Zend_Registry::getInstance();
	   $this->view->basePath = $registry->get('basepath'); 
	   $this->view->dPath = $registry->get('baseData');
   }
	
    public function indexAction() {
	
    }
	/*
   public function absensijsAction()
   {
	  header('content-type : text/javascript');
	  $this->render('absensijs');
   }
   */
   
      public function viewimagesxAction()
	{
	   $valNip = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = "http://".$_SERVER['SERVER_NAME'].$this->view->basePath."/images/".$valNip;
       $fotoNe = file_get_contents($valFile);
       header('Content-Type: image/jpg');
       header( "Cache-Control: no-cache, must-revalidate" );	   
	   echo $fotoNe;
	}  
     public function viewimagesAction()
	{
	   $valNip = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = "http://".$_SERVER['SERVER_NAME'].$this->view->basePath."/images/".$valNip.".png";
       $fotoNe = file_get_contents($valFile);
       header('Content-Type: image/jpg');
       header( "Cache-Control: no-cache, must-revalidate" );	   
	   echo $fotoNe;
	}
     public function tampilfotoAction()
	{
	   $valNip = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   //$valFile = $this->view->dPath."/sdm/foto/" .$valNip.'.jpg';
	   $valFile = "..\etc\data\kp\photo\\".$valNip.".jpg";
       $fotoNe = file_get_contents($valFile);
       header('Content-Type: image/jpg');
	   echo $fotoNe;
	}
     public function tampilfotofpdfAction()
	{
	   $valNip = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = $this->view->dPath."/auditor/foto/" .$valNip;
	   //$valFile = "..\etc\data\auditor\foto\\".$valNip.".jpg";
       $fotoNe = file_get_contents($valFile);
       header('Content-Type: image/jpg');
	   echo $fotoNe;
	} 	
     public function viewdokumenAction()
	{
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath.$valFile;
       $dokumenNe = file_get_contents($folderE);
	   $ektensi = explode(".",$valFile);
	   $valEks = $ektensi[1];
	   header("Content-Type: application/$valEks");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumendossierAction()
	{
	   $lokDossierFile = $this->view->dPath."/sdm/dokumenPdf/";
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $lokDossierFile.$valFile;
       $dokumenNe = file_get_contents($folderE);
	   $ektensi = explode(".",$valFile);
	   $valEks = $ektensi[1];
	   header("Content-Type: application/$valEks");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumenpdfAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".pdf";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/pdf");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumentxtAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".txt";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/txt");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumenxlsAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".xls";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/xls");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumendocAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".doc";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/doc");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumenpptAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".ppt";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/ppt");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
     public function viewdokumenrtfAction()
	{
	   $valJns = $_REQUEST['j'];
	   $valFile = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $folderE = $this->view->dPath."/sdm/dokumenPdf/" .$valJns."/".$valFile.".rtf";
       $dokumenNe = file_get_contents($folderE);
	   header("Content-Type: application/rtf");
	   header("Content-Disposition: attachment; filename=$folderE");
	   echo $dokumenNe;
	}
	
     public function viewchartAction()
	{
	   $valSwf = $_REQUEST['f'];
       $this->_helper->viewRenderer->setNoRender(true);
	   $valFile = $this->view->dPath."/chart/".$valSwf;
       $fotoNe = file_get_contents($valFile);
	   echo $fotoNe;
	}	

}
?>