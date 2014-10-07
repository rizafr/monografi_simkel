<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/kelurahan/Rekamkelurahan_Service.php";
require_once "service/kelurahan/Pendaftaran_Service.php";
require_once "service/adm/Referensi_Service.php";

require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "share/oa_dec_cur_conv.php";

class Kelurahan_RekamkelurahanController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->report_server = $registry->get('report_server'); 
		$this->view->basePath = $registry->get('basepath'); 
		$this->view->baseData = "../etc/data/kelurahan/";	

		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->rekamkelurahan  = 'cdr';
	   
		$this->rekamkelurahan_serv = Rekamkelurahan_Service::getInstance();
		$this->pendaftaran_serv = Pendaftaran_Service::getInstance();
		$this->ref_serv = Referensi_Service::getInstance();

		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssorekamkelurahan = new Zend_Session_Namespace('ssorekamkelurahan');
	    $this->iduser =$ssorekamkelurahan->user_id;
	   // $this->view->t_tensiuser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function rekamkelurahanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('rekamkelurahanjs');
    }
	
	//menampilkan data kelurahan
	public function rekamkelurahanlistAction()
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

		$this->view->kd_kel = $_REQUEST['kd_kel'];
		$this->view->bulan = $_REQUEST['bulan'];
		$this->view->tahun = $_REQUEST['tahun'];

		$detailKelurahan = $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel, $this->view->bulan, $this->view->tahun);
		$this->view->detailKelurahan = $detailKelurahan;
		
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$sortBy			= 'n_rekamkelurahan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $this->view->kategoriCari,
							"katakunciCari" => $this->view->carii,
							"kd_kel"	=> $_REQUEST['kd_kel'],
							"sortBy"		=> $sortBy,
							"sort"			=> $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totrekamkelurahanlist = $this->rekamkelurahan_serv->carirekamkelurahanlist($dataMasukan,0,0,0);
		$this->view->rekamkelurahanlist = $this->rekamkelurahan_serv->carirekamkelurahanlist($dataMasukan,$currentPage, $numToDisplay,$this->view->totrekamkelurahanlist);		
		//var_dump($this->view->rekamkelurahanlist);
		//exit();
	}
	
	//menampilkan form insert
	public function rekamkelurahandataAction(){
		$ssogroup = new Zend_Session_Namespace('ssogroup');	

		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$this->view->id				= $_REQUEST['id'];
		$this->view->kd_kel			= $_REQUEST['kd_kel'];
		
		$this->view->kd_kel	 = $_REQUEST['kd_kel'];
		$this->view->bulan 	 = $_REQUEST['bulan'];
		$this->view->tahun 	 = $_REQUEST['tahun'];

		$this->view->detailKelurahan	= $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel, $this->view->bulan, $this->view->tahun);
	
		$this->view->agamaList			= $this->ref_serv->getAgamaList();
		$this->view->statusList			= $this->ref_serv->getStatusList();
		$this->view->propinsiList		= $this->ref_serv->getPropinsiList();
		$this->view->goldarList			= $this->ref_serv->getGoldarList();
		$this->view->klasifikasiList	= $this->ref_serv->getKlasifikasiList();
		$this->view->tindakanList		= $this->ref_serv->getTindakanList();
		$this->view->detailRekamkelurahan	= $this->rekamkelurahan_serv->detailRekamkelurahanById($this->view->id, $this->view->bulan, $this->view->tahun);
	}
	
	//insert data
	public function rekamkelurahanAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$this->view->c_group =$ssogroup->c_group;
		$this->view->user_id =$ssogroup->user_id;

		$this->view->kd_kel	 = $_REQUEST['kd_kel'];
		$this->view->bulan 	 = $_REQUEST['bulan'];
		$this->view->tahun 	 = $_REQUEST['tahun'];

		$this->view->detailKelurahan	= $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel, $this->view->bulan, $this->view->tahun);
	
		//umum
		$tahun						= $_POST['tahun'];
		$bulan						= $_POST['bulan'];
		$kd_kel						= $_POST['kd_kel'];
		$tipologi					= $_POST['tipologi'];
		$jml_jiwa					= $_POST['jml_jiwa'];
		$jml_kk						= $_POST['jml_kk'];
		$jml_laki					= $_POST['jml_laki'];
		$jml_perempuan				= $_POST['jml_perempuan'];
		$jml_0_15					= $_POST['jml_0_15'];
		$jml_15_65					= $_POST['jml_15_65'];
		$jml_65_keatas				= $_POST['jml_65_keatas'];
		$jml_pns					= $_POST['jml_pns'];
		$jml_abri					= $_POST['jml_abri'];
		$jml_swasta					= $_POST['jml_swasta'];
		$jml_wiraswasta				= $_POST['jml_wiraswasta'];
		$jml_tani					= $_POST['jml_tani'];
		$jml_pertukangan			= $_POST['jml_pertukangan'];
		$jml_buruh_tani				= $_POST['jml_buruh_tani'];
		$jml_pensiunan				= $_POST['jml_pensiunan'];
		$jml_nelayan				= $_POST['jml_nelayan'];
		$jml_pemulung				= $_POST['jml_pemulung'];
		$jml_jasa					= $_POST['jml_jasa'];
		$jml_lulusan_tk				= $_POST['jml_lulusan_tk'];
		$jml_lulusan_sd				= $_POST['jml_lulusan_sd'];		
		$jml_lulusan_smp			= $_POST['jml_lulusan_smp'];
		$jml_lulusan_sma			= $_POST['jml_lulusan_sma'];
		$jml_lulusan_diploma		= $_POST['jml_lulusan_diploma'];
		$jml_lulusan_sarjana		= $_POST['jml_lulusan_sarjana'];
		$jml_lulusan_pascasarjana	= $_POST['jml_lulusan_pascasarjana'];
		$jml_lulusan_pontren		= $_POST['jml_lulusan_pontren'];
		$jml_lulusan_keagamaan		= $_POST['jml_lulusan_keagamaan'];
		$jml_lulusan_slb			= $_POST['jml_lulusan_slb'];
		$jml_lulusan_kursus			= $_POST['jml_lulusan_kursus'];
		$jml_lulusan_miskin			= $_POST['jml_lulusan_miskin'];
		$jml_jiwa_kk				= $_POST['jml_jiwa_kk'];
		$umr						= $_POST['umr'];
		$sarana_kantor				= $_POST['sarana_kantor'];
		$sarana_puskesmas			= $_POST['sarana_puskesmas'];
		$sarana_jml_posyandu		= $_POST['sarana_jml_posyandu'];
		$sarana_jml_poliklinik		= $_POST['sarana_jml_poliklinik'];
		$sarana_jml_paud			= $_POST['sarana_jml_paud'];
		$sarana_jml_tk				= $_POST['sarana_jml_tk'];		
		$sarana_jml_sd				= $_POST['sarana_jml_sd'];
		$sarana_jml_smp				= $_POST['sarana_jml_smp'];
		$sarana_jml_sma				= $_POST['sarana_jml_sma'];
		$sarana_jml_pt				= $_POST['sarana_jml_pt'];
		$sarana_jml_masjid			= $_POST['sarana_jml_masjid'];
		$sarana_jml_mushola			= $_POST['sarana_jml_mushola'];
		$sarana_jml_gereja			= $_POST['sarana_jml_gereja'];
		$sarana_jml_pura			= $_POST['sarana_jml_pura'];
		$sarana_jml_vihara			= $_POST['sarana_jml_vihara'];
		$sarana_jml_klenteng		= $_POST['sarana_jml_klenteng'];
		$sarana_jml_olahraga		= $_POST['sarana_jml_olahraga'];
		$sarana_jml_kesenian		= $_POST['sarana_jml_kesenian'];
		$sarana_jml_balai_pertemuan	= $_POST['sarana_jml_balai_pertemuan'];
		$sarana_jml_lainnya			= $_POST['sarana_jml_lainnya'];
		
		//personil
		$nama_lurah					= $_POST['nama_lurah'];
		$nip_lurah					= $_POST['nip_lurah'];
		$gol_lurah					= $_POST['gol_lurah'];
		$id_pendidikan_lurah		= $_POST['id_pendidikan_lurah'];
		$tmt_jabatan_lurah			= $_POST['tmt_jabatan_lurah'];
		$riwayat_jabatan1			= $_POST['riwayat_jabatan1'];
		$id_jenkel					= $_POST['id_jenkel'];
		$nama_seklur				= $_POST['nama_seklur'];
		$nip_seklur					= $_POST['nip_seklur'];
		$gol_seklur					= $_POST['gol_seklur'];
		$id_pendidikan_seklur		= $_POST['id_pendidikan_seklur'];
		$tmt_jabatan_seklur			= $_POST['tmt_jabatan_seklur'];
		$riwayat_jabatan1_seklur	= $_POST['riwayat_jabatan1_seklur'];
		$id_jenkel_seklur			= $_POST['id_jenkel_seklur'];
		$jumlah_aparat_gol1			= $_POST['jumlah_aparat_gol1'];
		$jumlah_aparat_gol2			= $_POST['jumlah_aparat_gol2'];
		$jumlah_aparat_gol3			= $_POST['jumlah_aparat_gol3'];
		$jumlah_aparat_gol4			= $_POST['jumlah_aparat_gol4'];
		
		//kewenangan
		$jml_urusan					= $_POST['jml_urusan'];
		$jml_urusan_wajib			= $_POST['jml_urusan_wajib'];
		$jml_urusan_pilihan			= $_POST['jml_urusan_pilihan'];
		$bidang_urusan_wajib		= $_POST['bidang_urusan_wajib'];
		$bidang_urusan_pilihan		= $_POST['bidang_urusan_pilihan'];
		$urusan_wajib				= $_POST['urusan_wajib'];
		$urusan_pilihan				= $_POST['urusan_pilihan'];
		$jml_program_kelurahan		= $_POST['jml_program_kelurahan'];
		
		//keuangan
		$anggaran_apbd				= $_POST['anggaran_apbd'];
		$is_skpd					= $_POST['is_skpd'];
		$bantuan_pusat				= $_POST['bantuan_pusat'];
		$bantuan_prov				= $_POST['bantuan_prov'];
		$bantuan_kota				= $_POST['bantuan_kota'];
		$hibah						= $_POST['hibah'];
		$sumbangan					= $_POST['sumbangan'];
		$swadaya					= $_POST['swadaya'];
		
		//kelembagaan
		$lpm_jml_pengurus			= $_POST['lpm_jml_pengurus'];
		$lpm_jml_anggota			= $_POST['lpm_jml_anggota'];
		$lpm_jml_keg_perbulan		= $_POST['lpm_jml_keg_perbulan'];
		$lpm_jml_dana				= $_POST['lpm_jml_dana'];
		$pkk_jml_pengurus			= $_POST['pkk_jml_pengurus'];
		$pkk_jml_anggota			= $_POST['pkk_jml_anggota'];
		$pkk_jml_keg_perbulan		= $_POST['pkk_jml_keg_perbulan'];
		$pkk_jml_buku_administrasi	= $_POST['pkk_jml_buku_administrasi'];
		$taruna_jml					= $_POST['taruna_jml'];
		$taruna_jenis				= $_POST['taruna_jenis'];
		$taruna_jml_pengurus		= $_POST['taruna_jml_pengurus'];
		$jml_rw						= $_POST['jml_rw'];
		$jml_rt						= $_POST['jml_rt'];
		$rata_penghasilan_rw		= $_POST['rata_penghasilan_rw'];
		$rata_penghasilan_rt		= $_POST['rata_penghasilan_rt'];
		$jml_lembaga_lain			= $_POST['jml_lembaga_lain'];
		
		//trantib
		$jml_anggota_linmas			= $_POST['jml_anggota_linmas'];
		$jml_pos_kamling			= $_POST['jml_pos_kamling'];
		$jml_ops_penertiban			= $_POST['jml_ops_penertiban'];
		$jml_pencurian				= $_POST['jml_pencurian'];
		$jml_perkosaan				= $_POST['jml_perkosaan'];
		$jml_kenakalan_remaja		= $_POST['jml_kenakalan_remaja'];
		$jml_pembunuhan				= $_POST['jml_pembunuhan'];
		$jml_perampokan				= $_POST['jml_perampokan'];
		$jml_penipuan				= $_POST['jml_penipuan'];
		$jml_bencana				= $_POST['jml_bencana'];
		$jml_pos_bencana			= $_POST['jml_pos_bencana'];
		$jml_pembalakan_liar		= $_POST['jml_pembalakan_liar'];
		$jml_pos_hutan_lindung		= $_POST['jml_pos_hutan_lindung'];
		$n_hemoglobin				= $_POST['n_hemoglobin'];
		$n_urin						= $_POST['n_urin'];
		$n_faeces					= $_POST['n_faeces'];
		$n_thalassimia				= $_POST['n_thalassimia'];
		$n_diagnosa					= $_POST['n_diagnosa'];
		
		$datamasukanumum = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"tipologi"	           	 	=> $tipologi,
				"jml_jiwa"	            	=> $jml_jiwa,
				"jml_kk"	            	=> $jml_kk,
				"jml_laki"	       			=> $jml_laki,
				"jml_perempuan"	        	=> $jml_perempuan,
				"jml_0_15"         			=> $jml_0_15,
				"jml_15_65"	            	=> $jml_15_65,
				"jml_65_keatas"        		=> $jml_65_keatas,
				"jml_pns"					=> $jml_pns,
				"jml_abri"					=> $jml_abri,
				"jml_swasta"        		=> $jml_swasta,
				"jml_wiraswasta"        	=> $jml_wiraswasta,
				"jml_tani"					=> $jml_tani,
				"jml_pertukangan"			=> $jml_pertukangan,
				"jml_buruh_tani"			=> $jml_buruh_tani,
				"jml_pensiunan"				=> $jml_pensiunan,
				"jml_nelayan"				=> $jml_nelayan,
				"jml_pemulung"				=> $jml_pemulung,
				"jml_jasa"					=> $jml_jasa,
				"jml_lulusan_tk"			=> $jml_lulusan_tk,
				"jml_lulusan_sd"			=> $jml_lulusan_sd,
				"jml_lulusan_smp"			=> $jml_lulusan_smp,
				"jml_lulusan_sma"			=> $jml_lulusan_sma,
				"jml_lulusan_diploma"		=> $jml_lulusan_diploma,
				"jml_lulusan_sarjana"		=> $jml_lulusan_sarjana,
				"jml_lulusan_pascasarjana"	=> $jml_lulusan_pascasarjana,
				"jml_lulusan_pontren"		=> $jml_lulusan_pontren,
				"jml_lulusan_keagamaan"		=> $jml_lulusan_keagamaan,
				"jml_lulusan_slb"			=> $jml_lulusan_slb,
				"jml_lulusan_kursus"		=> $jml_lulusan_kursus,
				"jml_lulusan_miskin"		=> $jml_lulusan_miskin,
				"jml_jiwa_kk"				=> $jml_jiwa_kk,
				"umr"						=> $umr,
				"sarana_kantor"				=> $sarana_kantor,
				"sarana_puskesmas"			=> $sarana_puskesmas,
				"sarana_jml_posyandu"		=> $sarana_jml_posyandu,
				"sarana_jml_poliklinik"		=> $sarana_jml_poliklinik,
				"sarana_jml_paud"			=> $sarana_jml_paud,
				"sarana_jml_tk"				=> $sarana_jml_tk,
				"sarana_jml_sd"				=> $sarana_jml_sd,
				"sarana_jml_smp"			=> $sarana_jml_smp,
				"sarana_jml_sma"			=> $sarana_jml_sma,
				"sarana_jml_pt"				=> $sarana_jml_pt,
				"sarana_jml_masjid"			=> $sarana_jml_masjid,
				"sarana_jml_mushola"		=> $sarana_jml_mushola,
				"sarana_jml_gereja"			=> $sarana_jml_gereja,
				"sarana_jml_pura"			=> $sarana_jml_pura,
				"sarana_jml_vihara"			=> $sarana_jml_vihara,
				"sarana_jml_klenteng"		=> $sarana_jml_klenteng,
				"sarana_jml_olahraga"		=> $sarana_jml_olahraga,
				"sarana_jml_kesenian"		=> $sarana_jml_kesenian,
				"sarana_jml_balai_pertemuan"=> $sarana_jml_balai_pertemuan,
				"sarana_jml_lainnya"		=> $sarana_jml_lainnya);
		
		$datamasukanpersonil = array("tahun" => $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"nama_lurah"	           	=> $nama_lurah,
				"nip_lurah"	            	=> $nip_lurah,
				"gol_lurah"	            	=> $gol_lurah,
				"id_pendidikan_lurah"	    => $id_pendidikan_lurah,
				"tmt_jabatan_lurah"	        => $tmt_jabatan_lurah,
				"riwayat_jabatan1"         	=> $riwayat_jabatan1,
				"id_jenkel"	            	=> $id_jenkel,
				
				"nama_lurah"	           	=> $nama_seklur,
				"nip_lurah"	            	=> $nip_seklur,
				"gol_lurah"	            	=> $gol_seklur,
				"id_pendidikan_lurah"	    => $id_pendidikan_seklur,
				"tmt_jabatan_lurah"	        => $tmt_jabatan_seklur,
				"riwayat_jabatan1"         	=> $riwayat_jabatan1_seklur,
				"id_jenkel"	            	=> $id_jenkel_seklur,
				
				"jumlah_aparat_gol1"	    => $jumlah_aparat_gol1,
				"jumlah_aparat_gol2"	    => $jumlah_aparat_gol2,
				"jumlah_aparat_gol3"	    => $jumlah_aparat_gol3,
				"jumlah_aparat_gol4"	    => $jumlah_aparat_gol4);
		
		$datamasukankewenangan = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"jml_urusan"	           	=> $jml_urusan,
				"jml_urusan_wajib"	        => $jml_urusan_wajib,
				"jml_urusan_pilihan"	    => $jml_urusan_pilihan,
				"bidang_urusan_wajib"	    => $bidang_urusan_wajib,
				"bidang_urusan_pilihan"	    => $bidang_urusan_pilihan,
				"urusan_wajib"         		=> $urusan_wajib,
				"urusan_pilihan"	        => $urusan_pilihan,
				"jml_program_kelurahan"	    => $jml_program_kelurahan);
		
		$datamasukankeuangan = array("tahun" 	=> $tahun,
				"bulan"					=> $bulan,
				"kd_kel"	           	=> $kd_kel,
				"anggaran_apbd"	        => $anggaran_apbd,
				"is_skpd"	        	=> $is_skpd,
				"bantuan_pusat"	    	=> $bantuan_pusat,
				"bantuan_prov"	    	=> $bantuan_prov,
				"bantuan_kota"	    	=> $bantuan_kota,
				"hibah"         		=> $hibah,
				"sumbangan"	      		=> $sumbangan,
				"swadaya"	   			=> $swadaya);
						
		$datamasukankelembagaan = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"lpm_jml_pengurus"	        => $lpm_jml_pengurus,
				"lpm_jml_anggota"	        => $lpm_jml_anggota,
				"lpm_jml_keg_perbulan"	    => $lpm_jml_keg_perbulan,
				"lpm_jml_dana"	    		=> $lpm_jml_dana,
				"pkk_jml_pengurus"	   		=> $pkk_jml_pengurus,
				"pkk_jml_anggota"         	=> $pkk_jml_anggota,
				"pkk_jml_keg_perbulan"	    => $pkk_jml_keg_perbulan,
				"pkk_jml_buku_administrasi"	=> $pkk_jml_buku_administrasi,
				"taruna_jml"	    		=> $taruna_jml,
				"taruna_jenis"	    		=> $taruna_jenis,
				"taruna_jml_pengurus"	    => $taruna_jml_pengurus,
				"jml_rw"	    			=> $jml_rw,
				"jml_rt"	    			=> $jml_rt,
				"rata_penghasilan_rw"	    => $rata_penghasilan_rw,
				"rata_penghasilan_rt"	    => $rata_penghasilan_rt,
				"jml_lembaga_lain"	    	=> $jml_lembaga_lain);
				
		$datamasukantrantib = array("tahun" => $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"jml_anggota_linmas"	    => $jml_anggota_linmas,
				"jml_pos_kamling"	        => $jml_pos_kamling,
				"jml_ops_penertiban"	    => $jml_ops_penertiban,
				"jml_pencurian"	    		=> $jml_pencurian,
				"jml_perkosaan"	   			=> $jml_perkosaan,
				"jml_kenakalan_remaja"      => $jml_kenakalan_remaja,
				"jml_pembunuhan"	    	=> $jml_pembunuhan,
				"jml_perampokan"			=> $jml_perampokan,
				"jml_penipuan"	    		=> $jml_penipuan,
				"jml_bencana"	    		=> $jml_bencana,
				"jml_pos_bencana"	    	=> $jml_pos_bencana,
				"jml_pembalakan_liar"	    => $jml_pembalakan_liar,
				"jml_pos_hutan_lindung"	    => $jml_pos_hutan_lindung);

				
		$this->view->umumInsert = $this->rekamkelurahan_serv->umumInsert($datamasukanumum);
		$this->view->personilInsert = $this->rekamkelurahan_serv->personilInsert($datamasukanpersonil);
		$this->view->kewenanganInsert = $this->rekamkelurahan_serv->kewenanganInsert($datamasukankewenangan);
		$this->view->keuanganInsert = $this->rekamkelurahan_serv->keuanganInsert($datamasukankeuangan);
		$this->view->kelembagaanInsert = $this->rekamkelurahan_serv->kelembagaanInsert($datamasukankelembagaan);
		$this->view->trantibInsert = $this->rekamkelurahan_serv->trantibInsert($datamasukantrantib);

		$this->view->proses = "1";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->umumInsert ." ". $this->view->personilInsert ." ". $this->view->kewenanganInsert ." ". 
							$this->view->keuanganInsert ." ". $this->view->kelembagaanInsert ." ". $this->view->trantibInsert;
		
		$this->rekamkelurahanlistAction();
		$this->render('rekamkelurahanlist');

	}
	
	public function rekamkelurahanupdateAction()
	{
		$ssogroup = new Zend_Session_Namespace('ssogroup');	
		$user_id =$ssogroup->user_id;

		$id					= $_POST['id'];
		$jenisForm			= $_POST['jenisForm'];
		$this->view->kd_kel	= $_REQUEST['kd_kel'];
		$this->view->bulan 	= $_REQUEST['bulan'];
		$this->view->tahun 	= $_REQUEST['tahun'];

		$this->view->detailKelurahan	= $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel, $this->view->bulan, $this->view->tahun);
	
		//umum
		$tahun						= $_POST['tahun'];
		$bulan						= $_POST['bulan'];
		$kd_kel						= $_POST['kd_kel'];
		$tipologi					= $_POST['tipologi'];
		$jml_jiwa					= $_POST['jml_jiwa'];
		$jml_kk						= $_POST['jml_kk'];
		$jml_laki					= $_POST['jml_laki'];
		$jml_perempuan				= $_POST['jml_perempuan'];
		$jml_0_15					= $_POST['jml_0_15'];
		$jml_15_65					= $_POST['jml_15_65'];
		$jml_65_keatas				= $_POST['jml_65_keatas'];
		$jml_pns					= $_POST['jml_pns'];
		$jml_abri					= $_POST['jml_abri'];
		$jml_swasta					= $_POST['jml_swasta'];
		$jml_wiraswasta				= $_POST['jml_wiraswasta'];
		$jml_tani					= $_POST['jml_tani'];
		$jml_pertukangan			= $_POST['jml_pertukangan'];
		$jml_buruh_tani				= $_POST['jml_buruh_tani'];
		$jml_pensiunan				= $_POST['jml_pensiunan'];
		$jml_nelayan				= $_POST['jml_nelayan'];
		$jml_pemulung				= $_POST['jml_pemulung'];
		$jml_jasa					= $_POST['jml_jasa'];
		$jml_lulusan_tk				= $_POST['jml_lulusan_tk'];
		$jml_lulusan_sd				= $_POST['jml_lulusan_sd'];		
		$jml_lulusan_smp			= $_POST['jml_lulusan_smp'];
		$jml_lulusan_sma			= $_POST['jml_lulusan_sma'];
		$jml_lulusan_diploma		= $_POST['jml_lulusan_diploma'];
		$jml_lulusan_sarjana		= $_POST['jml_lulusan_sarjana'];
		$jml_lulusan_pascasarjana	= $_POST['jml_lulusan_pascasarjana'];
		$jml_lulusan_pontren		= $_POST['jml_lulusan_pontren'];
		$jml_lulusan_keagamaan		= $_POST['jml_lulusan_keagamaan'];
		$jml_lulusan_slb			= $_POST['jml_lulusan_slb'];
		$jml_lulusan_kursus			= $_POST['jml_lulusan_kursus'];
		$jml_lulusan_miskin			= $_POST['jml_lulusan_miskin'];
		$jml_jiwa_kk				= $_POST['jml_jiwa_kk'];
		$umr						= $_POST['umr'];
		$sarana_kantor				= $_POST['sarana_kantor'];
		$sarana_puskesmas			= $_POST['sarana_puskesmas'];
		$sarana_jml_posyandu		= $_POST['sarana_jml_posyandu'];
		$sarana_jml_poliklinik		= $_POST['sarana_jml_poliklinik'];
		$sarana_jml_paud			= $_POST['sarana_jml_paud'];
		$sarana_jml_tk				= $_POST['sarana_jml_tk'];		
		$sarana_jml_sd				= $_POST['sarana_jml_sd'];
		$sarana_jml_smp				= $_POST['sarana_jml_smp'];
		$sarana_jml_sma				= $_POST['sarana_jml_sma'];
		$sarana_jml_pt				= $_POST['sarana_jml_pt'];
		$sarana_jml_masjid			= $_POST['sarana_jml_masjid'];
		$sarana_jml_mushola			= $_POST['sarana_jml_mushola'];
		$sarana_jml_gereja			= $_POST['sarana_jml_gereja'];
		$sarana_jml_pura			= $_POST['sarana_jml_pura'];
		$sarana_jml_vihara			= $_POST['sarana_jml_vihara'];
		$sarana_jml_klenteng		= $_POST['sarana_jml_klenteng'];
		$sarana_jml_olahraga		= $_POST['sarana_jml_olahraga'];
		$sarana_jml_kesenian		= $_POST['sarana_jml_kesenian'];
		$sarana_jml_balai_pertemuan	= $_POST['sarana_jml_balai_pertemuan'];
		$sarana_jml_lainnya			= $_POST['sarana_jml_lainnya'];
		
		//personil
		$nama_lurah					= $_POST['nama_lurah'];
		$nip_lurah					= $_POST['nip_lurah'];
		$gol_lurah					= $_POST['gol_lurah'];
		$id_pendidikan_lurah		= $_POST['id_pendidikan_lurah'];
		$tmt_jabatan_lurah			= $_POST['tmt_jabatan_lurah'];
		$riwayat_jabatan1			= $_POST['riwayat_jabatan1'];
		$id_jenkel					= $_POST['id_jenkel'];
		$nama_seklur				= $_POST['nama_seklur'];
		$nip_seklur					= $_POST['nip_seklur'];
		$gol_seklur					= $_POST['gol_seklur'];
		$id_pendidikan_seklur		= $_POST['id_pendidikan_seklur'];
		$tmt_jabatan_seklur			= $_POST['tmt_jabatan_seklur'];
		$riwayat_jabatan1_seklur	= $_POST['riwayat_jabatan1_seklur'];
		$id_jenkel_seklur			= $_POST['id_jenkel_seklur'];
		$jumlah_aparat_gol1			= $_POST['jumlah_aparat_gol1'];
		$jumlah_aparat_gol2			= $_POST['jumlah_aparat_gol2'];
		$jumlah_aparat_gol3			= $_POST['jumlah_aparat_gol3'];
		$jumlah_aparat_gol4			= $_POST['jumlah_aparat_gol4'];
		
		//kewenangan
		$jml_urusan					= $_POST['jml_urusan'];
		$jml_urusan_wajib			= $_POST['jml_urusan_wajib'];
		$jml_urusan_pilihan			= $_POST['jml_urusan_pilihan'];
		$bidang_urusan_wajib		= $_POST['bidang_urusan_wajib'];
		$bidang_urusan_pilihan		= $_POST['bidang_urusan_pilihan'];
		$urusan_wajib				= $_POST['urusan_wajib'];
		$urusan_pilihan				= $_POST['urusan_pilihan'];
		$jml_program_kelurahan		= $_POST['jml_program_kelurahan'];
		
		//keuangan
		$anggaran_apbd				= $_POST['anggaran_apbd'];
		$is_skpd					= $_POST['is_skpd'];
		$bantuan_pusat				= $_POST['bantuan_pusat'];
		$bantuan_prov				= $_POST['bantuan_prov'];
		$bantuan_kota				= $_POST['bantuan_kota'];
		$hibah						= $_POST['hibah'];
		$sumbangan					= $_POST['sumbangan'];
		$swadaya					= $_POST['swadaya'];
		
		//kelembagaan
		$lpm_jml_pengurus			= $_POST['lpm_jml_pengurus'];
		$lpm_jml_anggota			= $_POST['lpm_jml_anggota'];
		$lpm_jml_keg_perbulan		= $_POST['lpm_jml_keg_perbulan'];
		$lpm_jml_dana				= $_POST['lpm_jml_dana'];
		$pkk_jml_pengurus			= $_POST['pkk_jml_pengurus'];
		$pkk_jml_anggota			= $_POST['pkk_jml_anggota'];
		$pkk_jml_keg_perbulan		= $_POST['pkk_jml_keg_perbulan'];
		$pkk_jml_buku_administrasi	= $_POST['pkk_jml_buku_administrasi'];
		$taruna_jml					= $_POST['taruna_jml'];
		$taruna_jenis				= $_POST['taruna_jenis'];
		$taruna_jml_pengurus		= $_POST['taruna_jml_pengurus'];
		$jml_rw						= $_POST['jml_rw'];
		$jml_rt						= $_POST['jml_rt'];
		$rata_penghasilan_rw		= $_POST['rata_penghasilan_rw'];
		$rata_penghasilan_rt		= $_POST['rata_penghasilan_rt'];
		$jml_lembaga_lain			= $_POST['jml_lembaga_lain'];
		
		//trantib
		$jml_anggota_linmas			= $_POST['jml_anggota_linmas'];
		$jml_pos_kamling			= $_POST['jml_pos_kamling'];
		$jml_ops_penertiban			= $_POST['jml_ops_penertiban'];
		$jml_pencurian				= $_POST['jml_pencurian'];
		$jml_perkosaan				= $_POST['jml_perkosaan'];
		$jml_kenakalan_remaja		= $_POST['jml_kenakalan_remaja'];
		$jml_pembunuhan				= $_POST['jml_pembunuhan'];
		$jml_perampokan				= $_POST['jml_perampokan'];
		$jml_penipuan				= $_POST['jml_penipuan'];
		$jml_bencana				= $_POST['jml_bencana'];
		$jml_pos_bencana			= $_POST['jml_pos_bencana'];
		$jml_pembalakan_liar		= $_POST['jml_pembalakan_liar'];
		$jml_pos_hutan_lindung		= $_POST['jml_pos_hutan_lindung'];
		$n_hemoglobin				= $_POST['n_hemoglobin'];
		$n_urin						= $_POST['n_urin'];
		$n_faeces					= $_POST['n_faeces'];
		$n_thalassimia				= $_POST['n_thalassimia'];
		$n_diagnosa					= $_POST['n_diagnosa'];
		
		
		$datamasukanumum = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"tipologi"	           	 	=> $tipologi,
				"jml_jiwa"	            	=> $jml_jiwa,
				"jml_kk"	            	=> $jml_kk,
				"jml_laki"	       			=> $jml_laki,
				"jml_perempuan"	        	=> $jml_perempuan,
				"jml_0_15"         			=> $jml_0_15,
				"jml_15_65"	            	=> $jml_15_65,
				"jml_65_keatas"        		=> $jml_65_keatas,
				"jml_pns"					=> $jml_pns,
				"jml_abri"					=> $jml_abri,
				"jml_swasta"        		=> $jml_swasta,
				"jml_wiraswasta"        	=> $jml_wiraswasta,
				"jml_tani"					=> $jml_tani,
				"jml_pertukangan"			=> $jml_pertukangan,
				"jml_buruh_tani"			=> $jml_buruh_tani,
				"jml_pensiunan"				=> $jml_pensiunan,
				"jml_nelayan"				=> $jml_nelayan,
				"jml_pemulung"				=> $jml_pemulung,
				"jml_jasa"					=> $jml_jasa,
				"jml_lulusan_tk"			=> $jml_lulusan_tk,
				"jml_lulusan_sd"			=> $jml_lulusan_sd,
				"jml_lulusan_smp"			=> $jml_lulusan_smp,
				"jml_lulusan_sma"			=> $jml_lulusan_sma,
				"jml_lulusan_diploma"		=> $jml_lulusan_diploma,
				"jml_lulusan_sarjana"		=> $jml_lulusan_sarjana,
				"jml_lulusan_pascasarjana"	=> $jml_lulusan_pascasarjana,
				"jml_lulusan_pontren"		=> $jml_lulusan_pontren,
				"jml_lulusan_keagamaan"		=> $jml_lulusan_keagamaan,
				"jml_lulusan_slb"			=> $jml_lulusan_slb,
				"jml_lulusan_kursus"		=> $jml_lulusan_kursus,
				"jml_lulusan_miskin"		=> $jml_lulusan_miskin,
				"jml_jiwa_kk"				=> $jml_jiwa_kk,
				"umr"						=> $umr,
				"sarana_kantor"				=> $sarana_kantor,
				"sarana_puskesmas"			=> $sarana_puskesmas,
				"sarana_jml_posyandu"		=> $sarana_jml_posyandu,
				"sarana_jml_poliklinik"		=> $sarana_jml_poliklinik,
				"sarana_jml_paud"			=> $sarana_jml_paud,
				"sarana_jml_tk"				=> $sarana_jml_tk,
				"sarana_jml_sd"				=> $sarana_jml_sd,
				"sarana_jml_smp"			=> $sarana_jml_smp,
				"sarana_jml_sma"			=> $sarana_jml_sma,
				"sarana_jml_pt"				=> $sarana_jml_pt,
				"sarana_jml_masjid"			=> $sarana_jml_masjid,
				"sarana_jml_mushola"		=> $sarana_jml_mushola,
				"sarana_jml_gereja"			=> $sarana_jml_gereja,
				"sarana_jml_pura"			=> $sarana_jml_pura,
				"sarana_jml_vihara"			=> $sarana_jml_vihara,
				"sarana_jml_klenteng"		=> $sarana_jml_klenteng,
				"sarana_jml_olahraga"		=> $sarana_jml_olahraga,
				"sarana_jml_kesenian"		=> $sarana_jml_kesenian,
				"sarana_jml_balai_pertemuan"=> $sarana_jml_balai_pertemuan,
				"sarana_jml_lainnya"		=> $sarana_jml_lainnya);
		
		$datamasukanpersonil = array("tahun" => $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"nama_lurah"	           	=> $nama_lurah,
				"nip_lurah"	            	=> $nip_lurah,
				"gol_lurah"	            	=> $gol_lurah,
				"id_pendidikan_lurah"	    => $id_pendidikan_lurah,
				"tmt_jabatan_lurah"	        => $tmt_jabatan_lurah,
				"riwayat_jabatan1"         	=> $riwayat_jabatan1,
				"id_jenkel"	            	=> $id_jenkel,
				
				"nama_lurah"	           	=> $nama_seklur,
				"nip_lurah"	            	=> $nip_seklur,
				"gol_lurah"	            	=> $gol_seklur,
				"id_pendidikan_lurah"	    => $id_pendidikan_seklur,
				"tmt_jabatan_lurah"	        => $tmt_jabatan_seklur,
				"riwayat_jabatan1"         	=> $riwayat_jabatan1_seklur,
				"id_jenkel"	            	=> $id_jenkel_seklur,
				
				"jumlah_aparat_gol1"	    => $jumlah_aparat_gol1,
				"jumlah_aparat_gol2"	    => $jumlah_aparat_gol2,
				"jumlah_aparat_gol3"	    => $jumlah_aparat_gol3,
				"jumlah_aparat_gol4"	    => $jumlah_aparat_gol4);
		
		$datamasukankewenangan = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"jml_urusan"	           	=> $jml_urusan,
				"jml_urusan_wajib"	        => $jml_urusan_wajib,
				"jml_urusan_pilihan"	    => $jml_urusan_pilihan,
				"bidang_urusan_wajib"	    => $bidang_urusan_wajib,
				"bidang_urusan_pilihan"	    => $bidang_urusan_pilihan,
				"urusan_wajib"         		=> $urusan_wajib,
				"urusan_pilihan"	        => $urusan_pilihan,
				"jml_program_kelurahan"	    => $jml_program_kelurahan);
		
		$datamasukankeuangan = array("tahun" 	=> $tahun,
				"bulan"					=> $bulan,
				"kd_kel"	           	=> $kd_kel,
				"anggaran_apbd"	        => $anggaran_apbd,
				"is_skpd"	        	=> $is_skpd,
				"bantuan_pusat"	    	=> $bantuan_pusat,
				"bantuan_prov"	    	=> $bantuan_prov,
				"bantuan_kota"	    	=> $bantuan_kota,
				"hibah"         		=> $hibah,
				"sumbangan"	      		=> $sumbangan,
				"swadaya"	   			=> $swadaya);
						
		$datamasukankelembagaan = array("tahun" 	=> $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"lpm_jml_pengurus"	        => $lpm_jml_pengurus,
				"lpm_jml_anggota"	        => $lpm_jml_anggota,
				"lpm_jml_keg_perbulan"	    => $lpm_jml_keg_perbulan,
				"lpm_jml_dana"	    		=> $lpm_jml_dana,
				"pkk_jml_pengurus"	   		=> $pkk_jml_pengurus,
				"pkk_jml_anggota"         	=> $pkk_jml_anggota,
				"pkk_jml_keg_perbulan"	    => $pkk_jml_keg_perbulan,
				"pkk_jml_buku_administrasi"	=> $pkk_jml_buku_administrasi,
				"taruna_jml"	    		=> $taruna_jml,
				"taruna_jenis"	    		=> $taruna_jenis,
				"taruna_jml_pengurus"	    => $taruna_jml_pengurus,
				"jml_rw"	    			=> $jml_rw,
				"jml_rt"	    			=> $jml_rt,
				"rata_penghasilan_rw"	    => $rata_penghasilan_rw,
				"rata_penghasilan_rt"	    => $rata_penghasilan_rt,
				"jml_lembaga_lain"	    	=> $jml_lembaga_lain);
				
		$datamasukantrantib = array("tahun" => $tahun,
				"bulan"						=> $bulan,
				"kd_kel"	           	 	=> $kd_kel,
				"jml_anggota_linmas"	    => $jml_anggota_linmas,
				"jml_pos_kamling"	        => $jml_pos_kamling,
				"jml_ops_penertiban"	    => $jml_ops_penertiban,
				"jml_pencurian"	    		=> $jml_pencurian,
				"jml_perkosaan"	   			=> $jml_perkosaan,
				"jml_kenakalan_remaja"      => $jml_kenakalan_remaja,
				"jml_pembunuhan"	    	=> $jml_pembunuhan,
				"jml_perampokan"			=> $jml_perampokan,
				"jml_penipuan"	    		=> $jml_penipuan,
				"jml_bencana"	    		=> $jml_bencana,
				"jml_pos_bencana"	    	=> $jml_pos_bencana,
				"jml_pembalakan_liar"	    => $jml_pembalakan_liar,
				"jml_pos_hutan_lindung"	    => $jml_pos_hutan_lindung);
				
		$this->view->umumUpdate = $this->rekamkelurahan_serv->umumUpdate($datamasukanumum);
		$this->view->personilUpdate = $this->rekamkelurahan_serv->personilUpdate($datamasukanpersonil);
		$this->view->kewenanganUpdate = $this->rekamkelurahan_serv->kewenanganUpdate($datamasukankewenangan);
		$this->view->keuanganUpdate = $this->rekamkelurahan_serv->keuanganUpdate($datamasukankeuangan);
		$this->view->kelembagaanUpdate = $this->rekamkelurahan_serv->kelembagaanUpdate($datamasukankelembagaan);
		$this->view->trantibUpdate = $this->rekamkelurahan_serv->trantibUpdate($datamasukantrantib);

		$this->view->proses = "2";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->umumUpdate ." ". $this->view->personilUpdate ." ". $this->view->kewenanganUpdate ." ". 
						$this->view->keuanganUpdate ." ". $this->view->kelembagaanUpdate ." ". $this->view->trantibUpdate;
		
		$this->rekamkelurahanlistAction();
		$this->render('rekamkelurahanlist');
	}
	
	public function rekamkelurahanhapusAction()
	{
		$this->view->kategoriCari 	= $_REQUEST['kategoriCari']; 
		$this->view->carii 			= $_REQUEST['carii'];

		$this->view->jenisForm		= $_REQUEST['jenisForm'];
		$id							= $_REQUEST['id'];
		
		$this->view->kd_kel	= $_REQUEST['kd_kel'];
		$this->view->bulan 	= $_REQUEST['bulan'];
		$this->view->tahun 	= $_REQUEST['tahun'];

		$this->view->detailKelurahan	= $this->rekamkelurahan_serv->detailKelurahanByKode($this->view->kd_kel, $this->view->bulan, $this->view->tahun);
	
		$dataMasukan = array("id" => $id,
							"kd_kel" => $kd_kel,
							"bulan" => $bulan,
							"tahun" => $tahun);
		$this->view->rekamkelurahanUpdate = $this->rekamkelurahan_serv->rekamkelurahanHapus($dataMasukan);
		
		$this->view->proses = "3";	
		$this->view->keterangan = "Judul";
		$this->view->hasil = $this->view->rekamkelurahanUpdate;
		
		$this->rekamkelurahanlistAction();
		$this->render('rekamkelurahanlist');
	}

	public function kelurahanlistAction() {
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
							 
		$this->view->totKelurahanList 		= $this->pendaftaran_serv->cariPendaftaranList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage		= $itemPerPage;
		$this->view->kelurahanList 		= $this->pendaftaran_serv->cariPendaftaranList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totKelurahanList);
		
	}

	public function getKabAction(){
		$this->view->list		=  $this->ref_serv->getKabByPropList(trim($_REQUEST["propinsi"]));
	}

}
?>