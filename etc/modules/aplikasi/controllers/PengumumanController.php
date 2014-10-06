<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/aplikasi/Aplikasi_Pengumuman_Service.php";

class Aplikasi_PengumumanController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	   // $ssogroup = new Zend_Session_Namespace('ssogroup');
	   //echo "TEST ".$ssogroup->n_user_grp." ".$ssogroup->i_user." ".$ssogroup->i_peg_nip;
	   $this->user  = 'cdr';
	   // $this->username  = 'Yuliah';
	   //$this->nip  = strtoupper($this->id['nip']);
	   // $this->usernip  = '060046350';
	   // $this->kdorg = 'SK1201';
	 // $this->modul    = '5';
	 // $this->category = 'A';
		
		$this->pengumuman_serv = Aplikasi_Pengumuman_Service::getInstance();
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	
    public function indexAction() {
	   
    }
	
	public function pengumumanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('pengumumanjs');
    }
	
	//test OPen report
	//----------------------
	public function pengumumanlistAction()
	{
		$pageNumber 	= 1;
		$itemPerPage 	= 20;
		$kategoriCari 	= 'e_pengumuman';
		$katakunciCari 	= $_POST['cari'];
		$sortBy			= 'e_pengumuman';
		$sort			= 'asc';
		
		$dataMasukan = array("pageNumber" => $pageNumber,
							"itemPerPage" => $itemPerPage,
							"kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$this->view->currentPage = $pageNumber;
		$this->view->numToDisplay = $itemPerPage;		
		$this->view->pengumumanList = $this->pengumuman_serv->cariPengumumanList($dataMasukan);
	}
	
	public function pengumumanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iPengumuman = $_REQUEST['iPengumuman'];
		
		$this->view->detailPengumuman = $this->pengumuman_serv->detailPengumumanById($iPengumuman);
	}
	
	public function pengumumanAction()
	{
		$ePengumuman 	= $_POST['ePengumuman'];
		$cStatusaktif 	= $_POST['cStatusaktif'];
		$iEntry 		= $this->user;
		
		$dataMasukan = array("e_pengumuman" => $ePengumuman,
							"c_statusaktif" => $cStatusaktif,
							"i_entry" => $iEntry);
		
		$this->view->pengumumanInsert = $this->pengumuman_serv->pengumumanInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Pengumuman";
		$this->view->hasil = $hasil;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}
	
	public function pengumumanupdateAction()
	{
		$iPengumuman 		= $_POST['iPengumuman'];
		$ePengumuman 		= $_POST['ePengumuman'];
		$cStatusaktif 		= $_POST['cStatusaktif'];
		
		$iEntry 		= $this->user;
		
		$dataMasukan = array("i_pengumuman" => $iPengumuman,
							"e_pengumuman" => $ePengumuman,
							"c_statusaktif" => $cStatusaktif,
							"i_entry" => $iEntry);
		
		$this->view->pengumumanUpdate = $this->pengumuman_serv->pengumumanUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Pengumuman";
		$this->view->hasil = $hasil;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}
	
	public function pengumumanhapusAction()
	{
		$iPengumuman 		= $_REQUEST['iPengumuman'];
		
		$dataMasukan = array("i_pengumuman" => $iPengumuman);
		
		$this->view->pengumumanUpdate = $this->pengumuman_serv->pengumumanHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Pengumuman";
		$this->view->hasil = $hasil;
		
		$this->pengumumanlistAction();
		$this->render('pengumumanlist');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//Daftar Personal
	//==============
	/*public function personalsearchAction() {  
		$currentPage = $_REQUEST['currentPage']; 

		$nip = $_POST['nip'];
		$nama = $_POST['nama'];
		$stat = $_POST['stat'];
		$cSatminkal = $_POST['satminkal'];
		
		$param1 = $_REQUEST['param1']; 
		$param2 = $_REQUEST['param2']; 
		$param3 = $_REQUEST['param3']; 
		$param4 = $_REQUEST['param4']; 
		$param5 = $_REQUEST['param5']; 
		$param6 = $_REQUEST['param6']; 
		
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		}

		$numToDisplay = 500;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		$this->view->totPersonal = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 0, 0, $cSatminkal);
		$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, $currentPage, $numToDisplay, $cSatminkal );
		$this->view->satminkalList = $this->auditor_serv->getSatminkalList();
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
	}*/
	public function personCetakAllFPDFAction(){
		$txt_cari1 = $_REQUEST['txtcari1'];
		$txt_cari2 = $_REQUEST['txtcari2'];
		$txt_cari3 = $_REQUEST['txtcari3'];
		$cSatminkal = $_REQUEST['satminkal'];
		$this->view->txtcari1 = $txt_cari1;
		$this->view->txtcari2 = $txt_cari2;
		$this->view->txtcari3 = $txt_cari3;
	$this->view->personalList = $this->auditor_serv->getPL($txt_cari1, $txt_cari2, $txt_cari3,$cSatminkal);
	
	}
	public function personSatKerCetakAllFPDFAction(){
		$txt_cari1 = $_REQUEST['txtcari1'];
		$txt_cari2 = $_REQUEST['txtcari2'];
		$txt_cari3 = $_REQUEST['txtcari3'];
		$cSatminkal = $_REQUEST['satminkal'];
		$this->view->txtcari1 = $txt_cari1;
		$this->view->txtcari2 = $txt_cari2;
		$this->view->txtcari3 = $txt_cari3;
		$this->view->personalList = $this->auditor_serv->getPL($nip, $nama, $stat, $cSatminkal );
	}
	public function personalsearchAction() {  
		$currentPage = $_REQUEST['currentPage']; 

		$nip = $_POST['nip'];
		$nama = $_POST['nama'];
		$stathide = $_POST['stathide'];
		$this->view->stat = $_POST['stat'];
		//echo "Stat : ".$stathide;
		//if(empty($_REQUEST['satker']) && empty($_REQUEST['param4']) && empty($stathide)){
		if($_REQUEST['satker'] == '' || $_REQUEST['param4'] == '' || $stathide == '' 
		|| $_REQUEST['satker'] == 'ADT' || $_REQUEST['satker'] == 'TAH' || $_REQUEST['satker'] == 'SKT'
		|| $_REQUEST['satker'] == 'NON'){
			$stat = $_POST['stat'];
			$this->view->menutab="datapersonil";
			$this->view->stater = $stat;
		}
		if($_REQUEST['satker'] == 'SKR' || $_REQUEST['param4'] == 'SKR'){		    
			$stat = $_REQUEST['satker'];
			$this->view->menutab="datasatker";
			if(!empty($_REQUEST['param4'])){
			$stat = $_REQUEST['param4'];
			}
			$this->view->stater = $stat;
		}
		if($stathide == 'SKR'){
		  $stat = $stathide;
		  $this->view->menutab="datasatker";
		  $this->view->stater = $stat;
		}
		
		$cSatminkal = $_POST['satminkal'];
		
		$param1 = $_REQUEST['param1']; 
		$param2 = $_REQUEST['param2']; 
		$param3 = $_REQUEST['param3']; 
		$param4 = $_REQUEST['param4']; 
		$param5 = $_REQUEST['param5']; 
		$param6 = $_REQUEST['param6']; 

		
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		}

		$numToDisplay = 500;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		
		$this->view->totPersonal = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 0, 0, $cSatminkal);
		$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, $currentPage, $numToDisplay, $cSatminkal );
		$this->view->satminkalList = $this->auditor_serv->getSatminkalList();
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
	}	
	
	// Tambah Data Personal
	//====================
	public function personaldatapokokAction()
	{
		
			
		//echo "nip=$nip";
		$userid = $this->user;
		$this->view->perintah = $_REQUEST['perintah'];
		
		//echo $this->view->perintah;
		if($this->view->perintah == "UPDATE")
		{
			$nip = $_REQUEST['nip'];
		}
		
		$this->view->nip=$nip;
		
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$this->view->propinsiList = $this->auditor_serv->getPropinsiListAll();
		$prop1 = $this->view->personalDetail['a_peg_propinsi'];
		$this->view->kabupatenList = $this->auditor_serv->getKabupatenByProp($prop1);
		$this->view->kelurahanList = $this->auditor_serv->getKelurahan();
		
		$this->view->perintahproses = $_POST['perintahproses'];
		
		if($this->view->perintahproses)
		{
			//if($this->view->periintahproses == "SIMPAN")
			//{
				$nip = $_POST['nip'];
				$nipH = $_POST['nipH'];
				$namaPegawai = $_POST['namaPegawai'];
			// }
			// else
			// {
				// $nip = $_POST['nipH'];
				// $namaPegawai = $_POST['namaPegawaiH'];
			// }
			$gelarDpn = $_POST['gelarDpn'];
			$gelarBlk = $_POST['gelarBlk'];
			
			$tmpLahir = $_POST['tmpLahir'];
			
			$hrLahir = $_POST['hrLahir'];
			$blnLahir = $_POST['blnLahir'];
			$thnLahir = $_POST['thnLahir'];
			
			if(!$hrLahir)
			{
				$tanggalLahir = null;
			}
			else
			{
				if(!$blnLahir)
				{
					$tanggalLahir = null;
				}
				else
				{
					if(!$thnLahir)
					{
						$tanggalLahir = null;
					}
					else
					{
						$tanggalLahir = "$thnLahir-$blnLahir-$hrLahir";
					}
				}
			}
			
			$jenisKelamin = $_POST['jenisKelamin'];
			$stsNikah = $_POST['stsNikah'];
			$agama = $_POST['agama'];
			$hrMasuk = $_POST['hrMasuk'];
			$blnMasuk = $_POST['blnMasuk'];
			$thnMasuk = $_POST['thnMasuk'];
			$tanggalMasuk = "$thnMasuk-$blnMasuk-$hrMasuk";
			
			$alamat = $_POST['alamat'];
			$rt = $_POST['rt'];
			$rw = $_POST['rw'];
			$kelurahan = $_POST['kelurahan'];
			$kecamatan = $_POST['kecamatan'];
			$propinsi = $_POST['propinsi'];
			$kabupaten = $_POST['kabupaten'];
			$kodePos = $_POST['kodePos'];
			$teleponRumah = $_POST['teleponRumah'];
			$tlpGenggam = $_POST['tlpGenggam'];
			$email = $_POST['email'];
			$email2 = $_POST['email2'];
			
			$paramDataPokok = array("nip" => $nip,
									"nipH" => $nipH,
									"namaPegawai" => $namaPegawai,
									"gelarDpn" => $gelarDpn,
									"gelarBlk" => $gelarBlk,
									"tmpLahir" => $tmpLahir,
									"tanggalLahir" => $tanggalLahir,
									"jenisKelamin" => $jenisKelamin,
									"stsNikah" => $stsNikah,
									"agama" => $agama,
									"tanggalMasuk" => $tanggalMasuk,
									"alamat" => $alamat,
									"rt" => $rt,
									"rw" => $rw,
									"kelurahan" => $kelurahan,
									"kecamatan" => $kecamatan,
									"propinsi" => $propinsi,
									"kabupaten" => $kabupaten,
									"kodePos" => $kodePos,
									"teleponRumah" => $teleponRumah,
									"tlpGenggam" => $tlpGenggam,
									"email" => $email,
									"email2" => $email2,
									"userid" =>$userid
									);
										
			//var_dump($paramDataPokok);
			
			
			if((isset($_FILES['foto']['error']) && $_FILES['foto'] == 0) || (!empty($_FILES['foto']['tmp_name']) && $_FILES['foto']['tmp_name'] != 'none'))
			{
				$size = filesize($_FILES['foto']['tmp_name']);
				if (($size != 0) && ($size > 2048000)) 
				{ ?> 
					<script>alert('File Terlalu Besar');</script>
				<? 
				}
				else
				{
					$fileName = $_FILES['foto']['name'];
					$extention = substr($fileName, -3, 3);
					
					if (($extention == "jpg") || ($extention == "JPG")) 
					{
						$destDir = "..\etc\data\auditor\foto\\";
						$newFileName = "$nip.jpg";
						$tujuan = $destDir.$newFileName;
						
						$file = $_FILES['foto']['tmp_name'];
						
						$prosesUpload = move_uploaded_file($file,$tujuan);
						
						if($prosesUpload == "1")
						{
							if($this->view->perintahproses == "SIMPAN")
							{
								$hasil = $this->auditor_serv->insertPersonalDataPokok($paramDataPokok);	
								$proses = "1";
							}
							else
							{
								$hasil = $this->auditor_serv->updatePersonalDataPokok($paramDataPokok);
								$proses = "2";
							}
							//$hasil = "sukses";
							//var_dump($paramDataPokok);
							if($hasil == "sukses")
							{
								$keterangan = "Data Pokok Personal";
								$status = $hasil;
							}
							else
							{
								$keterangan = "Data Pokok Personal";
								$status = $hasil;
								if(file_exists($tujuan))
								{
									unlink($tujuan);
								}	
							}
						}
						else
						{
							$proses = "1";
							$keterangan = "File";
							$status = "Gagal";
						}
						$this->view->status = $status;
						$this->view->proses = $proses;
						$this->view->keterangan = $keterangan;

						unset($_POST);
						/* $this->personalsearchAction();
						$this->render('personalsearch'); */
						unset($_REQUEST);
						$_REQUEST = array("perintah" => "UPDATE",
										"nip" => $nip);
						$this->personaldatapokokAction();
					}
					else
					{
					?> 
						<script>alert('File Harus Dalam Format jpg');</script>
					<? 
						$this->render('personaldatapokok');
					}
				}
			}
			else
			{
				if($this->view->perintahproses == "SIMPAN")
				{
					//echo "hhhhhhhhhh";
					$hasil = $this->auditor_serv->insertPersonalDataPokok($paramDataPokok);
					$proses = "1";
					$status = $hasil;
				}
				else
				{ 
//echo "iiiiiiii";
					$hasil = $this->auditor_serv->updatePersonalDataPokok($paramDataPokok);
					$proses = "2";
					$status = $hasil;
					$destDir = "..\etc\data\auditor\foto\\";
					$newFileName1 = "$nipH.jpg";
					$tujuan1 = $destDir.$newFileName1;
					$newFileName = "$nip.jpg";
					$tujuan = $destDir.$newFileName;
					if(file_exists($tujuan1))
					{
						
						rename($tujuan1, $tujuan);
					}
				}
					//$hasil = "sukses";
					//var_dump($paramDataPokok);
					if($hasil == "sukses")
					{
						$keterangan = "Data Pokok Personal";
						$status = $hasil;
					}
					else
					{
						$keterangan = "Data n'Pokok Personal";
						$status = $hasil;
						if(file_exists($tujuan))
						{
							unlink($tujuan);
						}	
					}
					
					$this->view->status = $status;
					$this->view->proses = $proses;
					$this->view->keterangan = $keterangan;

					unset($_POST);
					unset($_REQUEST);
					$_REQUEST = array("perintah" => "UPDATE",
									"nip" => $nip);
					$this->personaldatapokokAction();
					//$this->render('personalsearch');
				//}
				//var_dump($_REQUEST);
			}
		}
		
	}
	
	/* public function dataintiAction()
	{
		$nip = $_REQUEST['nip'];
		$this->view->nip=$nip;	
		//echo "nip=$nip";
		$userid = $this->user;
		$this->view->perintah = $_REQUEST['perintah'];
		
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$this->view->propinsiList = $this->auditor_serv->getPropinsiListAll();
		$prop1 = $this->view->personalDetail[0]['a_peg_propinsi'];
		$this->view->kabupatenList = $this->auditor_serv->getKabupatenByProp($prop1);
		$this->view->kelurahanList = $this->auditor_serv->getKelurahan();
		
		
		
	} */
	
	public function setkecamatanAction() {
		$kel = $_REQUEST['kelurahan'];
		
		$this->view->kecamatan = $this->auditor_serv->getKecamatan($kel);
	}
	
	public function listkabupatenpropinsiAction() {
		$perintah = $_POST['perintah'];
		
		$this->view->tujuan = $_REQUEST['tujuan'];
		if($perintah = "cari")
		{
			$paramCari = array("n_kabupaten" => $_POST['n_kabupaten'],
								"n_propinsi" => $_POST['n_propinsi']);
		}
		else
		{
			$paramCari = array();
		}
		$this->view->listkabupatenpropinsi = $this->auditor_serv->getListKabupatenPropinsi($paramCari);
	}
	
	public function hapuspersonaldatapokokAction()
	{
		$nip = $_REQUEST['nip'];
		
		$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1, '');
		$this->view->nmJenjangList = $this->auditor_serv->getPendidikanListAll();
		
		
		$paramDelete = array("nip"		=>$nip);
							  
		$hasil = $this->auditor_serv->deletePersonalDataPokok($paramDelete);
		
		$this->view->proses = "3";
		$this->view->keterangan = "Data Pokok Personal";
		$this->view->status = $hasil;
		
		if($this->view->status == "sukses")
		{
			$destDir = "..\etc\data\auditor\foto\\";
			$newFileName = "$nip.jpg";
			$tujuan = $destDir.$newFileName;
			
			if(file_exists($tujuan))
			{
				unlink($tujuan);
			}
		}
		$this->personalsearchAction();
		$this->render('personalsearch');
	}
	
	public function listkabupatenAction() {	
		//$cPropinsi = $_POST['cPropH'];
		$cProp = $_REQUEST['cPropinsi'];
		$cPropinsi = substr($cProp,0,2);
		if (substr($cProp,3,1) == '|') {
			$cPropinsi = substr($cProp,0,3);
		}
		else if (substr($cProp,4,1) == '|') {
			$cPropinsi = substr($cProp,0,4);			
		}
		$this->view->kabupatenList = $this->auditor_serv->getKabupatenByProp($cPropinsi);
	}
	
	//PENDIDIKAN
	//============
	public function pendidikanAction() {
		$user = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->nmJenjangList = $this->auditor_serv->getPendidikanListAll();
			
		
		$perintah = $_POST['perintahproses'];
		
		if($perintah == "SIMPAN")
		{
			$nmJenjang = $_POST['nmJenjang'];
//			$nip = 	$_POST['nipH'];
			
			$prmPendInsert = array("nip"		=>$nip,
			                    "kdjenjang"		=>$nmJenjang,
								"pendidikan"	=>$_POST['pendidikan'],
								"tempat"		=>$_POST['alamat'],
								"jurusan"		=>$_POST['njurusan'],
								"noIjazah"		=>$_POST['noIjazah'],
								"tglIjazah"		=>$_POST['thLulus'],
								"user"			=>$user);

			$hasil = $this->auditor_serv->insertPendidikan($prmPendInsert);
			
			$this->view->proses = "1";
			$this->view->keterangan = "Data Pendidikan";
			$this->view->status = $hasil;	
		}
		$this->view->pendList = $this->auditor_serv->getPendidikan($nip);
	}
	
	public function listperguruantinggiAction() {	
		$nama = $_POST['nama'];
		$kota = $_POST['kota'];
		$param = array("nama" => $nama,
					"kota" => $kota);
		$this->view->ptList = $this->auditor_serv->getPT($param);
	}
	
	function pendidikanupdateAction()
	{
		$user = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$kdjenjang = $_REQUEST['jenjang'];
		$lembaga = $_REQUEST['lembaga'];
		$jurusan = $_REQUEST['jurusan'];
		
		$this->view->perintah = $_REQUEST['perintah'];
		$prosesUpdate = $_POST['perintahUpdate'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->nmJenjangList = $this->auditor_serv->getPendidikanListAll();
		
		$this->view->pendByNipJenjang = $this->auditor_serv->getPendidikanByNipJenjang($nip,$kdjenjang,$lembaga,$jurusan);
		
		if($prosesUpdate == "UPDATE")
		{
			$nmJenjangH = $_POST['nmJenjangH'];
			$nmJenjang = $_POST['nmJenjang'];
			$jurusanH = $_POST['jurusanH'];
			
			$nip = 	$_POST['nipH'];
			
			$prmPendUpdate = array("nip"		=>$nip,
								"kdjenjangH"		=>$nmJenjangH,
								"kdjenjang"		=>$nmJenjang,
								"pendidikan"	=>$_POST['pendidikan'],
								"pendidikanH"	=>$_POST['pendidikanH'],
								"tempat"		=>$_POST['alamat'],
								"jurusan"		=>$_POST['njurusan'],
								"jurusanH"		=>$_POST['njurusanH'],
								"noIjazah"		=>$_POST['noIjazah'],
								"tglIjazah"		=>$_POST['thLulus'],
								"user"			=>$user);

			$hasil = $this->auditor_serv->updatePendidikan($prmPendUpdate);
			
			

			$this->view->proses = "2";
			$this->view->keterangan = "Data Pendidikan";
			$this->view->status = $hasil;	
		}
		$this->view->pendList = $this->auditor_serv->getPendidikan($nip);
		$this->render('pendidikan');
	}

	public function hapusdidikAction() {	
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$kdjenjang = $_REQUEST['jenjang'];
		$lembaga = $_REQUEST['lembaga'];
		$jurusan = $_REQUEST['jurusan'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->nmJenjangList = $this->auditor_serv->getPendidikanListAll();
		
		
		$prmPendDelete = array("nip"		=>$_REQUEST['nip'],
								  "kdjenjang"	=>$_REQUEST['jenjang'],
								  "lembaga"	=>$lembaga,
								  "jurusan"	=>$jurusan);
							  
		$hasil = $this->auditor_serv->deletePendidikan($prmPendDelete);
		
		$this->view->proses = "3";
		$this->view->keterangan = "Data Pendidikan";
		$this->view->status = $hasil;	

		$this->view->pendList = $this->auditor_serv->getPendidikan($nip);

		$this->render('pendidikan');
	}	
	
// PELATIHAN
//============
	public function pelatihanAction() {
		$user = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$perintah = $_POST['perintahSimpan'];

		if($perintah == "SIMPAN")
		{
			$hrSertifikat = $_POST['hrSertifikat'];
			$blnSertifikat = $_POST['blnSertifikat'];
			$thnSertifikat = $_POST['thnSertifikat'];
			
			if($hrSertifikat == '#')
			{
				$tglSertifikat = null;
			}
			else
			{
				if($blnSertifikat == '#')
				{
					$tglSertifikat = null;
				}
				else
				{
					if($thnSertifikat == '')
					{
						$tglSertifikat = null;
					}
					else
					{
						$_POST['thnSertifikat'].'-'.$_POST['blnSertifikat'].'-'.$_POST['hrSertifikat'];
					}
				}
			}

			$kodePelatihan = $_POST['namaPelatihan'];	
			$kodePelatihanArr = explode("~",$kodePelatihan);
			$idPelatihan = $kodePelatihanArr[0];
			$nmPelatihan = $kodePelatihanArr[1];
			/* if (($_POST['thnSertifikat']) && ($_POST['thnSertifikat'] == '#') || ($_POST['thnSertifikat'] == null)){
				$tglSertifikat = null;
			}
			else {
				$tglSertifikat = $_POST['thnSertifikat'].'-'.$_POST['blnSertifikat'].'-'.$_POST['hrSertifikat'];		
			} */
 
			
			$kodePenyelenggara = $_POST['kodePenyelenggara'];
			if($kodePenyelenggara == "") {$kodePenyelenggara = null;}
			$prmLatihInsert = array("nip"				=>$_POST['nipH'],
									"idPelatihan"		=>$idPelatihan,
									"nmPelatihan"		=>$nmPelatihan,
									"tahunPelatihan"	=>$_POST['tahunPelatihan'],
									"lamaJamlat"		=>$_POST['vJamlat'],
									"satuanJamlat"		=>$_POST['satuanJamlat'],
									"tempat"			=>$_POST['tempat'],
									"keterangan"		=>$_POST['keterangan'],
									"jenisLatih"		=>$_POST['jenisLatih'],
									"kategoriLatih"		=>$_POST['kategoriLatih'],
									"kodePenyelenggara"		=>$kodePenyelenggara,
									"penyelenggara"		=>$_POST['penyelenggara'],
									"kota"				=>$_POST['ckota'],
									"noSertifikat"		=>$_POST['noSertifikat'],
									"tglSertifikat"		=>$tglSertifikat,
									"user"				=>$user);
			
			$hasil = $this->auditor_serv->insertPelatihan($prmLatihInsert);	

			$this->view->proses = "1";
			$this->view->keterangan = "Data Pelatihan";
			$this->view->status = $hasil;
				
				
		}	
		$this->view->latihList = $this->auditor_serv->getPelatihan($nip);	
		$this->render('pelatihan');
	}
	
	public function listnamapelatihanAction()
	{
		$jenisLatih = $_REQUEST['jenisLatih'];
		$this->view->namaPelatiahList = $this->auditor_serv->listNamaPelatihan($jenisLatih);	
	}
	
	public function listpenyelenggaradiklatAction()
	{
		$nama = $_POST['nama'];
		$kota = $_POST['kota'];
		$paramCari = array("nama" => $nama,
							"kota" => $kota);
		$this->view->penyelenggaraDiklatList = $this->auditor_serv->listPenyelenggaraDiklat($paramCari);	
	}
	
	public function pelatihanupdateAction() {
		$nip 		= $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$this->view->perintah 	= $_REQUEST['perintah'];
		
		$c_peg_latih		= $_REQUEST['c_latih_struktural'];
		$n_pend_latih		= $_REQUEST['n_latih_struktural'];
		
		$this->view->namaPelatiahList = $this->auditor_serv->listNamaPelatihan($c_peg_latih);
		
		$i_peny_diklat		= $_REQUEST['i_peny_diklat'];
		$d_pend_tahunlatih		= $_REQUEST['d_pend_mulailatih'];
		
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$param = array("nip" => $nip,
						"c_peg_latih" => $c_peg_latih,
						"n_pend_latih" => $n_pend_latih,
						"i_peny_diklat" => $i_peny_diklat,
						"d_pend_tahunlatih" => $d_pend_tahunlatih);
						
		$this->view->latihDetail = $this->auditor_serv->getPelatihanDetailByNip($param);	
		
		//var_dump($this->view->latihDetail);
		$perintahupdate = $_POST['perintahUpdate'];
		
		if($perintahupdate == "UPDATE")
		{
			$hrSertifikat = $_POST['hrSertifikat'];
			$blnSertifikat = $_POST['blnSertifikat'];
			$thnSertifikat = $_POST['thnSertifikat'];
			
			if($hrSertifikat == '#')
			{
				$tglSertifikat = null;
			}
			else
			{
				if($blnSertifikat == '#')
				{
					$tglSertifikat = null;
				}
				else
				{
					if($thnSertifikat == '')
					{
						$tglSertifikat = null;
					}
					else
					{
						$tglSertifikat =$_POST['thnSertifikat'].'-'.$_POST['blnSertifikat'].'-'.$_POST['hrSertifikat'];
					}
				}
			}
 
			$nip = $_POST['nipH'];
			$kodePelatihan = $_POST['namaPelatihan'];	
			$kodePelatihanArr = explode("~",$kodePelatihan);
			$idPelatihan = $kodePelatihanArr[0];
			$nmPelatihan = $kodePelatihanArr[1];
			
			$kdPelatihan = $_POST['namaPelatihanH'];	
			$kdPelatihanArr = explode("~",$kdPelatihan);
			$idPelatihanH = $kdPelatihanArr[0];
			$nmPelatihanH = $kdPelatihanArr[1];
			
			$kodePenyelenggara = $_POST['kodePenyelenggara'];
			if($kodePenyelenggara == "") {$kodePenyelenggara = null;}
			$prmLatihUpdate = array("nip"				=>$_POST['nipH'],
									"idPelatihan"		=>$idPelatihan,
									"nmPelatihan"		=>$nmPelatihan,
									"idPelatihanH"		=>$idPelatihanH,
									"nmPelatihanH"		=>$nmPelatihanH,
									"tahunPelatihan"	=>$_POST['tahunPelatihan'],
									"tahunPelatihanH"	=>$_POST['tahunPelatihanH'],
									"lamaJamlat"		=>$_POST['vJamlat'],
									"satuanJamlat"		=>$_POST['satuanJamlat'],
									"tempat"			=>$_POST['tempat'],
									"keterangan"		=>$_POST['keterangan'],
									"jenisLatih"		=>$_POST['jenisLatih'],
									"kategoriLatih"		=>$_POST['kategoriLatih'],
									"kodePenyelenggara"		=>$kodePenyelenggara,
									"penyelenggara"		=>$_POST['penyelenggara'],
									"kota"				=>$_POST['ckota'],
									"noSertifikat"		=>$_POST['noSertifikat'],
									"tglSertifikat"		=>$tglSertifikat,
									"user"				=>$user);
				
			$hasil = $this->auditor_serv->updatePelatihan($prmLatihUpdate);	
			//var_dump($prmLatihUpdate);

			$this->view->proses = "2";
			$this->view->keterangan = "Pelatihan";
			$this->view->status = $hasil;

			}	
		$this->view->latihList = $this->auditor_serv->getPelatihan($nip);		
		$this->render('pelatihan');
	}
	
	public function hapuslatihAction() {
		
		
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		
		$c_peg_latih		= $_REQUEST['c_latih_struktural'];
		$n_pend_latih		= $_REQUEST['n_latih_struktural'];
		
		$this->view->namaPelatiahList = $this->auditor_serv->listNamaPelatihan($c_peg_latih);
				
		$thnlatih = $_REQUEST['mulai'];	
		$nmLatih = $_REQUEST['latih'];	

		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);

		$prmLatihDelete = array("nip"		=>$nip,
								"nmLatih" => $nmLatih,
								"thnlatih"	=>$thnlatih); 

		$hasil = $this->auditor_serv->deletePelatihan($prmLatihDelete);
		 
		$this->view->proses = "3";
		$this->view->keterangan = "Pelatihan";
		$this->view->status = $hasil;
		$this->view->latihList = $this->auditor_serv->getPelatihan($nip);
		
		$this->render('pelatihan');
	}
	
//========================JABATAN========================================	
    public function jabatanAction() {
	   //echo "Masuk Controller kepangkatanAction";
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		
		$userid = $this->user;

		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
		$this->view->satminkalList = $this->auditor_serv->getSatminkalList();
		$satminkalAwal = $this->view->satminkalList[0]['c_satminkal'];
		$this->view->satkerList = $this->auditor_serv->getSatkerList($satminkalAwal);
		
		$perintah = $_POST['perintahSimpan'];
		
		if($perintah == "SIMPAN")
		{
			$nip = $_POST['nipH'];
			$status = $_POST['status'];
			$bidang = $_POST['bidang'];
			
			$nStatus = $this->auditor_serv->getStatusByCode($status);
			$c_satker = $_POST['satker'];
			$n_instansi = $_POST['n_instansi'];
			if($status == "LAN")
			{
				$nmJabat = "Lainnya";
			}
			else
			{
				
				$nmJabat1 = $_POST['nmJabat'];
				if($status == "ADT")
				{
					$nmJabatArr  = explode("~",$nmJabat1);
					$nmJabat = $nmJabatArr[0];
				}
				else
				{
					$nmJabat = $nmJabat1;
				}
			}
			
			//echo "nmJabat=$nmJabat";
			if($status == "TAH")
			{
				$n_jabatan = "$nStatus-$bidang";
			}
			else if($status == "ADT")
			{
				$n_jabatan = "$nStatus-$nmJabat";
			}
			else if ($status == "SKR")
			{
				$nmSatker = $this->auditor_serv->getSatkerByCode($c_satker);
				$n_jabatan = "$nmJabat-$nmSatker";
			}
			else if($status == "LAN")
			{
				$n_jabatan = $_POST['n_jabatan'];
			}
			else if($status == "SKT")
			{
				$nmJabatLengkap = $this->auditor_serv->getJabatanSekretariat($nmJabat);
				$nmPejabat = $_POST['nmPejabat'];
				$n_instansi = $nmJabatLengkap;
				$n_jabatan1 = $nmPejabat;
				$nJabatanArr = explode("~",$n_jabatan1);
				$n_jabatan = $nJabatanArr[1];
				$nmJabatArr = explode("~",$nmJabat);
				$nmJabat = $nmJabatArr[0];
			}
			else if($status == "SPV")
			{
				$n_jabatan = "Supervisor";
			}
			
			//$n_jabatan = $_POST['n_jabatan'];
			
			$a_lokasi = $_POST['a_lokasi'];
			$e_jabatan = $_POST['e_jabatan'];
			
			$satminkal = $_POST['satminkal'];
			$satker = $_POST['satker'];
			
			$hrMulai = $_POST['hrMulai'];
			$blnMulai = $_POST['blnMulai'];
			$thnMulai = $_POST['thnMulai'];
			
			if ((!$hrMulai) || ($hrMulai == '#')){
				$tglMulai = null;
			}
			else
			{
				if ((!$blnMulai) || ($blnMulai == '#')) {
					$tglMulai = null;
				}
				else
				{
					if (!$thnMulai) {
						$tglMulai = null;
					}			
					else {
						$tglMulai = "$thnMulai-$blnMulai-$hrMulai"; 		
					}
				}
			}
			
			$hrAkhir = $_POST['hrAkhir'];
			$blnAkhir = $_POST['blnAkhir'];
			$thnAkhir = $_POST['thnAkhir'];
			
			
			if ((!$hrAkhir) || ($hrAkhir == '#')){
				$tglAkhir = null;
			}
			else
			{
				if ((!$blnAkhir) || ($blnAkhir == '#')) {
					$tglAkhir = null;
				}
				else
				{
					if (!$thnAkhir) {
						$tglAkhir = null;
					}			
					else {
						$tglAkhir = "$thnAkhir-$blnAkhir-$hrAkhir"; 		
					}
				}
			}
			
			//echo "$tglMulai | $tglAkhir";
			$noSK = $_POST['noSK'];
			
			$hrSK = $_POST['hrSK'];
			$blnSK = $_POST['blnSK'];
			$thnSK = $_POST['thnSK'];
			
			if ((!$hrSK) || ($hrSK == '#')){
				$tglSK = null;
			}
			else
			{
				if ((!$blnSK) || ($blnSK == '#')) {
					$tglSK = null;
				}
				else
				{
					if (!$thnSK) {
						$tglSK = null;
					}			
					else {
						$tglSK = "$thnSK-$blnSK-$hrSK"; 		
					}
				}
			}
			
			if($status == "SKT")
			{ $cEselon = $_POST['cEselon'];}
			else
			{ $cEselon = ''; }
			
			$hrEselon = $_POST['hrEselon'];
			$blnEselon = $_POST['blnEselon'];
			$thnEselon = $_POST['thnEselon'];
			
			if ((!$hrEselon) || ($hrEselon == '#')){
				$dEselon = null;
			}
			else
			{
				if ((!$blnEselon) || ($blnEselon == '#')) {
					$dEselon = null;
				}
				else
				{
					if (!$thnEselon) {
						$dEselon = null;
					}			
					else {
						$dEselon = "$thnEselon-$blnEselon-$hrEselon"; 		
					}
				}
			}
			
			$paramJabatInsert = array("i_peg_nip" => $nip,
								"c_peg_status" => $status,
								"c_peg_jabatan" => trim($nmJabat),
								"n_jabatan" => trim($n_jabatan),
								"n_instansi" => $n_instansi,
								"a_lokasi" => $a_lokasi,
								"e_jabatan" => $e_jabatan,
								"n_bidang" => $bidang,
								"c_satminkal" => $satminkal,
								"c_satker" => $satker,
								"d_jabatan_mulai" => $tglMulai,
								"d_jabatan_akhir" => $tglAkhir,
								"i_sk" => $noSK,
								"d_sk" => $tglSK,
								"c_eselon" => trim($cEselon),
								"d_eselon" => $dEselon,
								"userid" =>$userid);
			//var_dump($paramJabatInsert);					
			$hasil = $this->auditor_serv->insertJabatan($paramJabatInsert);
		 
			$this->view->proses = "1";
			$this->view->keterangan = "Data Jabatan";
			$this->view->status = $hasil;
		}
		$this->view->jabatList = $this->auditor_serv->getJabatListByNip($nip);
		
	}	
	
	public function listjabatanAction()
	{
		$status = $_REQUEST['status'];
		$this->view->status = $status;
		$this->view->listJabat = $this->auditor_serv->getJabatanListByStatus($status);
		
	}
	
	public function listpejabatAction()
	{
		$org = $_REQUEST['org'];
		$eselon = $_REQUEST['eselon'];
		$this->view->eselon = $eselon;
		$this->view->listPejabat = $this->auditor_serv->getPejabatListByOrg($org);
		
	}
	
	public function listsatkerAction()
	{
		$satminkal = $_REQUEST['satminkal'];
		$this->view->listSatker = $this->auditor_serv->getSatkerList($satminkal);
	}
	
	public function jabatanupdateAction() {
	   //echo "Masuk Controller kepangkatanAction";
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		
		$status = $_REQUEST['status'];
		$jabatan = $_REQUEST['jabatan'];
		$tglMulai = $_REQUEST['tglMulai'];
		
		$paramSearch = array("nip" => $nip,
							"status" => $status,
							"jabatan" => $jabatan,
							"tglMulai" => $tglMulai);
							
		$this->view->personalJabatDetail = $this->auditor_serv->getPersonalJabatDetail($paramSearch);
		$this->view->listJabat = $this->auditor_serv->getJabatanListByStatus($status);
		$this->view->listPejabat = $this->auditor_serv->getPejabatListByOrg($jabatan);
		$this->view->eselonOrg = $this->auditor_serv->getEselonByOrg($jabatan);
//var_dump($this->view->personalJabatDetail);
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
		$this->view->satminkalList = $this->auditor_serv->getSatminkalList();
		$satminkalAwal = $this->view->satminkalList[0]['c_satminkal'];
		$this->view->satkerList = $this->auditor_serv->getSatkerList($satminkalAwal);
		
		$this->view->perintah = $_REQUEST['perintah'];
		$perintahUpdate = $_POST['perintahUpdate'];
		if($perintahUpdate == "UPDATE")
		{
			$nip = $_POST['nipH'];
			$status = $_POST['status'];
			$statusH = $_POST['statusH'];
			$bidang = $_POST['bidang'];
			$bidangH = $_POST['bidangH'];
			
			$nStatus = $this->auditor_serv->getStatusByCode($status);
			$c_satker = $_POST['satker'];
			
			$n_instansi = "";
			$a_lokasi = "";
			$e_jabatan = "";
			
			if($status == "LAN")
			{
				$nmJabat = "Lainnya";
				$bidang = "";
			}
			else
			{
				//$nmJabat = $_POST['nmJabat'];
//nmJabatH = $_POST['nmJabatH'];
				$nmJabat1 = $_POST['nmJabat'];
				if($status == "ADT")
				{
					$nmJabatArr  = explode("~",$nmJabat1);
					$nmJabat = $nmJabatArr[0];
				}
				else
				{
					$nmJabat = $nmJabat1;
				}
				
				$nmJabat1H = $_POST['nmJabatH'];
				if($status == "ADT")
				{
					$nmJabat1HArr  = explode("~",$nmJabat1H);
					$nmJabatH = $nmJabat1HArr[0];
				}
				else
				{
					$nmJabatH = $nmJabat1H;
				}
			}
			
			if($status == "TAH")
			{
				$n_jabatan = "$nStatus-$bidang";
				//$n_jabatanH = "$nStatusH-$bidangH";
			}
			else if($status == "ADT")
			{
				$n_jabatan = "$nStatus-$nmJabat";
				//$n_jabatanH = "$nStatusH-$nmJabatH";
				$bidang = "";
			}
			else if ($status == "SKR")
			{
				$nmSatker = $this->auditor_serv->getSatkerByCode($c_satker);
				$n_jabatan = "$nmJabat-$nmSatker";
				$bidang = "";
			}
			else if($status == "LAN")
			{
				$n_jabatan = $_POST['n_jabatan'];
				//$n_jabatanH = $_POST['n_jabatanH'];
				$n_instansi = $_POST['n_instansi'];
				$a_lokasi = $_POST['a_lokasi'];
				$e_jabatan = $_POST['e_jabatan'];
				
			}
			else if($status == "SKT")
			{
				
				$nmPejabat = $_POST['nmPejabat'];
				$nmPejabatH = $_POST['nmPejabatH'];
				
				$nmJabatLengkap = $this->auditor_serv->getJabatanSekretariat($nmJabat);
				$nmJabatLengkapH = $this->auditor_serv->getJabatanSekretariat($nmJabatH);
				
				$n_instansi = $nmJabatLengkap;
				$n_jabatan1 = $nmPejabat;
				$nJabatan1Arr = explode("~",$n_jabatan1);
				$n_jabatan = $nJabatan1Arr[1];
				
				$n_jabatanH1 = $nmPejabatH;
				$nJabatanH1Arr = explode("~",$n_jabatanH1);
				$n_jabatanH = $nJabatanH1Arr[1];
				
				$nmJabatArr = explode("~",$nmJabat);
				$nmJabat = $nmJabatArr[0];
				
				$nmJabatHArr = explode("~",$nmJabatH);
				$nmJabatH = $nmJabatHArr[0];
				
				
				/* $nmJabatLengkap = $this->auditor_serv->getJabatanSekretariat($nmJabat);
				$nmJabatLengkapH = $this->auditor_serv->getJabatanSekretariat($nmJabatH);
				$n_jabatan = "$nStatus-$nmJabatLengkap"; */
				//$n_jabatanH = "$nStatusH-$nmJabatLengkapH";
				$bidang = "";
			}
			else if($status == "SPV")
			{
				$n_jabatan = "Supervisor";
			}
			
			
			$n_jabatanH = $_POST['n_jabatanH'];
			$satminkal = $_POST['satminkal'];
			$satker = $_POST['satker'];
			
			$hrMulai = $_POST['hrMulai'];
			$blnMulai = $_POST['blnMulai'];
			$thnMulai = $_POST['thnMulai'];
			
			if ((!$hrMulai) || ($hrMulai == '#')){
				$tglMulai = null;
			}
			else
			{
				if ((!$blnMulai) || ($blnMulai == '#')) {
					$tglMulai = null;
				}
				else
				{
					if (!$thnMulai) {
						$tglMulai = null;
					}			
					else {
						$tglMulai = "$thnMulai-$blnMulai-$hrMulai"; 		
					}
				}
			}
			
			$tglMulaiH = $_POST['mulaiH'];
			
			$hrAkhir = $_POST['hrAkhir'];
			$blnAkhir = $_POST['blnAkhir'];
			$thnAkhir = $_POST['thnAkhir'];
			
			
			if ((!$hrAkhir) || ($hrAkhir == '#')){
				$tglAkhir = null;
			}
			else
			{
				if ((!$blnAkhir) || ($blnAkhir == '#')) {
					$tglAkhir = null;
				}
				else
				{
					if (!$thnAkhir) {
						$tglAkhir = null;
					}			
					else {
						$tglAkhir = "$thnAkhir-$blnAkhir-$hrAkhir"; 		
					}
				}
			}
			
			$noSK = $_POST['noSK'];
			
			$hrSK = $_POST['hrSK'];
			$blnSK = $_POST['blnSK'];
			$thnSK = $_POST['thnSK'];
			
			if ((!$hrSK) || ($hrSK == '#')){
				$tglSK = null;
			}
			else
			{
				if ((!$blnSK) || ($blnSK == '#')) {
					$tglSK = null;
				}
				else
				{
					if (!$thnSK) {
						$tglSK = null;
					}			
					else {
						$tglSK = "$thnSK-$blnSK-$hrSK"; 		
					}
				}
			}
			
			if($status == "SKT")
			{ $cEselon = $_POST['cEselon'];}
			else
			{ $cEselon = ''; }
			
			$hrEselon = $_POST['hrEselon'];
			$blnEselon = $_POST['blnEselon'];
			$thnEselon = $_POST['thnEselon'];
			
			if ((!$hrEselon) || ($hrEselon == '#')){
				$dEselon = null;
			}
			else
			{
				if ((!$blnEselon) || ($blnEselon == '#')) {
					$dEselon = null;
				}
				else
				{
					if (!$thnEselon) {
						$dEselon = null;
					}			
					else {
						$dEselon = "$thnEselon-$blnEselon-$hrEselon"; 		
					}
				}
			}
			
			$paramJabatUpdate = array("i_peg_nip" => $nip,
								"c_peg_status" => $status,
								"c_peg_statusH" => $statusH,
								"c_peg_jabatan" => $nmJabat,
								"c_peg_jabatanH" => $nmJabatH,
								"n_jabatan" => $n_jabatan,
								"n_jabatanH" => $n_jabatanH,
								"n_instansi" => $n_instansi,
								"a_lokasi" => $a_lokasi,
								"e_jabatan" => $e_jabatan,
								"n_bidang" => $bidang,
								"c_satminkal" => $satminkal,
								"c_satker" => $satker,
								"d_jabatan_mulai" => $tglMulai,
								"d_jabatan_mulaiH" => $tglMulaiH,
								"d_jabatan_akhir" => $tglAkhir,
								"i_sk" => $noSK,
								"d_sk" => $tglSK,
								"c_eselon" => $cEselon,
								"d_eselon" => $dEselon,
								"userid" =>$userid);
//var_dump($paramJabatUpdate);
			$hasil = $this->auditor_serv->updateJabatan($paramJabatUpdate);
		 
			$this->view->proses = "2";
			$this->view->keterangan = "Data Jabatan";
			$this->view->status = $hasil;
		}
		$this->view->jabatList = $this->auditor_serv->getJabatListByNip($nip);
		$this->render('jabatan');
	}	

	public function hapusjabatAction() {
		
		
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$status = $_REQUEST['status'];	
		$jabatan = $_REQUEST['jabatan'];	
		$mulai = $_REQUEST['mulai'];	

		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->personalStatusList = $this->auditor_serv->getPersonalStatusList();
		$this->view->satminkalList = $this->auditor_serv->getSatminkalList();

		$prmJabatDelete = array("i_peg_nip"		=>$nip,
								"c_peg_status" => $status,
								"c_peg_jabatan"	=>$jabatan,
								"d_jabatan_mulai"		=>$mulai);
								  
		
		$hasil = $this->auditor_serv->deleteJabatan($prmJabatDelete);
		
		$this->view->proses = "3";
		$this->view->keterangan = "Data Jabatan";
		$this->view->status = $hasil;
		
		$this->view->jabatList = $this->auditor_serv->getJabatListByNip($nip);
		$this->render('jabatan');
	}
	
//===================GOLONGAN
	public function golonganAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->golonganList = $this->auditor_serv->getGolonganList();
		
		$perintah = $_POST['perintahSimpan'];
		if($perintah == "SIMPAN")
		{
			$nip = $_POST['nipH'];
			$c_peg_golongan = $_POST['gol'];
			$c_pns_status = $_POST['statusPeg'];
			$hrTmt = $_POST['hrTmt'];
			$blnTmt= $_POST['blnTmt'];
			$thnTmt= $_POST['thnTmt'];
			
			if ((!$thnTmt) || ($thnTmt == '#')){
				$d_tmt_golongan = null;
			}
			else
			{
			if ($blnTmt == '#') {
				$d_tmt_golongan = null;
			}
			
			if ($hrTmt == '#') {
				$d_tmt_golongan = null;
			}			
			else {
				$d_tmt_golongan = "$thnTmt-$blnTmt-$hrTmt";		
			}
			}
			
			
			$i_sk = $_POST['nomorSK'];
			$hrSK = $_POST['hrSK'];
			$blnSK= $_POST['blnSK'];
			$thnSK= $_POST['thnSK'];
			
			if ((!$thnSK) || ($thnSK == '#')){
				$d_sk = null;
			}
			else
			{
				if ($blnSK == '#') {
					$d_sk = null;
				}
				
				if ($hrSK == '#') {
					$d_sk = null;
				}			
				else {
					$d_sk = "$thnSK-$blnSK-$hrSK";	
				}
			}
			

			$prmGolInsert = array("i_peg_nip"		=>$nip,
								"c_peg_golongan" 	=> $c_peg_golongan,
								"c_pns_status"		=>$c_pns_status,
								"i_sk"				=>$i_sk,
								"d_sk"				=>$d_sk,
								"d_tmt_golongan"	=>$d_tmt_golongan,
								"q_kerja_bulan"		=>$q_kerja_bulan,
								"q_kerja_tahun"		=>$q_kerja_tahun,
								"i_entry"			=>$userid);
								  
			$hasil = $this->auditor_serv->insertGolongan($prmGolInsert);	
			$this->view->proses = "1";
			$this->view->keterangan = "Data Golongan";
			$this->view->status = $hasil;
		}
		$this->view->golonganByNipList = $this->auditor_serv->getGolonganListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);

	}
	
	public function golonganupdateAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$golongan = $_REQUEST['golongan'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->golonganList = $this->auditor_serv->getGolonganList();
		$paramCari = array("nip" => $nip,
					"golongan" => $golongan);
		$this->view->personalGolonganDetail = $this->auditor_serv->getPersonalGolonganDetail($paramCari);

		$this->view->perintah = $_REQUEST['perintah'];
		$perintahUpdate = $_POST['perintahUpdate'];
		if($perintahUpdate == "UPDATE")
		{
			$nip = $_POST['nipH'];
			$c_peg_golongan = $_POST['gol'];
			$c_peg_golonganH = $_POST['golH'];
			$c_pns_status = $_POST['statusPeg'];
			$c_pns_statusH = $_POST['statusPegH'];
			
			$hrTmt = $_POST['hrTmt'];
			$blnTmt= $_POST['blnTmt'];
			$thnTmt= $_POST['thnTmt'];
			if ((!$thnTmt) || ($thnTmt == '#')){
				$d_tmt_golongan = null;
			}
			else
			{
			if ($blnTmt == '#') {
				$d_tmt_golongan = null;
			}
			
			if ($hrTmt == '#') {
				$d_tmt_golongan = null;
			}			
			else {
				$d_tmt_golongan = "$thnTmt-$blnTmt-$hrTmt";		
			}
			}
			
			
			$i_sk = $_POST['nomorSK'];
			$hrSK = $_POST['hrSK'];
			$blnSK= $_POST['blnSK'];
			$thnSK= $_POST['thnSK'];
			
			if ((!$thnSK) || ($thnSK == '#')){
				$d_sk = null;
			}
			else
			{
			
			if ($blnSK == '#') {
				$d_sk = null;
			}
			
			if ($hrSK == '#') {
				$d_sk = null;
			}			
			else {
				$d_sk = "$thnSK-$blnSK-$hrSK";	
			}
			}

			$prmGolUpdate = array("i_peg_nip"		=>$nip,
								"c_peg_golongan" 	=> $c_peg_golongan,
								"c_peg_golonganH" 	=> $c_peg_golonganH,
								"c_pns_status"		=>$c_pns_status,
								"c_pns_statusH"		=>$c_pns_statusH,
								"i_sk"				=>$i_sk,
								"d_sk"				=>$d_sk,
								"d_tmt_golongan"	=>$d_tmt_golongan,
								"q_kerja_bulan"		=>$q_kerja_bulan,
								"q_kerja_tahun"		=>$q_kerja_tahun,
								"i_entry"			=>$userid);
			
			$hasil = $this->auditor_serv->updateGolongan($prmGolUpdate);	
			$this->view->proses = "2";
			$this->view->keterangan = "Data Golongan";
			$this->view->status = $hasil;
		}
		$this->view->golonganByNipList = $this->auditor_serv->getGolonganListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->render('golongan');
	}
	
	public function hapusgolonganAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$golongan = $_REQUEST['golongan'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->view->golonganList = $this->auditor_serv->getGolonganList();
		$paramDelete = array("i_peg_nip" => $nip,
					"c_peg_golongan" => $golongan);
		$hasil = $this->auditor_serv->hapusPersonalGolongan($paramDelete);

		$this->view->proses = "3";
		$this->view->keterangan = "Data Golongan";
		$this->view->status = $hasil;
		$this->view->golonganByNipList = $this->auditor_serv->getGolonganListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->render('golongan');
	}
	
	//Sertifikasi
	//---------------
	public function sertifikasiAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$perintah = $_POST['perintahSimpan'];
		if($perintah == "SIMPAN")
		{
			$nip = $_POST['nipH'];
			$nSertifikat = $_POST['nSertifikat'];
			$iSertifikat = $_POST['iSertifikat'];
			$nLembaga = $_POST['nLembaga'];
			
			$hrSertifikat = $_POST['hrSertifikat'];
			$blnSertifikat= $_POST['blnSertifikat'];
			$thnSertifikat= $_POST['thnSertifikat'];
			
			if ((!$thnSertifikat) || ($thnSertifikat == '#')){
				$dSertifikat = null;
			}
			else
			{
				if ($blnSertifikat == '#') {
					$dSertifikat = null;
				}
				
				if ($hrSertifikat == '#') {
					$dSertifikat = null;
				}			
				else {
					$dSertifikat = "$thnSertifikat-$blnSertifikat-$hrSertifikat";		
				}
			}
			
			$hrMulaiberlaku = $_POST['hrMulaiberlaku'];
			$blnMulaiberlaku= $_POST['blnMulaiberlaku'];
			$thnMulaiberlaku= $_POST['thnMulaiberlaku'];
			
			if ((!$thnMulaiberlaku) || ($thnMulaiberlaku == '#')){
				$dMulaiberlaku = null;
			}
			else
			{
				if ($blnMulaiberlaku == '#') {
					$dMulaiberlaku = null;
				}
				
				if ($hrMulaiberlaku == '#') {
					$dMulaiberlaku = null;
				}			
				else {
					$dMulaiberlaku = "$thnMulaiberlaku-$blnMulaiberlaku-$hrMulaiberlaku";		
				}
			}
			
			$hrAkhirberlaku = $_POST['hrAkhirberlaku'];
			$blnAkhirberlaku = $_POST['blnAkhirberlaku'];
			$thnAkhirberlaku = $_POST['thnAkhirberlaku'];
			
			if ((!$thnAkhirberlaku) || ($thnAkhirberlaku == '#')){
				$dAkhirberlaku = null;
			}
			else
			{
				if ($blnAkhirberlaku == '#') {
					$dAkhirberlaku = null;
				}
				
				if ($hrAkhirberlaku == '#') {
					$dAkhirberlaku = null;
				}			
				else {
					$dAkhirberlaku = "$thnAkhirberlaku-$blnAkhirberlaku-$hrAkhirberlaku";		
				}
			}
			
			$eKeterangan = $_POST['eKeterangan'];
			
			$prmInsert = array("i_peg_nip"		=>$nip,
								"i_sertifikat" 		=> $iSertifikat,
								"n_sertifikat"		=>$nSertifikat,
								"n_lembaga"			=>$nLembaga,
								"d_sertifikat"		=>$dSertifikat,
								"d_mulaiberlaku"	=>$dMulaiberlaku,
								"d_akhirberlaku"	=>$dAkhirberlaku,
								"e_keterangan"		=>$eKeterangan,
								"i_entry"			=>$userid);
								  
			$hasil = $this->auditor_serv->insertSertifikasi($prmInsert);	
			$this->view->proses = "1";
			$this->view->keterangan = "Data Sertifikasi";
			$this->view->status = $hasil;
		}
		$this->view->sertifikasiByNipList = $this->auditor_serv->getSertifikasiListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);

	}
	
	public function sertifikasiupdateAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		
		echo "xxx $nip";
		$iSertifikat = $_REQUEST['i_sertifikat'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$param = array("nip" => $nip,
					"iSertifikat" => $iSertifikat);
		$this->view->personalSertifikasiDetail = $this->auditor_serv->getPersonalSertifikasiDetail($param);

		$this->view->perintah = $_REQUEST['perintah'];
		$perintahUpdate = $_POST['perintahUpdate'];
		if($perintahUpdate == "UPDATE")
		{
			$nip = $_POST['nipH'];
			$nSertifikat = $_POST['nSertifikat'];
			$iSertifikat = $_POST['iSertifikat'];
			$iSertifikatH = $_POST['iSertifikatH'];
			$nLembaga = $_POST['nLembaga'];
			
			$hrSertifikat = $_POST['hrSertifikat'];
			$blnSertifikat= $_POST['blnSertifikat'];
			$thnSertifikat= $_POST['thnSertifikat'];
			
			if ((!$thnSertifikat) || ($thnSertifikat == '#')){
				$dSertifikat = null;
			}
			else
			{
				if ($blnSertifikat == '#') {
					$dSertifikat = null;
				}
				
				if ($hrSertifikat == '#') {
					$dSertifikat = null;
				}			
				else {
					$dSertifikat = "$thnSertifikat-$blnSertifikat-$hrSertifikat";		
				}
			}
			
			$hrMulaiberlaku = $_POST['hrMulaiberlaku'];
			$blnMulaiberlaku= $_POST['blnMulaiberlaku'];
			$thnMulaiberlaku= $_POST['thnMulaiberlaku'];
			
			if ((!$thnMulaiberlaku) || ($thnMulaiberlaku == '#')){
				$dMulaiberlaku = null;
			}
			else
			{
				if ($blnMulaiberlaku == '#') {
					$dMulaiberlaku = null;
				}
				
				if ($hrMulaiberlaku == '#') {
					$dMulaiberlaku = null;
				}			
				else {
					$dMulaiberlaku = "$thnMulaiberlaku-$blnMulaiberlaku-$hrMulaiberlaku";		
				}
			}
			
			$hrAkhirberlaku = $_POST['hrAkhirberlaku'];
			$blnAkhirberlaku = $_POST['blnAkhirberlaku'];
			$thnAkhirberlaku = $_POST['thnAkhirberlaku'];
			
			if ((!$thnAkhirberlaku) || ($thnAkhirberlaku == '#')){
				$dAkhirberlaku = null;
			}
			else
			{
				if ($blnAkhirberlaku == '#') {
					$dAkhirberlaku = null;
				}
				
				if ($hrAkhirberlaku == '#') {
					$dAkhirberlaku = null;
				}			
				else {
					$dAkhirberlaku = "$thnAkhirberlaku-$blnAkhirberlaku-$hrAkhirberlaku";		
				}
			}
			
			$eKeterangan = $_POST['eKeterangan'];
			
			$param = array("i_peg_nip"		=>$nip,
								"i_sertifikat" 		=> $iSertifikat,
								"i_sertifikatH" 		=> $iSertifikatH,
								"n_sertifikat"		=>$nSertifikat,
								"n_lembaga"			=>$nLembaga,
								"d_sertifikat"		=>$dSertifikat,
								"d_mulaiberlaku"	=>$dMulaiberlaku,
								"d_akhirberlaku"	=>$dAkhirberlaku,
								"e_keterangan"		=>$eKeterangan,
								"i_entry"			=>$userid);
			
			$hasil = $this->auditor_serv->updateSertifikasi($param);	
			$this->view->proses = "2";
			$this->view->keterangan = "Data Sertifikasi";
			$this->view->status = $hasil;
		}
		$this->view->sertifikasiByNipList = $this->auditor_serv->getSertifikasiListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->render('sertifikasi');
	}
	
	public function hapussertifikasiAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$iSertifikat = $_REQUEST['iSertifikat'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$param = array("i_peg_nip" => $nip,
					"i_sertifikat" => $iSertifikat);
		$hasil = $this->auditor_serv->hapusSertifikasi($param);

		$this->view->proses = "3";
		$this->view->keterangan = "Data Sertifikasi";
		$this->view->status = $hasil;
		$this->view->sertifikasiByNipList = $this->auditor_serv->getSertifikasiListByNip($nip);
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$this->render('sertifikasi');
	}
