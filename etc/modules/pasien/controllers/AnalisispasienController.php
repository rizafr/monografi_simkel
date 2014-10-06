<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pasien/Analisispasien_Service.php";
require_once "service/pasien/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Pasien_AnalisispasienController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->report_server = $registry->get('report_server'); 
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->baseData = "../etc/data/pasien/";	

		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->analisispasien  = 'cdr';
	   
		$this->analisispasien_serv = Analisispasien_Service::getInstance();
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssoanalisispasien = new Zend_Session_Namespace('ssoanalisispasien');
	    $this->iduser =$ssoanalisispasien->user_id;
	   // $this->view->t_tensiuser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function analisispasienjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('analisispasienjs');
    }
	
	//test OPen report
	//----------------------
	public function analisispasienlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;

		if ( $_REQUEST['param1']){ $this->view->t_awal= $_REQUEST['param1'];}
		else {  $this->view->t_awal= $_REQUEST['t_awal']; 
		}

		if ( $_REQUEST['param2']){ $this->view->t_akhir= $_REQUEST['param2'];}
		else {  $this->view->t_akhir= $_REQUEST['t_akhir']; 
		}
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_analisispasien';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"t_awal"		=> $this->view->t_awal,
							"t_akhir"		=> $this->view->t_akhir,
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totAnalisispasienList = $this->analisispasien_serv->cariAnalisispasienList($dataMasukan,0,0,0);
		$this->view->analisispasienList = $this->analisispasien_serv->cariAnalisispasienList($dataMasukan,$currentPage, $numToDisplay,$this->view->totAnalisispasienList);		
	}
	
	public function analisispasiendataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		
		$this->view->detailAnalisispasien				= $this->analisispasien_serv->detailAnalisispasienById($this->view->id);
		$this->view->detailAnalisispasienList			= $this->analisispasien_serv->AnalisispasienList($this->view->id);
	}
	
	public function analisispasienAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->user_id =$ssogroup->user_id;

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];

		$id								= $_POST['id'];
		$n_nama							= $_POST['n_nama'];
		$kode_pasien					= $_POST['kode_pasien'];
		$d_medrec						= $_POST['d_medrec'];
		$b_badan						= $_POST['b_badan'];
		$t_badan						= $_POST['t_badan'];
		$n_tensi						= $_POST['n_tensi'];
		$c_klasifikasi					= $_POST['c_klasifikasi'];
		$c_tindakan						= $_POST['c_tindakan'];
		$n_diagnosis					= $_POST['n_diagnosis'];
		$n_terapi						= $_POST['n_terapi'];
		$c_hematologi					= $_POST['c_hematologi'];
		$c_kimiahati					= $_POST['c_kimiahati'];
		$c_glukosa						= $_POST['c_glukosa'];
		$c_cholesterol					= $_POST['c_cholesterol'];
		$c_alergi						= $_POST['c_alergi'];
		$c_rematik						= $_POST['c_rematik'];
		$v_hemoglobin					= $_POST['v_hemoglobin'];
		$v_leukosit						= $_POST['v_leukosit'];
		$v_trombosit					= $_POST['v_trombosit'];
		$v_eritrosit					= $_POST['v_eritrosit'];
		$v_got							= $_POST['v_got'];
		$v_gpt							= $_POST['v_gpt'];
		$v_glukosa						= $_POST['v_glukosa'];
		$v_cholesterol					= $_POST['v_cholesterol'];
		$v_igetotal						= $_POST['v_igetotal'];
		$v_igeatopi						= $_POST['v_igeatopi'];
		$n_igeket						= $_POST['n_igeket'];
		$v_asto							= $_POST['v_asto'];
		$v_anaif						= $_POST['v_anaif'];
		$v_anaelisa						= $_POST['v_anaelisa'];
		$v_letest						= $_POST['v_letest'];
		$v_antidsdna					= $_POST['v_antidsdna'];
		$v_antiparietalsel				= $_POST['v_antiparietalsel'];
		$v_imun							= $_POST['v_imun'];
		$v_imunka						= $_POST['v_imunka'];
		
		if($d_medrec) {
		$bln = substr($d_medrec, 3, 2);$tgl = substr($d_medrec, 0, 2);$thn = substr($d_medrec, 6, 4);
		$d_medrec = $thn."-".$bln."-".$tgl; 
		} else {$d_medrec ="-";}

		$dataMasukan	= array("kode_pasien"           => $kode_pasien,
								"n_nama"				=> $n_nama,
								"d_medrec"	            => $d_medrec,
								"b_badan"	            => $b_badan,
								"t_badan"	            => $t_badan,
								"n_tensi"	            => $n_tensi,
								"c_klasifikasi"	        => $c_klasifikasi,
								"c_tindakan"	        => $c_tindakan,
								"n_diagnosis"           => $n_diagnosis,
								"n_terapi"	            => $n_terapi,
								"c_hematologi"			=> $c_hematologi,
								"c_kimiahati"			=> $c_kimiahati,
								"c_glukosa"				=> $c_glukosa,
								"c_cholesterol"			=> $c_cholesterol,
								"c_alergi"				=> $c_alergi,
								"c_rematik"				=> $c_rematik,
								"v_hemoglobin"          => $v_hemoglobin,
								"v_leukosit"	        => $v_leukosit,
								"v_trombosit"           => $v_trombosit,
								"v_eritrosit"           => $v_eritrosit,
								"v_got"		            => $v_got,
								"v_gpt"		            => $v_gpt,
								"v_glukosa"	            => $v_glukosa,
								"v_cholesterol"         => $v_cholesterol,
								"v_igetotal"	        => $v_igetotal,
								"v_igeatopi"	        => $v_igeatopi,
								"n_igeket"	            => $n_igeket,
								"v_asto"		        => $v_asto,
								"v_anaif"	            => $v_anaif,
								"v_anaelisa"	        => $v_anaelisa,
								"v_letest"	            => $v_letest,
								"v_antidsdna"           => $v_antidsdna,
								"v_antiparietalsel"		=> $v_antiparietalsel,
								"v_imun"		        => $v_imun,
								"v_imunka"	            => $v_imunka,
								"cuid"					=> $ssogroup->user_id
								);

		$this->view->analisispasienInsert = $this->analisispasien_serv->analisispasienInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->analisispasienInsert;
		
		$this->analisispasienlistAction();
		$this->render('analisispasienlist');

	}
	
	public function analisispasienupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];

		$id								= $_POST['id'];
		$jenisForm						= $_POST['jenisForm'];

		$kode_pasien					= $_POST['kode_pasien'];
		$n_nama							= $_POST['n_nama'];
		$d_medrec						= $_POST['d_medrec'];
		$b_badan						= $_POST['b_badan'];
		$t_badan						= $_POST['t_badan'];
		$n_tensi						= $_POST['n_tensi'];
		$c_klasifikasi					= $_POST['c_klasifikasi'];
		$c_tindakan						= $_POST['c_tindakan'];
		$n_diagnosis					= $_POST['n_diagnosis'];
		$n_terapi						= $_POST['n_terapi'];
		$c_hematologi					= $_POST['c_hematologi'];
		$c_kimiahati					= $_POST['c_kimiahati'];
		$c_glukosa						= $_POST['c_glukosa'];
		$c_cholesterol					= $_POST['c_cholesterol'];
		$c_alergi						= $_POST['c_alergi'];
		$c_rematik						= $_POST['c_rematik'];
		$v_hemoglobin					= $_POST['v_hemoglobin'];
		$v_leukosit						= $_POST['v_leukosit'];
		$v_trombosit					= $_POST['v_trombosit'];
		$v_eritrosit					= $_POST['v_eritrosit'];
		$v_got							= $_POST['v_got'];
		$v_gpt							= $_POST['v_gpt'];
		$v_glukosa						= $_POST['v_glukosa'];
		$v_cholesterol					= $_POST['v_cholesterol'];
		$v_igetotal						= $_POST['v_igetotal'];
		$v_igeatopi						= $_POST['v_igeatopi'];
		$n_igeket						= $_POST['n_igeket'];
		$v_asto							= $_POST['v_asto'];
		$v_anaif						= $_POST['v_anaif'];
		$v_anaelisa						= $_POST['v_anaelisa'];
		$v_letest						= $_POST['v_letest'];
		$v_antidsdna					= $_POST['v_antidsdna'];
		$v_antiparietalsel				= $_POST['v_antiparietalsel'];
		$v_imun							= $_POST['v_imun'];
		$v_imunka						= $_POST['v_imunka'];
		if($d_medrec) {
		$bln = substr($d_medrec, 3, 2);$tgl = substr($d_medrec, 0, 2);$thn = substr($d_medrec, 6, 4);
		$d_medrec = $thn."-".$bln."-".$tgl; 
		} else {$d_medrec ="-";}


		$dataMasukanUpd = array("id"					=> $id,
								"kode_pasien"           => $kode_pasien,
								"n_nama"				=> $n_nama,
								"d_medrec"	            => $d_medrec,
								"b_badan"	            => $b_badan,
								"t_badan"	            => $t_badan,
								"n_tensi"	            => $n_tensi,
								"c_klasifikasi"	        => $c_klasifikasi,
								"c_tindakan"	        => $c_tindakan,
								"n_diagnosis"           => $n_diagnosis,
								"n_terapi"	            => $n_terapi,
								"c_hematologi"			=> $c_hematologi,
								"c_kimiahati"			=> $c_kimiahati,
								"c_glukosa"				=> $c_glukosa,
								"c_cholesterol"			=> $c_cholesterol,
								"c_alergi"				=> $c_alergi,
								"c_rematik"				=> $c_rematik,
								"v_hemoglobin"          => $v_hemoglobin,
								"v_leukosit"	        => $v_leukosit,
								"v_trombosit"           => $v_trombosit,
								"v_eritrosit"           => $v_eritrosit,
								"v_got"		            => $v_got,
								"v_gpt"		            => $v_gpt,
								"v_glukosa"	            => $v_glukosa,
								"v_cholesterol"         => $v_cholesterol,
								"v_igetotal"	        => $v_igetotal,
								"v_igeatopi"	        => $v_igeatopi,
								"n_igeket"	            => $n_igeket,
								"v_asto"		        => $v_asto,
								"v_anaif"	            => $v_anaif,
								"v_anaelisa"	        => $v_anaelisa,
								"v_letest"	            => $v_letest,
								"v_antidsdna"           => $v_antidsdna,
								"v_antiparietalsel"		=> $v_antiparietalsel,
								"v_imun"		        => $v_imun,
								"v_imunka"	            => $v_imunka,
								"muid"					=> $ssogroup->user_id
								);
		$this->view->analisispasienUpdate = $this->analisispasien_serv->analisispasienUpdate($dataMasukanUpd);

