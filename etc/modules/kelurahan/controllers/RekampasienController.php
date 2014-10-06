<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/pasien/Rekampasien_Service.php";
require_once "service/pasien/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_RekampasienController extends Zend_Controller_Action {
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
	    $this->rekampasien  = 'cdr';
	   
		$this->rekampasien_serv = Rekampasien_Service::getInstance();
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssorekampasien = new Zend_Session_Namespace('ssorekampasien');
	    $this->iduser =$ssorekampasien->user_id;
	   // $this->view->t_tensiuser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	public function cameraAction() {
	   $this->view->id				= $_GET['id'];
	   var_dump("xxx-->".$this->view->id);
    }
	public function uploadAction() {

		$this->view->id				= $_REQUEST['id'];
	   //var_dump("yyy-->".$this->view->id);
	   $name = date('YmdHis');
$newname="../www/pasien/".$name.".jpg";
$file = file_put_contents( $newname, file_get_contents('php://input') );
if (!$file){
	print "ERROR: Failed to write data to $filename, check permissions\n";
	exit();
}
else{
	$this->view->rekampasienUpdate = $this->rekampasien_serv->rekampasienFotoUpdate($this->view->id,$name.".jpg");
	exit();
}
/*else
{
    $sql="Insert into entry(images) values('$newname')";
    $result=mysqli_query($con,$sql)
            or die("Error in query");
    $value=mysqli_insert_id($con);
    $_SESSION["myvalue"]=$value;
}*/

$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $newname;
print "$url\n";
    }
	public function rekampasienjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('rekampasienjs');
    }
	
	//test OPen report
	//----------------------
	public function rekampasienlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}

		$this->view->kode_pasien = $_REQUEST['kode_pasien'];

		$this->view->detailPasien= $this->rekampasien_serv->detailPasienByKode($this->view->kode_pasien);

		$this->view->agamaList = $this->ref_serv->getAgamaList();
		$this->view->statusList = $this->ref_serv->getStatusList();
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_rekampasien';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"kode_pasien"	=> $_REQUEST['kode_pasien'],
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totRekampasienList = $this->rekampasien_serv->cariRekampasienList($dataMasukan,0,0,0);
		$this->view->rekampasienList = $this->rekampasien_serv->cariRekampasienList($dataMasukan,$currentPage, $numToDisplay,$this->view->totRekampasienList);		
		//var_dump($this->view->rekampasienList);
		//exit();
	}
	
	public function rekampasiendataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		$this->view->kode_pasien	= $_REQUEST['kode_pasien'];
		$this->view->detailPasien	= $this->rekampasien_serv->detailPasienByKode($this->view->kode_pasien);

		$this->view->agamaList		= $this->ref_serv->getAgamaList();
		$this->view->statusList		= $this->ref_serv->getStatusList();
		$this->view->propinsiList	= $this->ref_serv->getPropinsiList();
		$this->view->goldarList		= $this->ref_serv->getGoldarList();
		$this->view->klasifikasiList = $this->ref_serv->getKlasifikasiList();
		$this->view->tindakanList	= $this->ref_serv->getTindakanList();
		$this->view->detailRekampasien				= $this->rekampasien_serv->detailRekampasienById($this->view->id);
	}
	
	public function rekampasienAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->user_id =$ssogroup->user_id;

		$this->view->kode_pasien	= $_REQUEST['kode_pasien'];
		$this->view->detailPasien	= $this->rekampasien_serv->detailPasienByKode($this->view->kode_pasien);


		$c_noreg						= $_POST['c_noreg'];
		$t_waktu						= $_POST['t_waktu'];
		$t_berat						= $_POST['t_berat'];
		$t_tinggi						= $_POST['t_tinggi'];
		$t_tekanan						= $_POST['t_tekanan'];
		$t_denyut						= $_POST['t_denyut'];
		$t_frequensi					= $_POST['t_frequensi'];
		$t_suhu							= $_POST['t_suhu'];
		$n_mata							= $_POST['n_mata'];
		$n_tht							= $_POST['n_tht'];
		$n_gigi							= $_POST['n_gigi'];
		$n_leher						= $_POST['n_leher'];
		$n_jantung						= $_POST['n_jantung'];
		$n_paru							= $_POST['n_paru'];
		$n_perut						= $_POST['n_perut'];
		$n_gerak						= $_POST['n_gerak'];
		$n_gizi						= $_POST['n_gizi'];
		$n_potensi						= $_POST['n_potensi'];
		$n_mental						= $_POST['n_mental'];
		$n_reproduksi						= $_POST['n_reproduksi'];
		$n_kematangan						= $_POST['n_kematangan'];
		$n_hb						= $_POST['n_hb'];
		$n_feses						= $_POST['n_feses'];
		$n_jasmani						= $_POST['n_jasmani'];
		
		$c_golongan						= $_POST['c_golongan'];
		$c_jantung						= $_POST['c_jantung'];
		$c_lain							= $_POST['c_lain'];
		$c_asma							= $_POST['c_asma'];
		$c_thalassimia						= $_POST['c_thalassimia'];
		$c_jiwa							= $_POST['c_jiwa'];
		$c_darting						= $_POST['c_darting'];
		$c_manis						= $_POST['c_manis'];
		$c_kanker						= $_POST['c_kanker'];
		$c_golongani						= $_POST['c_golongani'];
		$c_jantungi						= $_POST['c_jantungi'];
		$c_laini							= $_POST['c_laini'];
		$c_asmai							= $_POST['c_asmai'];
		$c_thalassimiai						= $_POST['c_thalassimiai'];
		$c_jiwai							= $_POST['c_jiwai'];
		$c_dartingi						= $_POST['c_dartingi'];
		$c_manisi						= $_POST['c_manisi'];
		$c_kankeri						= $_POST['c_kankeri'];
		
		
		$c_golongan1							= $_POST['c_golongan1'];
		$n_rhesus						= $_POST['n_rhesus'];
		$c_jantung1						= $_POST['c_jantung1'];
		$n_lain						= $_POST['n_lain'];
		$asma							= $_POST['asma'];
		$c_thalassimia1						= $_POST['c_thalassimia1'];
		$c_jiwa1							= $_POST['c_jiwa1'];
		$c_menular							= $_POST['c_menular'];
		$n_sebut							= $_POST['n_sebut'];
		$c_rokok							= $_POST['c_rokok'];
		$c_minum							= $_POST['c_minum'];
		$c_narkoba							= $_POST['c_narkoba'];
		$n_lain1							= $_POST['n_lain1'];
		$n_haid							= $_POST['n_haid'];
		$c_teratur							= $_POST['c_teratur'];
		$n_mimpi							= $_POST['n_mimpi'];
		$c_dasar							= $_POST['c_dasar'];
		$c_lengkap							= $_POST['c_lengkap'];
		
		$t_berat2						= $_POST['t_berat2'];
		$t_tinggi2						= $_POST['t_tinggi2'];
		$t_tekanan2						= $_POST['t_tekanan2'];
		$t_denyut2						= $_POST['t_denyut2'];
		$t_frequensi2					= $_POST['t_frequensi2'];
		$t_suhu2							= $_POST['t_suhu2'];
		$n_mata2							= $_POST['n_mata2'];
		$n_tht2							= $_POST['n_tht2'];
		$n_gigi2							= $_POST['n_gigi2'];
		$n_jantung2						= $_POST['n_jantung2'];
		$n_paru2							= $_POST['n_paru2'];
		$n_perut2						= $_POST['n_perut2'];
		$n_gerak2						= $_POST['n_gerak2'];
		$n_gizi2						= $_POST['n_gizi2'];
		
		$n_darah						= $_POST['n_darah'];
		$n_hemoglobin						= $_POST['n_hemoglobin'];
		$n_urin						= $_POST['n_urin'];
		$n_faeces						= $_POST['n_faeces'];
		$n_thalassimia						= $_POST['n_thalassimia'];
		$n_diagnosa						= $_POST['n_diagnosa'];
		
		
		if($d_medrec) {
		$bln = substr($d_medrec, 3, 2);$tgl = substr($d_medrec, 0, 2);$thn = substr($d_medrec, 6, 4);
		$d_medrec = $thn."-".$bln."-".$tgl; 
		} else {$d_medrec ="-";}

				$dataMasukan	= array("c_noreg"           => $c_noreg,
								"t_waktu"				=> $t_waktu,
								"t_berat"	            => $t_berat,
								"t_tinggi"	            => $t_tinggi,
								"t_tekanan"	            => $t_tekanan,
								"t_denyut"	            => $t_denyut,
								"t_frequensi"	        => $t_frequensi,
								"t_suhu"	        => $t_suhu,
								"n_mata"           => $n_mata,
								"n_tht"	            => $n_tht,
								"n_gigi"			=> $n_gigi,
								"n_leher"			=> $n_leher,
								"n_jantung"				=> $n_jantung,
								"n_paru"			=> $n_paru,
								"n_perut"				=> $n_perut,
								"n_gerak"				=> $n_gerak,
								"n_gizi"				=> $n_gizi,
								"n_potensi"				=> $n_potensi,
								"n_mental"				=> $n_mental,
								"n_reproduksi"				=> $n_reproduksi,
								"n_kematangan"				=> $n_kematangan,
								"n_hb"				=> $n_hb,
								"n_feses"				=> $n_feses,
								"n_jasmani"				=> $n_jasmani,
								
								
								
								"c_golongan"				=> $c_golongan,
								"c_jantung"				=> $c_jantung,
								"c_lain"				=> $c_lain,
								"c_asma"				=> $c_asma,
								"c_thalassimia"				=> $c_thalassimia,
								"c_jiwa"				=> $c_jiwa,
								"c_darting"				=> $c_darting,
								"c_manis"				=> $c_manis,
								"c_kanker"				=> $c_kanker,
								"c_golongani"				=> $c_golongani,
								"c_jantungi"				=> $c_jantungi,
								"c_laini"				=> $c_laini,
								"c_asmai"				=> $c_asmai,
								"c_thalassimiai"				=> $c_thalassimiai,
								"c_jiwai"				=> $c_jiwai,
								"c_dartingi"				=> $c_dartingi,
								"c_manisi"				=> $c_manisi,
								"c_kankeri"				=> $c_kankeri,
								
								"c_golongan1"				=> $c_golongan1,
								"n_rhesus"				=> $n_rhesus,
								"c_jantung1"				=> $c_jantung1,
								"n_lain"				=> $n_lain,
								"asma"				=> $asma,
								"c_thalassimia1"				=> $c_thalassimia1,
								"c_jiwa1"				=> $c_jiwa1,
								"c_menular"				=> $c_menular,
								"n_sebut"				=> $n_sebut,
								"c_rokok"				=> $c_rokok,
								"c_minum"				=> $c_minum,
								"c_narkoba"				=> $c_narkoba,
								"n_lain1"				=> $n_lain1,
								"n_haid"				=> $n_haid,
								"c_teratur"				=> $c_teratur,
								"n_mimpi"				=> $n_mimpi,
								"c_dasar"				=> $c_dasar,
								"c_lengkap"				=> $c_lengkap,
								
								"t_berat2"	            => $t_berat2,
								"t_tinggi2"	            => $t_tinggi2,
								"t_tekanan2"	            => $t_tekanan2,
								"t_denyut2"	            => $t_denyut2,
								"t_frequensi2"	        => $t_frequensi2,
								"t_suhu2"	        => $t_suhu2,
								"n_mata2"           => $n_mata2,
								"n_tht2"	            => $n_tht2,
								"n_gigi2"			=> $n_gigi2,
								"n_jantung2"				=> $n_jantung2,
								"n_paru2"			=> $n_paru2,
								"n_perut2"				=> $n_perut2,
								"n_gerak2"				=> $n_gerak2,
								"n_gizi2"				=> $n_gizi2,
								
								"n_darah"				=> $n_darah,
								"n_hemoglobin"				=> $n_hemoglobin,
								"n_urin"				=> $n_urin,
								"n_faeces"				=> $n_faeces,
								"n_thalassimia"				=> $n_thalassimia,
								"n_diagnosa"				=> $n_diagnosa,
								
								
								
								);

		$this->view->rekampasienInsert = $this->rekampasien_serv->rekampasienInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->rekampasienInsert;
		
		$this->rekampasienlistAction();
		$this->render('rekampasienlist');

	}
	
	public function rekampasienupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id						= $_POST['id'];
		$jenisForm						= $_POST['jenisForm'];
		$this->view->kode_pasien	= $_REQUEST['kode_pasien'];
		$this->view->detailPasien	= $this->rekampasien_serv->detailPasienByKode($this->view->kode_pasien);


		$c_noreg							= $_POST['c_noreg'];
		$t_waktu							= $_POST['t_waktu'];
		$t_berat						= $_POST['t_berat'];
		$t_tinggi						= $_POST['t_tinggi'];
		$t_tekanan						= $_POST['t_tekanan'];
		$t_denyut						= $_POST['t_denyut'];
		$t_frequensi					= $_POST['t_frequensi'];
		$t_suhu							= $_POST['t_suhu'];
		$n_mata							= $_POST['n_mata'];
		$n_tht							= $_POST['n_tht'];
		$n_gigi							= $_POST['n_gigi'];
		$n_leher						= $_POST['n_leher'];
		$n_jantung						= $_POST['n_jantung'];
		$n_paru							= $_POST['n_paru'];
		$n_perut						= $_POST['n_perut'];
		$n_gerak						= $_POST['n_gerak'];
		$c_golongan						= $_POST['c_golongan'];
		$c_jantung						= $_POST['c_jantung'];
		$c_lain							= $_POST['c_lain'];
		$c_asma							= $_POST['c_asma'];
		$c_thalassimia						= $_POST['c_thalassimia'];
		$c_jiwa							= $_POST['c_jiwa'];
		$c_darting						= $_POST['c_darting'];
		$c_manis						= $_POST['c_manis'];
		$c_kanker						= $_POST['c_kanker'];
		$c_golongani						= $_POST['c_golongani'];
		$c_jantungi						= $_POST['c_jantungi'];
		$c_laini							= $_POST['c_laini'];
		$c_asmai							= $_POST['c_asmai'];
		$c_thalassimiai						= $_POST['c_thalassimiai'];
		$c_jiwai							= $_POST['c_jiwai'];
		$c_dartingi						= $_POST['c_dartingi'];
		$c_manisi						= $_POST['c_manisi'];
		$c_kankeri						= $_POST['c_kankeri'];
		
		
		$c_golongan1							= $_POST['c_golongan1'];
		$n_rhesus						= $_POST['n_rhesus'];
		$c_jantung1						= $_POST['c_jantung1'];
		$n_lain						= $_POST['n_lain'];
		$asma							= $_POST['asma'];
		$c_thalassimia1						= $_POST['c_thalassimia1'];
		$c_jiwa1							= $_POST['c_jiwa1'];
		$c_menular							= $_POST['c_menular'];
		$n_sebut							= $_POST['n_sebut'];
		$c_rokok							= $_POST['c_rokok'];
		$c_minum							= $_POST['c_minum'];
		$c_narkoba							= $_POST['c_narkoba'];
		$n_lain1							= $_POST['n_lain1'];
		$n_haid							= $_POST['n_haid'];
		$c_teratur							= $_POST['c_teratur'];
		$n_mimpi							= $_POST['n_mimpi'];
		$c_dasar							= $_POST['c_dasar'];
		$c_lengkap							= $_POST['c_lengkap'];
		
		$t_berat2						= $_POST['t_berat2'];
		$t_tinggi2						= $_POST['t_tinggi2'];
		$t_tekanan2						= $_POST['t_tekanan2'];
		$t_denyut2						= $_POST['t_denyut2'];
		$t_frequensi2					= $_POST['t_frequensi2'];
		$t_suhu2							= $_POST['t_suhu2'];
		$n_mata2							= $_POST['n_mata2'];
		$n_tht2							= $_POST['n_tht2'];
		$n_gigi2							= $_POST['n_gigi2'];
		$n_jantung2						= $_POST['n_jantung2'];
		$n_paru2							= $_POST['n_paru2'];
		$n_perut2						= $_POST['n_perut2'];
		$n_gerak2						= $_POST['n_gerak2'];
		$n_gizi2						= $_POST['n_gizi2'];
		
		$n_darah						= $_POST['n_darah'];
		$n_hemoglobin						= $_POST['n_hemoglobin'];
		$n_urin						= $_POST['n_urin'];
		$n_faeces						= $_POST['n_faeces'];
		$n_thalassimia						= $_POST['n_thalassimia'];
		$n_diagnosa						= $_POST['n_diagnosa'];
		
		
		
		
		if($d_medrec) {
		$bln = substr($d_medrec, 3, 2);$tgl = substr($d_medrec, 0, 2);$thn = substr($d_medrec, 6, 4);
		$d_medrec = $thn."-".$bln."-".$tgl; 
		} else {$d_medrec ="-";}


		$dataMasukanUpd = array("id"           => $id,
								"c_noreg"           => $c_noreg,
								"t_waktu"				=> $t_waktu,
								"t_berat"	            => $t_berat,
								"t_tinggi"	            => $t_tinggi,
								"t_tekanan"	            => $t_tekanan,
								"t_denyut"	            => $t_denyut,
								"t_frequensi"	        => $t_frequensi,
								"t_suhu"	        => $t_suhu,
								"n_mata"           => $n_mata,
								"n_tht"	            => $n_tht,
								"n_gigi"			=> $n_gigi,
								"n_leher"			=> $n_leher,
								"n_jantung"				=> $n_jantung,
								"n_paru"			=> $n_paru,
								"n_perut"				=> $n_perut,
								"n_gerak"				=> $n_gerak,
								"c_golongan"				=> $c_golongan,
								"c_jantung"				=> $c_jantung,
								"c_lain"				=> $c_lain,
								"c_asma"				=> $c_asma,
								"c_thalassimia"				=> $c_thalassimia,
								"c_jiwa"				=> $c_jiwa,
								"c_darting"				=> $c_darting,
								"c_manis"				=> $c_manis,
								"c_kanker"				=> $c_kanker,
								"c_golongani"				=> $c_golongani,
								"c_jantungi"				=> $c_jantungi,
								"c_laini"				=> $c_laini,
								"c_asmai"				=> $c_asmai,
								"c_thalassimiai"				=> $c_thalassimiai,
								"c_jiwai"				=> $c_jiwai,
								"c_dartingi"				=> $c_dartingi,
								"c_manisi"				=> $c_manisi,
								"c_kankeri"				=> $c_kankeri,
								
								"c_golongan1"				=> $c_golongan1,
								"n_rhesus"				=> $n_rhesus,
								"c_jantung1"				=> $c_jantung1,
								"n_lain"				=> $n_lain,
								"asma"				=> $asma,
								"c_thalassimia1"				=> $c_thalassimia1,
								"c_jiwa1"				=> $c_jiwa1,
								"c_menular"				=> $c_menular,
								"n_sebut"				=> $n_sebut,
								"c_rokok"				=> $c_rokok,
								"c_minum"				=> $c_minum,
								"c_narkoba"				=> $c_narkoba,
								"n_lain1"				=> $n_lain1,
								"n_haid"				=> $n_haid,
								"c_teratur"				=> $c_teratur,
								"n_mimpi"				=> $n_mimpi,
								"c_dasar"				=> $c_dasar,
								"c_lengkap"				=> $c_lengkap,
								
								"t_berat2"	            => $t_berat2,
								"t_tinggi2"	            => $t_tinggi2,
								"t_tekanan2"	            => $t_tekanan2,
								"t_denyut2"	            => $t_denyut2,
								"t_frequensi2"	        => $t_frequensi2,
								"t_suhu2"	        => $t_suhu2,
								"n_mata2"           => $n_mata2,
								"n_tht2"	            => $n_tht2,
								"n_gigi2"			=> $n_gigi2,
								"n_jantung2"				=> $n_jantung2,
								"n_paru2"			=> $n_paru2,
								"n_perut2"				=> $n_perut2,
								"n_gerak2"				=> $n_gerak2,
								"n_gizi2"				=> $n_gizi2,
								
								"n_darah"				=> $n_darah,
								"n_hemoglobin"				=> $n_hemoglobin,
								"n_urin"				=> $n_urin,
								"n_faeces"				=> $n_faeces,
								"n_thalassimia"				=> $n_thalassimia,
								"n_diagnosa"				=> $n_diagnosa,
								
								);
		$this->view->rekampasienUpdate = $this->rekampasien_serv->rekampasienUpdate($dataMasukanUpd);