//===================PENGHARGAAN
	public function penghargaanAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		
		$this->view->nip=$nip;
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$perintah = $_POST['perintahSimpan'];
		if($perintah == "SIMPAN")
		{
			$nip = $_POST['nipH'];
			$i_srt_penghargaan = $_POST['noSurat'];
			$n_penghargaan = $_POST['nmHarga'];
			$d_penghargaan = $_POST['d_penghargaan'];
			$n_pejabat = $_POST['nPejabat'];
			$e_penghargaan = $_POST['keterangan'];
			
			$prmInsert = array("i_peg_nip"		=>$nip,
								"i_srt_penghargaan" 	=> $i_srt_penghargaan,
								"n_penghargaan"		=>$n_penghargaan,
								"d_penghargaan"				=>$d_penghargaan,
								"n_pejabat"				=>$n_pejabat,
								"e_penghargaan"				=>$e_penghargaan,
								"i_entry"			=>$userid);
			//var_dump($prmInsert);					  
			$hasil = $this->auditor_serv->insertPenghargaan($prmInsert);	
			$this->view->proses = "1";
			$this->view->keterangan = "Penghargaan";
			$this->view->status = $hasil;
		}
		$this->view->penghargaanListByNip = $this->auditor_serv->getPenghargaanListByNip($nip);
	}
	
	public function penghargaanupdateAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$nmHarga= $_REQUEST['nmHarga'];
		
		
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$paramCari = array("nip" => $nip,
					"nmHarga" => $nmHarga);
		$this->view->personalPenghargaanDetail = $this->auditor_serv->getPersonalPenghargaanDetail($paramCari);

		$this->view->perintah = $_REQUEST['perintah'];
		$perintahUpdate = $_POST['perintahUpdate'];
		if($perintahUpdate == "UPDATE")
		{
			$i_srt_penghargaan = $_POST['noSurat'];
			$n_penghargaanH = $_POST['nmHargaH'];
			$n_penghargaan = $_POST['nmHarga'];
			$d_penghargaan = $_POST['d_penghargaan'];
			$n_pejabat = $_POST['nPejabat'];
			$e_penghargaan = $_POST['keterangan'];
			
			$prm = array("i_peg_nip"		=>$nip,
								"i_srt_penghargaan" 	=> $i_srt_penghargaan,
								"n_penghargaan"		=>$n_penghargaan,
								"n_penghargaanH"		=>$n_penghargaanH,
								"d_penghargaan"				=>$d_penghargaan,
								"n_pejabat"				=>$n_pejabat,
								"e_penghargaan"				=>$e_penghargaan,
								"i_entry"			=>$userid);
								  
			$hasil = $this->auditor_serv->updatePenghargaan($prm);	
			$this->view->proses = "2";
			$this->view->keterangan = "Penghargaan";
			$this->view->status = $hasil;
			unset($this->view->personalPenghargaanDetail);
		}
		unset($paramCari);
		
		$this->view->penghargaanListByNip = $this->auditor_serv->getPenghargaanListByNip($nip);
		$this->render('penghargaan');
	}
	
	public function hapuspenghargaanAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$nmHarga = $_REQUEST['nmHarga'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$paramDelete = array("i_peg_nip" => $nip,
					"n_penghargaan" => $nmHarga);
		$hasil = $this->auditor_serv->hapusPersonalPenghargaan($paramDelete);

		$this->view->proses = "3";
		$this->view->keterangan = "Penghargaan";
		$this->view->status = $hasil;
		$this->view->penghargaanListByNip = $this->auditor_serv->getPenghargaanListByNip($nip);
		$this->render('penghargaan');
	}
	
