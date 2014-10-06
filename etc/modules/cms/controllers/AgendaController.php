<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/cms/Cms_agenda_Service.php";
require_once 'share/Portalconf.php'; 
require_once "service/sdm/Sdm_Pegawai_Service.php";

class Cmsmodule_agendaController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		$this->agenda_serv = Cms_agenda_Service::getInstance();
		$this->pegawai_serv = Sdm_Pegawai_Service::getInstance();
		$this->view->idagenda= $this->idagenda;
		$this->view->jdlagenda= $this->jdlagenda;
		$this->view->tglagenda= $this->tglagenda;
		$this->view->detilagenda= $this->detilagenda;
		$this->view->tempat= $this->tempat;
		$this->view->status= $this->status;

		//sesion dari login
		$ssologin = new Zend_Session_Namespace('ssologin');
		$this->view->userid=$ssologin->userid;
		$this->view->c_jabatan=$ssologin->c_jabatan;		
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
		$this->cmbkpd($ipeg,$npeg,'ins');
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
	$ipeg=$agenda[0]['i_nip'];
	$npeg=$agenda[0]['n_kepada'];
	$this->cmbkpd($ipeg,$npeg,'');
	

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
	$userlogin=$this->view->userid;
	//echo "user=".$userlogin;
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
$userlogin=$this->view->userid;
//echo "u=".$userlogin;

			if (!$_POST['idagenda']){
			$prm    =  array("i_modul" =>'agenda');			  
			$c_agenda=$this->agenda_serv->setNomor($prm);
			}
			else {$c_agenda=$_POST['idagenda'];}
			
	$MaintainData = array("c_agenda"=>$c_agenda,
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
	
$counttable=$_POST['counttable']*1;
if ($counttable!=0){
	$hasil = $this->agenda_serv->HapusDataKepada($c_agenda,$userlogin);
}

	$con=1;	
	for($xi=0;$xi<$counttable;$xi++)
	{	
		$MaintainData = array("c_agenda"=>$c_agenda,
					"i_nip"=>$_POST['i_peg_nip_'.$con],
					"n_kepada"=>$_POST['n_peg_'.$con],
					"i_entri"=>$userlogin,
					"d_entri"=>date("Y-m-d H:i:s"));
		$hasil = $this->agenda_serv->InsertDataKepada($MaintainData);				
		$con++;					
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
	  
 
public function listnamaAction() {
	$this->view->nom=$_GET['nom'];
	$this->view->par=$_GET['par'];
	$currentPage=$_GET['currentPage'];
	if((!$currentPage) || ($currentPage == 'undefined'))
	{$currentPage = 1;}
	$numToDisplay = 50;
	$this->view->numToDisplay = $numToDisplay;
	$this->view->currentPage = $currentPage;
	
	if ($_GET['nCol'])
	{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];	
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}
	else{
		$nCol=strtoupper($_GET['nCol']);
		$cCol=$_GET['cCol'];
		 $this->view->nCol = $_GET['nCol'];
		 $this->view->cCol = $_GET['cCol'];	
	}

	$c_jabatan=$this->view->c_jabatan;
	if ($nCol && $cCol ){$cari .= " and $cCol like '%$nCol%' ";}
	if ($c_jabatan){
	$getData = $this->agenda_serv->getTrJabatan(" and c_jabatan = '$c_jabatan' ");	
		$c_eselon=$getData[0]['c_eselon'];
		if ($c_eselon=='01' || $c_eselon=='02') {$cari .= " and c_eselon in ('01','02','03','04','05','06','07','08','09') ";}
		if ($c_eselon=='03' || $c_eselon=='04') {$cari .= " and c_eselon in ('03','04','05','06','07','08','09') ";}
		if ($c_eselon=='05' || $c_eselon=='06') {$cari .= " and c_eselon in ('05','06','07','08','09') ";}
		if ($c_eselon=='07' || $c_eselon=='08') {$cari .= " and c_eselon in ('07','08','09') ";}
		if ($c_eselon=='09') {$cari .= " and c_eselon in ('09') ";}
	}
	else{
		$cari .= " and c_eselon in ('09') ";
	}
	$this->view->totalpegawaiList = $this->pegawai_serv->getPegawaiList($cari, 0, 0 ,$orderBy);		
	$this->view->PegawaiList = $this->pegawai_serv->getPegawaiList($cari, $currentPage, $numToDisplay,$orderBy );
}

public function cmbkpdAction() {
	$npeg=$_GET['npeg'];
	$ipeg=$_GET['ipeg'];
	$nip=trim($_GET['nip']);
	$cek=$_GET['cek'];
	if (!$cek){$cek='1';}
	$html="";


$arrnpeg = explode("~",$npeg);
$arripeg = explode("~",$ipeg);
$countarr = count($arrnpeg);
if ($countarr==1)
{
	$npegx ="";
	$ipegx ="";
		$html='<table  width="100%" id ="tgtkpd"> 
			 <tr>
			   <td width="20%">Kepada</td>
			   <td width="1%">:</td>
			   <td width="79%">
				<input type="text" name="n_peg_1" id="n_peg_1" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;
				<input type="hidden" name="i_peg_nip_1" id="i_peg_nip_1" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
				$html  .=  "<input class=\"cancel\" type=\"button2\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','2')\" />&nbsp;";
				$html .='<input class="cancel" type= "button2" name="cancel" onClick="return cariData();" value=Cari>
			   </td>
			 </tr> 
			</table>';	
}
else{
   for($i=0; $i<$countarr-1; $i++)
	   {
		$npeg= $arrnpeg[$i];	
		$ipeg= $arripeg[$i];
		$npeg=stripslashes($npeg);
		//$ipegx = $ipegx.document.formagenda.i_peg_nip[i].value + "~";		
		if ($nip!=trim($ipeg)){
			$npegx = $npegx.$npeg."~";
			$ipegx = $ipegx.$ipeg."~";
		if ($i==0){
			$countpar=1;
			$html='<table  width="100%" id ="tgtkpd"> 
			 <tr>
			   <td width="20%">Kepada</td>
			   <td width="1%">:</td>
			   <td width="79%">
				<input type="text" name="n_peg_1" id="n_peg_1" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;
				<input type="hidden" name="i_peg_nip_1" id="i_peg_nip_1" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
				$html  .=  "<input class=\"cancel\" type=\"button\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','2')\" />&nbsp;";
				$html .='<input class="cancel" type= "button" name="cancel" onClick="return cariData();" value=Cari>
			   </td>
			 </tr> 
			</table>';
		}
		else{
			$countpar=$countpar*1+1;
			$html .= '<table  width="100%" id="tblIndikator_'.$countpar.'" >';
			$html .= '<tr>'; 
			if ($cek=='1'){
			$html .= '<td width="20%">&nbsp;</td>';
			}
			if ($cek=='2'){
			$html .= '<td width="20%">Kepada</td>';
			}
			$html .= '<td width="1%">:</td>';
			$html .= '<td width="79%">';		
			$html .= '<input type="text" name="n_peg_'.$countpar.'" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;';
			$html .= '<input type="hidden" name="i_peg_nip_'.$countpar.'" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
			$html  .=  "<input class=\"cancel\" type=\"button\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','1')\" />";
			if ($cek=='2'){
			$html .='&nbsp;<input class="cancel" type= "button" name="cancel" onClick="return cariData();" value=Cari>';
			}			
			$html .= '</td>'; 
			$html .= '</tr>';
			$html .= '</table>';		
		}
		}
		else{
			$npeg="";
			$ipeg="";
		}
		
		
	   }   
}
?>
<script>
	document.getElementById('nama').value="<?=$npegx?>";
	document.getElementById('nip').value="<?=$ipegx?>";
	document.getElementById('counttable').value="<?=$countpar?>";
</script>
<? 
	$this->view->par=$html;

}



function cmbkpd($ipeg,$npeg,$par) {

$html="";
$arrnpeg = explode("~",$npeg);
$arripeg = explode("~",$ipeg);
$countarr = count($arrnpeg);
if ($par=='ins')
{
			$html='<table  width="100%" id ="tgtkpd"> 
			 <tr>
			   <td width="20%">Kepada</td>
			   <td width="1%">:</td>
			   <td width="79%">
				<input type="text" name="n_peg_1" id="n_peg_1" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;
				<input type="hidden" name="i_peg_nip_1" id="i_peg_nip_1" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
				$html  .=  "<input class=\"cancel\" type=\"button\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','2')\" />&nbsp;";
				$html .='<input class="cancel" type= "button" name="cancel" onClick="return cariData();" value=Cari>
			   </td>
			 </tr> 
			</table>';
}
else
{ 
   for($i=0; $i<$countarr-1; $i++)
	   {
		$npeg= $arrnpeg[$i];	
		$ipeg= $arripeg[$i];
		$npeg=stripslashes($npeg);
			$npegx = $npegx.$npeg."~";
			$ipegx = $ipegx.$ipeg."~";
		if ($i==0){
			$countpar=1;
			$html='<table  width="100%" id ="tgtkpd"> 
			 <tr>
			   <td width="20%">Kepada</td>
			   <td width="1%">:</td>
			   <td width="79%">
				<input type="text" name="n_peg_1" id="n_peg_1" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;
				<input type="hidden" name="i_peg_nip_1" id="i_peg_nip_1" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
				$html  .=  "<input class=\"cancel\" type=\"button\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','2')\" />&nbsp;";
				$html .='<input class="cancel" type= "button" name="cancel" onClick="return cariData();" value=Cari>
			   </td>
			 </tr> 
			</table>';
		}
		else{
			$countpar=$countpar*1+1;
			$html .= '<table  width="100%" id="tblIndikator_'.$countpar.'" >';
			$html .= '<tr>'; 
			$html .= '<td width="20%">&nbsp;</td>';
			$html .= '<td width="1%">:</td>';
			$html .= '<td width="79%">';		
			$html .= '<input type="text" name="n_peg_'.$countpar.'" size="100" tabindex="1" value="'.$npeg.'" class="inputbox2"/>&nbsp;';
			$html .= '<input type="hidden" name="i_peg_nip_'.$countpar.'" size="100" tabindex="1" value="'.$ipeg.'" class="inputbox2"/>&nbsp;';
			$html  .=  "<input class=\"cancel\" type=\"button\" name=\"cancel\" value=\"Hapus\"  onClick=\"hapusBaris('$ipeg','1')\" />";
			$html .= '</td>'; 
			$html .= '</tr>';
			$html .= '</table>';		
		}

		
		
	   }   

?>
<script>
	document.getElementById('nama').value="<?=$npegx?>";
	document.getElementById('nip').value="<?=$ipegx?>";
	document.getElementById('counttable').value="<?=$countpar?>";
</script>
<?
} 
	$this->view->cmbkpd=$html;

}
	
}
?>