if((isset($_FILES['a_file']['error']) && $_FILES['a_file'] == 0) || (!empty($_FILES['a_file']['tmp_name']) && $_FILES['a_file']['tmp_name'] != 'none'))
{
$n_file =trim($id)."-@".trim($_FILES['a_file']['name']);
$ukuran =$_FILES['a_file']['size'];

$Filetersimpan		= $_POST['a_file'];
$destDirtersimpan	= "../etc/data/pasien/$Filetersimpan";
if($_POST['a_file']){unlink($destDirtersimpan);}


$this->view->rekampasienUpdate = $this->rekampasien_serv->rekampasienFotoUpdate($id,$n_file);
if ($this->view->rekampasienUpdate=="sukses" && $n_file){
				if (!empty($_FILES['a_file'])){$FileName = $n_file;}
				$FileName = $n_file;				
				if (!empty($_FILES['a_file'])){$destDir = "../etc/data/pasien/$FileName";	
					if (move_uploaded_file($_FILES['a_file']['tmp_name'], $destDir)) {$lampiran ="file";}
				}
			}



}


		$this->Logfile->createLog($this->view->t_tensiuser, "Ubah data", $t_tensi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Umum Rekampasien";
		$this->view->hasil = $this->view->rekampasienUpdate;
		
		$this->rekampasienlistAction();
		$this->render('rekampasienlist');
	}



	
	public function rekampasienhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];

		$this->view->kode_pasien	= $_REQUEST['kode_pasien'];
		$this->view->detailPasien	= $this->rekampasien_serv->detailPasienByKode($this->view->kode_pasien);


		$dataMasukan = array("id" => $id);

		$this->view->rekampasienUpdate = $this->rekampasien_serv->rekampasienHapus($dataMasukan);
		$this->Logfile->createLog($this->view->t_tensiuser, "Hapus data rekampasien user", $n_rekampasien." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->rekampasienUpdate;
		
		$this->rekampasienlistAction();
		$this->render('rekampasienlist');
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