//===================HUKUMAN
	public function hukumanAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		
		$this->view->nip=$nip;
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		
		$perintah = $_POST['perintahSimpan'];
		if($perintah == "SIMPAN")
		{
			$nip = $_POST['nipH'];
			$i_hukuman = $_POST['noSurat'];
			$n_hukuman = $_POST['nmSP'];
			$c_hukuman = $_POST['jenisHukuman'];
			$d_hukuman = $_POST['d_hukuman'];
			
			$n_pejabat = $_POST['nPejabat'];
			$e_hukuman = $_POST['keterangan'];
			
			$prmInsert = array("i_peg_nip"		=>$nip,
								"i_hukuman" 	=> $i_hukuman,
								"n_hukuman"		=>$n_hukuman,
								"c_hukuman"		=>$c_hukuman,
								"d_hukuman"				=>$d_hukuman,
								"n_pejabat"				=>$n_pejabat,
								"e_hukuman"				=>$e_hukuman,
								"i_entry"			=>$userid);
								  
			$hasil = $this->auditor_serv->insertHukuman($prmInsert);	
			$this->view->proses = "1";
			$this->view->keterangan = "Hukuman";
			$this->view->status = $hasil;
		}
		$this->view->hukumanListByNip = $this->auditor_serv->getHukumanListByNip($nip);
	}
	
	public function hukumanupdateAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$nmSurat = $_REQUEST['nmSurat'];
		
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$paramCari = array("nip" => $nip,
					"nmSurat" => $nmSurat);
		$this->view->personalHukumanDetail = $this->auditor_serv->getPersonalHukumanDetail($paramCari);
		$this->view->perintah = $_REQUEST['perintah'];
		$perintahUpdate = $_POST['perintahUpdate'];
		if($perintahUpdate == "UPDATE")
		{
			$i_hukuman = $_POST['noSurat'];
			$n_hukumanH = $_POST['nmSPH'];
			$n_hukuman = $_POST['nmSP'];
			$c_hukuman = $_POST['jenisHukuman'];
			$d_hukuman = $_POST['d_hukuman'];
			$e_hukuman = $_POST['keterangan'];
			
			$prm = array("i_peg_nip"		=>$nip,
								"i_hukuman" 	=> $i_hukuman,
								"n_hukuman"		=>$n_hukuman,
								"n_hukumanH"		=>$n_hukumanH,
								"c_hukuman"		=>$c_hukuman,
								"d_hukuman"				=>$d_hukuman,
								"e_hukuman"				=>$e_hukuman,
								"i_entry"			=>$userid);
			
//var_dump($prm);			
			$hasil = $this->auditor_serv->updateHukuman($prm);	
			$this->view->proses = "2";
			$this->view->keterangan = "Hukuman";
			$this->view->status = $hasil;
			unset($this->view->personalHukumanDetail);
		}
		unset($paramCari);
		
		$this->view->hukumanListByNip = $this->auditor_serv->getHukumanListByNip($nip);
		$this->render('hukuman');
	}
	
	public function hapushukumanAction()
	{
		$userid = $this->user;
		$nip = $_REQUEST['nip'];
		if(!$nip)
		{
			$nip = $_POST['nipH'];
		}
		$this->view->nip=$nip;
		$nmSurat = $_REQUEST['nmSurat'];
		
		//$this->view->personalList = $this->auditor_serv->getPersonalListByUser($nip, $nama, $stat, 1, 1);	
		$this->view->personalDetail = $this->auditor_serv->getPersonalDetail($nip);
		$paramDelete = array("i_peg_nip" => $nip,
					"n_hukuman" => $nmSurat);
		$hasil = $this->auditor_serv->hapusPersonalHukuman($paramDelete);

		$this->view->proses = "3";
		$this->view->keterangan = "Hukuman";
		$this->view->status = $hasil;
		$this->view->hukumanListByNip = $this->auditor_serv->getHukumanListByNip($nip);
		$this->render('hukuman');
	}
}
?>