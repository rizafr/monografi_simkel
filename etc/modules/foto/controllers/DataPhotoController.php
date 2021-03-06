<?php
require_once 'Zend/Controller/Action.php';
require_once "service/foto/Dataphoto_Service.php";

class Foto_DataPhotoController extends Zend_Controller_Action {
	private $serv_ref;
	private $userlog;
	private $userid;	
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');
		$this->dataphoto_serv = Dataphoto_Service::getInstance();	
	

		// $ssogroup = new Zend_Session_Namespace('ssogroup');
		// $this->userid =$ssogroup->i_user;
		// $userid =$this->userid;
		
		// $ssogroupnip = new Zend_Session_Namespace('ssogroupnip');
		// $this->nip_user =$ssogroupnip->nip_user;
		$ssouserid = new Zend_Session_Namespace('ssouserid');
		$this->nip_user =$ssouserid->user_id;
		$userid =$this->userid;		
    }
	
    public function indexAction() {

    }
	
    public function dataphotoAction() {

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->e_ket =$ssogroup->e_ket;
		$this->view->n_level =$ssogroup->n_level;
		$user_id =$ssogroup->user_id;


		$id=$user_id;
		$n_nama=$_GET['n_nama'];
		$this->view->id = $id;
		$this->view->n_nama = $n_nama;
		$cari=" and id= '$id' ";		
		$this->view->dataphoto = $this->dataphoto_serv->getTmPhotoPegawai($cari);
		
    }

    public function dataphotojsAction() {
	   header('content-type : text/javascript');
	   $this->render('dataphotojs');
    }

	

    public function maintaindataAction() {
		$userid = $this->nip_user;	
		$id = trim($_POST['id']);

		if ($_POST['a_photofile'])
		{
			if ($_POST['a_photofile']!=$_POST['a_photofile2'])
			{
				$namefile=$id;
				$FileName_dat = $namefile;
				$FileName_pdf = $FileName_dat.'.jpg';		
				$destDir = "$FileName_pdf";	
			}
			else
			{
				$destDir =$_POST['a_photofile'];
			}
		}
		
	
		$MaintainData = array("id"=>trim($_POST['id']),
							  "a_photofile"=>$destDir,
							  "i_entry"=>$userid);
		//$hasil = $this->dataphoto_serv->hapusData($_POST['id_npm']);	
		//if ($hasil=='sukses'){	
			$hasil = $this->dataphoto_serv->updateData($MaintainData);
		//}


		if ($hasil=="sukses"){
			$namefile=$id;
			$FileName_pdf;	
		    if (!empty($_FILES['a_photofilex'])) 
			{
				$FileName_pdf = $FileName_dat.'.jpg';
			}
			$FileName_dat = $namefile;
			$FileName_pdf = $FileName_dat.'.jpg';				
					
	       if (!empty($_FILES['a_photofilex'])) 	   {

	           $fileName = $_FILES['a_photofilex']['name'];
			   $extention = substr($fileName, -3, 3);
	     	   if (($extention == "jpg") || ($extention == "JPG")) {
					$destDir = "../etc/data/kp/photo/$FileName_pdf";		
					if (move_uploaded_file($_FILES['a_photofilex']['tmp_name'], $destDir)) { 
						$lampiran ="lampiran";	
					}
				}
			}
		}
		$id=$_POST['id'];
		$n_nama=$_POST['n_nama'];
		$this->view->id = $id;
		$this->view->n_nama = $n_nama;
		$cari=" and id= '$id' ";		
		$this->view->dataphoto = $this->dataphoto_serv->getTmPhotoPegawai($cari);		
				
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		
		$this->_helper->viewRenderer('dataphoto');
		
		
	 }

				
}

?>