<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_agenda_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_agendaController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->agenda_serv = Cms_agenda_Service::getInstance();
		$this->view->idagenda= $this->idagenda;
		$this->view->jdlagenda= $this->jdlagenda;
		$this->view->tglagenda= $this->tglagenda;
		$this->view->detilagenda= $this->detilagenda;
		$this->view->tempat= $this->tempat;
		$this->view->status= $this->status;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function agendajsAction() 
{
	header('content-type : text/javascript');
	$this->render('agendajs');
}	
	

public function listagendaAction() {    
    
	$status=$_GET['status'];
	$key=strtoupper($_GET['key']);
	if ($status!=''){
		if ($s!='') {
		  $cari = " where c_status='$status' and (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
		} else {
		  $cari = " where c_status='$status'";
		}
	}	
	else if ($key!='') $cari = " where  (upper(n_judul) like '%$key%' or upper(n_detil) like '%$key%')";
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
	else $orderBy=" order by d_agenda desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalagendaList = $this->agenda_serv->getagendaList($cari, 0, 0 ,$orderBy);		
		$this->view->agendaList = $this->agenda_serv->getagendaList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
public function agendaAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$this->view->maxid=$this->agenda_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idagenda=$_GET['idagenda'];
		if (!$idagenda){$idagenda=$this->view->idagenda;}
		$this->listDataByKey($idagenda);
	}	
}
public function listDataByKey($idagenda) {  
	$agenda=$this->agenda_serv->findagendaByKey($idagenda );
	$this->view->idagenda= $agenda[0]['c_agenda'];
	$this->view->jdlagenda= $agenda[0]['n_judul'];
	$this->view->detilagenda= $agenda[0]['n_detil'];
	$this->view->tglagenda= $agenda[0]['d_agenda'];	
	$this->view->status= $agenda[0]['c_status'];	
	$this->view->tempat= $agenda[0]['n_tempat'];	
	$this->view->ientri=$agenda[0]['i_entri'];
	$this->view->dentri=$agenda[0]['d_entri'];

}	
public function agendadetilAction() {  
	$idagenda=$_GET['idagenda'];
	$agenda=$this->agenda_serv->getagendaDtl($idagenda );
	$this->view->idagenda= $agenda[0]['c_agenda'];
	$this->view->jdlagenda= $agenda[0]['n_judul'];
	$this->view->detilagenda= $agenda[0]['n_detil'];
	$this->view->tglagenda= $agenda[0]['d_agenda'];	
}

public function hapusdataAction() {
 	$idagenda=$_GET['idagenda'];
	$userlogin='admin';
	$hasil = $this->agenda_serv->maintainHapusData($idagenda,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$orderBy=" order by d_agenda desc";
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalagendaList = $this->agenda_serv->getagendaList($cari, 0, 0 ,$orderBy);		
		$this->view->agendaList = $this->agenda_serv->getagendaList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listagenda');
}

public function maintaindataAction() {

$date=$_POST['date'];
$dates=reformatDate($date);
$userlogin="admin";
//echo "u=".$userlogin;
	$MaintainData = array("c_agenda"=>$_POST['idagenda'],
							"n_judul"=>$_POST['title'],
							"n_tempat"=>$_POST['place'],
							"n_detil"=>stripslashes($_POST['content']),
							"d_agenda"=>$dates,
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
//echo "id=".$_POST['idagenda'];							

if ($_POST['title']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->agenda_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
		$this->listagendaAction();
	}		
	else
	{
		$hasil = $this->agenda_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		//$this->listDataByKey($_POST['idagenda']) ;
		$this->listagendaAction();
	}
}
else{ $hasil="gagal";}	

/// simpan file
		if ($hasil=="sukses"){
			$namefile=trim($_POST['i_peg_nip']);
			$FileName_pdf;
			$fileNamex = $_FILES['a_filex']['name'];
			$extentionx = substr($fileNamex, -3, 3);	
				
		    if (!empty($_FILES['a_filex'])) 
		   {$FileName_pdf = $FileName_dat.'.'.$extentionx;}
			$FileName_dat = $namefile;
			$FileName_pdf = $FileName_dat.'.'.$extentionx;				
					
	       if (!empty($_FILES['a_filex'])) 	   {

	           $fileName = $_FILES['a_filex']['name'];
			   $extention = substr($fileName, -3, 3);
					$destDir = "data/sdm/photo/$FileName_pdf";		
					if (move_uploaded_file($_FILES['a_filex']['tmp_name'], $destDir)) { 
						$lampiran ="file";	
						$this->cropImage($nw, $nh, $destDir, $extention, $destDir);
					}
			}
			}




	$pesan=$par." data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;
	$this->render('listagenda');							
   }
	  
 

	
}
?>