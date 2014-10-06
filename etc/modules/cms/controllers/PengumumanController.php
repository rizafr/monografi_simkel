<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_pengumuman_Service.php";
require_once 'share/Portalconf.php'; 

class Cmsmodule_pengumumanController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->pengumuman_serv = Cms_pengumuman_Service::getInstance();
		$this->view->idpengumuman= $this->idpengumuman;
		$this->view->jdlpengumuman= $this->jdlpengumuman;
		$this->view->tglpengumuman= $this->tglpengumuman;
		$this->view->detilpengumuman= $this->detilpengumuman;
		$this->view->status= $this->status;
		
		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_jabatan=$ssologin->c_jabatan;
    }
	
    public function indexAction() {
    }
public function pengumumanjsAction() 
{
	header('content-type : text/javascript');
	$this->render('pengumumanjs');
}	
	

public function listpengumumanAction() {    
    
	$status=$_GET['status'];
	$key=strtoupper($_GET['key']);
	if ($status!=''){
		if ($key!='') {
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
	else $orderBy=" order by d_pengumuman desc";
	$this->view->orderBy=$_GET['orderBy'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpengumumanList = $this->pengumuman_serv->getpengumumanList($cari, 0, 0 ,$orderBy);		
		$this->view->pengumumanList = $this->pengumuman_serv->getpengumumanList($cari, $currentPage, $numToDisplay,$orderBy );	 
    }
public function pengumumanAction() {
	$par=$_GET['par'];
	if ($par=='insert'){
		$this->view->par="insert";
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$this->view->maxid=$this->pengumuman_serv->getMaxId();
	}
	else{
		$this->view->par="update";
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$idpengumuman=$_GET['idpengumuman'];
		if (!$idpengumuman){$idpengumuman=$this->view->idpengumuman;}
		$this->listDataByKey($idpengumuman);
	}	
}
public function listDataByKey($idpengumuman) {  
	$pengumuman=$this->pengumuman_serv->findpengumumanByKey($idpengumuman );
	$this->view->idpengumuman= $pengumuman[0]['c_pengumuman'];
	$this->view->jdlpengumuman= $pengumuman[0]['n_judul'];
	$this->view->detilpengumuman= $pengumuman[0]['n_detil'];
	$this->view->tglpengumuman= $pengumuman[0]['d_pengumuman'];	
	$this->view->status= $pengumuman[0]['c_status'];	
	$this->view->ientri=$pengumuman[0]['i_entri'];
	$this->view->dentri=$pengumuman[0]['d_entri'];

}	
public function pengumumandetilAction() {  
	$idpengumuman=$_GET['idpengumuman'];
	$pengumuman=$this->pengumuman_serv->getpengumumanDtl($idpengumuman );
	$this->view->idpengumuman= $pengumuman[0]['c_pengumuman'];
	$this->view->jdlpengumuman= $pengumuman[0]['n_judul'];
	$this->view->detilpengumuman= $pengumuman[0]['n_detil'];
	$this->view->tglpengumuman= $pengumuman[0]['d_pengumuman'];	
}

public function hapusdataAction() {
 	$idpengumuman=$_GET['idpengumuman'];
	$userlogin=$this->view->userid;
	$hasil = $this->pengumuman_serv->maintainHapusData($idpengumuman,$userlogin);

	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
		{$currentPage = 1;}
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totalpengumumanList = $this->pengumuman_serv->getpengumumanList($cari, 0, 0 ,$orderBy);		
		$this->view->pengumumanList = $this->pengumuman_serv->getpengumumanList($cari, $currentPage, $numToDisplay,$orderBy );
	
	$pesan="Hapus data ".$hasil;
	$this->view->pesan = $pesan;
	$this->view->pesancek = $hasil;		
	$this->render('listpengumuman');
}

public function maintaindataAction() {

$h=$_POST['jam'];
$i=$_POST['mnt'];
$date=$_POST['date'];
$datex=reformatDate($date);
$dates=$datex." ".$h.":".$i ;
$userlogin="admin";
	$MaintainData = array("c_pengumuman"=>$_POST['idpengumuman'],
							"n_judul"=>$_POST['title'],
							"n_detil"=>stripslashes($_POST['content']),
							"d_pengumuman"=>$dates,
							"c_status"=>$_POST['status'],
							"i_entri"=>$userlogin,
							"d_entri"=>date("Y-m-d H:i:s"));
echo "data=".$MaintainData;							

if ($_POST['title']){	
//echo "action=".$_POST['action'];					
	if ($_POST['action']=='insert')
	
	{
		$hasil = $this->pengumuman_serv->maintainData($MaintainData,'insert');		
		$this->view->pars="Simpan";
		$this->view->jdl="Menambah ";
		$par="Menambah";
	}		
	else
	{
		$hasil = $this->pengumuman_serv->maintainData($MaintainData,'update');
		$this->view->pars="Ubah";
		$this->view->jdl="Merubah ";
		$par="Merubah";
		$this->listDataByKey($_POST['idpengumuman']) ;
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
	$this->render('pengumuman');							
   }
	  
 

	
}
?>