<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/kelurahan/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_PendaftaranController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->report_server = $registry->get('report_server'); 
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->pendaftaran  = 'cdr';
	   
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();
		$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
		$this->kd_kel = $ssogroup->kd_kel;
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopendaftaran = new Zend_Session_Namespace('ssopendaftaran');
	    $this->kd_kel =$ssopendaftaran->kd_kel;
		$this->kd_kel =$ssousergroup->kd_kel;
	   // $this->view->n_namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
	function anti_injection($data){
		$filter = trim(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));

		return $filter;

	}
	
    public function indexAction() {
	   
    }
	
	public function pendaftaranjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('pendaftaranjs');
    }
	
	//test OPen report
	//----------------------
	public function pendaftaranlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->kelurahan =$ssogroup->kelurahan;
		$kd_kel =$ssogroup->kd_kel;

		if ( $_REQUEST['param1']){ $this->view->cabang= $_REQUEST['param1'];}
		else {  $this->view->cabang= $_REQUEST['cabang']; 
		}

		if ( $_REQUEST['param2']){ $this->view->korek2= $_REQUEST['param2'];}
		else {  $this->view->korek2= $_REQUEST['korek2']; 
		}
		// $this->view->agamaList = $this->ref_serv->getAgamaList();
		// $this->view->statusList = $this->ref_serv->getStatusList();
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= trim(stripslashes(strip_tags(htmlspecialchars($_REQUEST['carii']))));
		
		
		$sortBy			= 'kd_kel';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"kd_kel"	=> 		$kd_kel,
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totPendaftaranList = $this->pendaftaran_serv->cariPendaftaranList($dataMasukan,0,0,0);
		$this->view->pendaftaranList = $this->pendaftaran_serv->cariPendaftaranList($dataMasukan,$currentPage, $numToDisplay,$this->view->totPendaftaranList);
		//var_dump($this->view->pendaftaranList );		
	}
	
	public function pendaftarandataAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->kd_kel				= $_REQUEST['kd_kel'];
		
		$this->view->detailPendaftaran				= $this->pendaftaran_serv->detailPendaftaranById($this->view->kd_kel);
		//var_dump($this->view->detailPendaftaran);
	}
	
	public function pendaftaranAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->group_id =$ssogroup->group_id;
		$this->view->user_id =$ssogroup->user_id;

		$kd_kel							= $_POST['kd_kel'];
		$tahun_pembentukan				= $_POST['tahun_pembentukan'];
		$dasar_pembentukan				= $_POST['dasar_pembentukan'];
		$kode_wilayah					= $_POST['kode_wilayah'];
		$kode_pos						= $_POST['kode_pos'];
		$luas							= $_POST['luas'];
		$batas_utara					= $_POST['batas_utara'];
		$batas_selatan					= $_POST['batas_selatan'];
		$batas_barat					= $_POST['batas_barat'];
		$batas_timur					= $_POST['batas_timur'];
		$jarak_dari_kecamatan			= $_POST['jarak_dari_kecamatan'];
		$jarak_dari_kota				= $_POST['jarak_dari_kota'];
		$jarak_dari_ibukota_kota		= $_POST['jarak_dari_ibukota_kota'];
		$jarak_dari_ibukota_prov		= $_POST['jarak_dari_ibukota_prov'];
	

		$dataMasukan	= array("kd_kel"				=> $kd_kel,
								"tahun_pembentukan"		=> $tahun_pembentukan,
								"dasar_pembentukan"		=> $dasar_pembentukan,
								"kode_wilayah"			=> $kode_wilayah,
								"kode_pos"				=> $kode_pos,
								"luas"					=> $luas,
								"batas_utara"			=> $batas_utara,
								"batas_selatan"			=> $batas_selatan,
								"batas_barat"			=>$batas_barat,
								"batas_timur"			=> $batas_timur,
								"jarak_dari_kecamatan"	=> $jarak_dari_kecamatan,
								"jarak_dari_kota"		=> $jarak_dari_kota,
								"jarak_dari_ibukota_kota"	=>$jarak_dari_ibukota_kota,
								"jarak_dari_ibukota_prov"	=>$jarak_dari_ibukota_prov,
								
						
								);

		$this->view->pendaftaranInsert = $this->pendaftaran_serv->pendaftaranInsert($dataMasukan);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->pendaftaranInsert;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');

	}
	
	public function pendaftaranupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id								= $_POST['id'];
		$jenisForm						= $_POST['jenisForm'];

		$kd_kel							= $_POST['kd_kel'];
		$tahun_pembentukan				= $_POST['tahun_pembentukan'];
		$dasar_pembentukan				= $_POST['dasar_pembentukan'];
		$kode_wilayah					= $_POST['kode_wilayah'];
		$kode_pos						= $_POST['kode_pos'];
		


		$dataMasukan	= array("kd_kel"				=> $kd_kel,
								"tahun_pembentukan"		=> $tahun_pembentukan,
								"dasar_pembentukan"		=> $dasar_pembentukan,
								"kode_wilayah"			=> $kode_wilayah,
								"kode_pos"				=> $kode_pos
								);
								
		$this->view->pendaftaranUpdate = $this->pendaftaran_serv->pendaftaranUpdate($dataMasukan);
		// var_dump($dataMasukan);
		// var_dump($this->view->pendaftaranUpdate);
		$this->Logfile->createLog($this->view->kelurahan, "Ubah data", $n_nama." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Umum Pendaftaran";
		$this->view->hasil = $this->view->pendaftaranUpdate;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');
	}



	
	public function pendaftaranhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];

		$dataMasukan = array(
							"id" => $id,
							"kd_kel"=> $kd_kel,
							"bulan"=> $bulan,
							"tahun"=> $tahun,
							);

		$this->view->pendaftaranUpdate = $this->pendaftaran_serv->pendaftaranHapus($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Hapus data pendaftaran user", $n_pendaftaran." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "USER";
		$this->view->hasil = $this->view->pendaftaranUpdate;
		
		$this->pendaftaranlistAction();
		$this->render('pendaftaranlist');
	}

	public function cetakkartupdfAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];
		$this->view->t_awal			= $_REQUEST['t_awal'];
		$this->view->t_akhir		= $_REQUEST['t_akhir'];
		
		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];

		$this->view->detailPasien	= $this->pendaftaran_serv->detailPendaftaranById($this->view->id);
		$this->view->medrecList		= $this->pendaftaran_serv->medrecList($this->view->detailPasien['kode_pasien']);
	}



	public function getKabAction(){
		$this->view->list		=  $this->ref_serv->getKabByPropList(trim($_REQUEST["propinsi"]));
	}

}
?>