if((isset($_FILES['a_file']['error']) && $_FILES['a_file'] == 0) || (!empty($_FILES['a_file']['tmp_name']) && $_FILES['a_file']['tmp_name'] != 'none'))
{
$n_file =trim($id)."-@".trim($_FILES['a_file']['name']);
$ukuran =$_FILES['a_file']['size'];

$Filetersimpan		= $_POST['a_file'];
$destDirtersimpan	= "../etc/data/pasien/$Filetersimpan";
if($_POST['a_file']){unlink($destDirtersimpan);}


$this->view->analisispasienUpdate = $this->analisispasien_serv->analisispasienFotoUpdate($id,$n_file);
if ($this->view->analisispasienUpdate=="sukses" && $n_file){
				if (!empty($_FILES['a_file'])){$FileName = $n_file;}
				$FileName = $n_file;				
				if (!empty($_FILES['a_file'])){$destDir = "../etc/data/pasien/$FileName";	
					if (move_uploaded_file($_FILES['a_file']['tmp_name'], $destDir)) {$lampiran ="file";}
				}
			}



}


		$this->Logfile->createLog($this->view->t_tensiuser, "Ubah data", $t_tensi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Umum Analisispasien";
		$this->view->hasil = $this->view->analisispasienUpdate;
		
		$this->analisispasienlistAction();
		$this->render('analisispasienlist');
	}


	public function analisispasienhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];

		$dataMasukan = array("id" => $id);

		$this->view->analisispasienUpdate = $this->analisispasien_serv->analisispasienHapus($dataMasukan);
		$this->Logfile->createLog($this->view->t_tensiuser, "Hapus data analisispasien user", $n_analisispasien." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->analisispasienUpdate;
		
		$this->analisispasienlistAction();
		$this->render('analisispasienlist');
	}

	public function cetakrekappdfAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->d_medrec		= $_REQUEST['d_medrec'];

		$this->view->detailAnalisispasienList	= $this->analisispasien_serv->AnalisispasienList($this->view->d_medrec);
	}

	public function rekappdfAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];

		$this->view->detailAnalisispasienList	= $this->analisispasien_serv->rekapanList($this->view->t_awal,$this->view->t_akhir);
	}

	public function pasienlistAction() {
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {$pageNumber = 1;}

		$itemPerPage = $_REQUEST['numToDisplay'];
		if(!$itemPerPage) {$itemPerPage 	= 30;}
		
		$this->view->numToDisplay = $itemPerPage;
		$this->view->currentPage = $pageNumber;
		$ssologin = new Zend_Session_Namespace('login_data');
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		
		$dataMasukan = array("kategoriCari" => trim($this->view->kategoriCari),
							"katakunciCari" => trim($this->view->carii)
							);
							 
		$this->view->totPasienList 		= $this->pendaftaran_serv->cariPendaftaranList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage		= $itemPerPage;
		$this->view->pasienList 		= $this->pendaftaran_serv->cariPendaftaranList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totPasienList);
		
	}

	public function getKabAction(){
		$this->view->list		=  $this->ref_serv->getKabByPropList(trim($_REQUEST["propinsi"]));
	}

}
?>