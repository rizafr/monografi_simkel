<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once "service/sso/Sso_User_Service.php";
require_once 'Zend/Session/Namespace.php';

require_once "service/adm/Pengguna_Service.php";

class Home_IndexController extends Zend_Controller_Action {
    private $sso_serv;
	
    public function init() {
       // Local to this controller only; affects all actions, as loaded in init:
	   //UNTUK SETTING GLOBAL BASE PATH
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();	   
		$this->view->baseData = $registry->get('baseData');
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');	   
		$this->sso_serv = Sso_User_Service::getInstance();
		$this->pengguna_serv = Pengguna_Service::getInstance();
	
		$ssousergroup = new Zend_Session_Namespace('ssousergroup');
		$ssouserid = new Zend_Session_Namespace('ssouserid');
		$ssouseridpswd = new Zend_Session_Namespace('ssouseridpswd');
		
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		$this->group_user =$ssogroup->n_level;
		
		$this->user_paswd =$ssouseridpswd->user_paswd;			
		$this->userid =$ssouserid->userid;
		$this->c_group =$ssousergroup->c_group;

    }
	
    public function indexAction() {
		//indexAction default kepunyaan Home_IndexController dalam modul home
		$this->view->p = $_REQUEST['p'];
		$this->view->userid = $_REQUEST['u'];
		$this->view->username = $this->sso_serv->getUsername($this->view->userid);
		$request = $this->getRequest();   
		$ns = new Zend_Session_Namespace('HelloWorld'); 
	    
		if(!isset($ns->yourLoginRequest)){ 
			$ns->yourLoginRequest = 1; 
		}else{ 
			$ns->yourLoginRequest++; 
		} 
		//$this->runningtextAction();
		//$this->kritiklistAction();
		//$this->view->beritaPubList = $this->berita_serv->getBeritaPubList($cari);	
		$cari5 = "";

		//$this->view->pengadaanList 	= $this->pengadaan_serv->pengadaanList();
		//$this->view->penelitianList = $this->penelitian_serv->penelitianList();

		$this->view->checksess = $ns->yourLoginRequest;
    }
	
	public function mainAction() {
		$username = $_POST['user_login'];
	    $passwd = $_POST['pwd'];
		$par = $_POST['par'];
		$usergroup = $_POST['usergroup'];
		$this->view->usergroup=$_POST['usergroup'];
		$this->view->par=$_POST['par']; 
		$ubahpwd = $_POST['ubahpwd'];
		$userid = $_POST['userid'];
		$pwdBaru = $_POST['pwdBaru'];
		$ulangpwdBaru = $_POST['ulangpwdBaru'];
		if(!$ubahpwd){	
			
			if ($username && $passwd) {				
				
					$hasiluser = $this->sso_serv->getDataUser1($username,$passwd,$usergroup);
					if($hasiluser){//username, iduser, paskey, idlevel, nama
						$ssousergroup	= new Zend_Session_Namespace('ssousergroup');
						$ssouserid		= new Zend_Session_Namespace('ssouserid');///berdasarkan user group
						$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
						$ssouseridpswd	= new Zend_Session_Namespace('ssouseridpswd');///berdasarkan user group
						$ssousergroup->setExpirationSeconds(6000);	//uid, id, userid, nama, email, c_group
						
						$username		= $hasiluser->username;	
						$userid			= $hasiluser->userid;	
						$c_group		= $hasiluser->c_group;	
						$nama			= $hasiluser->nama;	

						$ssogroup->username		= $hasiluser->username;	
						$ssogroup->userid		= $hasiluser->userid;	
						$ssogroup->c_group		= $hasiluser->c_group;	
						$ssogroup->nama			= $hasiluser->nama;	
						
						$this->view->username	= $ssogroup->username;
						$this->view->userid	= $ssogroup->userid;
						$this->view->c_group	= $ssogroup->c_group;
						$this->view->nama		= $ssogroup->nama;

						if (!$userid){$userid =$this->userid;$c_group =$this->c_group;}

							$ssousergroup->c_group		= $c_group;
							$ssouserid->userid			= $userid;
							$ssouseridpswd->user_paswd  = $passwd;

							$this->view->c_group =$this->view->c_group;
							$this->view->userid =$this->view->userid;
							$this->view->username =$this->view->username;
						
							$this->runningtextAction();
							$this->view->n_nama = $ssogroup->n_nama;
							$this->view->NIP = $ssogroup->NIP;
							//$this->view->n_group = $this->adm_serv->getnamaGroup($c_group);
						//cari ultah
							$dultah=date(Y-m);
							$tgl=date(d);
							$bln=date(F);						
							$cari= " and DATENAME(d, d_tglLahir)='$tgl' and DATENAME(m, d_tglLahir)='$bln'  ";
						//$this->view->ultah = $this->adm_serv->getUltah($cari);
					}
					else
					{

						$this->view->log=$_POST['log'];
						$this->view->pwd=$_POST['pwd'];
						$this->view->pesanlogin ="salah";
						$this->view->pesanKesalahan = 'Nama Pengguna atau Kata Sandi Salah atau menu aplikasi ini bukan autorisasi anda';
						$this->indexAction();
						$this->render('index');
						$this->view->par=$_POST['par']; 
					}
				


		   } else {
		     $this->view->pesanlogin ="kosong";
			 $this->view->pesan = "User dan Password Kosong 2";
			 $this->indexAction();
		     $this->render('index');
			 $this->view->par=$_POST['par']; 
		  } 
		}
		else {
			$userid = $_POST['userid'];
			$pwdBaru = $_POST['pwdBaru'];
			$ulangpwdBaru = $_POST['ulangpwdBaru'];
			
			if($pwdBaru == $ulangpwdBaru){
				$encryptedPasswd = $pwdBaru;
				$dataMasukan = array("password" => $encryptedPasswd,
									 "userid" => $userid);
									 
				$hasil = $this->adm_serv->ubahPasswd($dataMasukan);
				$this->indexAction();
			    $this->render('index');
				$this->view->par=$_POST['par']; 
			}
			else{
				$_REQUEST['u'] = $userid;
				$_REQUEST['p'] = 'ubahpwd';
				$this->indexAction();
				$this->render('index');
				$this->view->par=$_POST['par']; 
				
			}
		}

    }
	
	public function kritiklistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari'];
		$katakunciCari 	= $_REQUEST['katakunciCari'];
		$sortBy			= 'NAMA';
		$sort			= 'desc';
		
		$dataMasukan = array(
							"kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 60;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totkritikList = $this->kritik_serv->cariKritikList($dataMasukan,0,0,0);
		$this->view->kritikList = $this->kritik_serv->cariKritikList($dataMasukan,$currentPage, $numToDisplay,$this->view->totkritikList);
		
	}
	public function runningtextAction() {
		$pageNumber 	= 1;
		$itemPerPage 	= 20;
		$kategoriCari 	= 'c_statusaktif';
		$katakunciCari 	= 'Y';
		$sortBy			= 'e_pengumuman';
		$sort			= 'asc';
		
		$dataMasukan = array("pageNumber" => $pageNumber,
							"itemPerPage" => $itemPerPage,
							"kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		//$this->view->pengumumanList = $this->pengumuman_serv->cariPengumumanList($dataMasukan);
	}
	
	public function headerAction() {
	  
    }

   public function daftarrekananAction() {
	  $this->view->pertanyaanList = $this->pertanyaan_serv->getPertanyaanList();
	  
    }
	public function daftarAction() {
		$userid		= $_POST['userid'];  
		$pertanyaan	 = $_POST['pertanyaan']; 
		$jawab		 = $_POST['jawab'];
		$hash_secret = "adlina_azizan_muti";
		$uid         = md5(uniqid($hash_secret));
			
		$dataMasukan = array("userid"		=>$userid,
							 "pertanyaan"  	=>$pertanyaan,
							 "jawab"  		=>$jawab,
							 "uid"  		=>$uid
							);
		$cekdata = $this->pengguna_serv->cekDataUser($userid);

			$mail = new Zend_Mail();

			$mailto = $userid;
			$subjek = "Selamat datang di sistem E-Komite Etik Penelitian Kesehatan Fakultas Kedokteran Universitas Padjadjaran ";
			$isi    = "Selamat datang di sistem E-Komite Etik Penelitian Kesehatan Fakultas Kedokteran Universitas Padjadjaran ,<br>Pendaftaran anda berhasil.<br>Data login berikut ini dapat digunakan :<br>userid : ".$userid."<br>Password : ".substr($uid,5,6)." (perhatian !! password case sensitive harus sesuai huruf besar dan huruf kecilnya)<br>Setelah memasukan masukan user dan password, anda dapat melakukan proses pengisian data pribadi anda.<br>Setelah masuk ke sistem , anda juga dapat melakukan pengubahan password anda.<br>Terima kasih,<br>Administrator Sekretariat Unit Komite Etik Penelitian Kesehatan FK UNPAD";

			$mail->setBodyHtml($isi);
			$mail->setFrom('kepk.fk.unpad@gmail.com', 'Komite Etik Penelitian Kesehatan FK UNPAD');
			$mail->addTo($mailto, 'Some Recipient');
			$mail->setSubject($subjek);

			if($cekdata['uid']){
				$this->view->userInsert = "Maaf Sudah Ada";
				} 
				else {
			
					if(!$mail->send()) {
						$this->view->userInsert = "Maaf email tidak terkirim";
					}
					else {
						$this->view->userInsert = $this->pengguna_serv->penggunaInsert($dataMasukan);
						if($this->view->userInsert =='sukses'){$angInsert = $this->pengguna_serv->anggotaInsert($userid);}
					}
				}
				$this->view->proses = '1';	
				$this->view->keterangan = 'User';
				$this->view->hasil = $this->view->userInsert;
		//var_dump($this->view->userInsert);
				$this->daftarrekananAction();
				$this->render('daftarrekanan');		
		
	  
    }

	public function lupapasswordAction() {
	  $this->view->pertanyaanList = $this->pertanyaan_serv->getPertanyaanList();
	  //var_dump($this->view->pertanyaanList);
    }
	public function lupaAction() {
		$userid		 = $_POST['userid']; 
		$pertanyaan	 = $_POST['pertanyaan']; 
		$jawab		 = $_POST['jawab'];
		$hash_secret = "adlina_azizan_muti";
		$uid         = md5(uniqid($hash_secret));
			
		$dataMasukan = array("userid"  	=>$userid,
							 "pertanyaan"  	=>$pertanyaan,
							 "jawab"  	=>$jawab,
							 "uid"  	=>$uid
							);
			$mail = new Zend_Mail();

			$mailto = $userid;
			$subjek = "Selamat datang di sistem E-Komite Etik Fakultas Kedokteran Universitas Padjadjaran ";
			$isi    = "Selamat datang di sistem E-Komite Etik Fakultas Kedokteran Universitas Padjadjaran ,<br>Permohonan penggantian Anda kami terima.<br>Login dan Password baru Anda adalah sebagai berikut :<br>userid   : ".$userid."<br>Password : ".substr($uid,5,6)."	(perhatian !! password case sensitive harus sesuai huruf besar dan huruf kecilnya)<br>Setelah masuk ke sistem , anda dapat melakukan pengubahan password anda.<br>Terima kasih,<br>Administrator Sekretariat Unit Komite Etik FK UNPAD";

			$mail->setBodyHtml($isi);
			$mail->setFrom('sitapurnadewi@gmail.com', 'Unit Komite Etik FK UNPAD');
			$mail->addTo($mailto, 'Some Recipient');
			$mail->setSubject($subjek);
			
			if(!$mail->send()) {
				//echo 'errrrrrrrrrrrrrrrrrrrrrrrrrroooor';
				$err++;
				$errmsg = "Mailer Error: " . $mail->ErrorInfo;
			}
			else {
				$this->view->userInsert = $this->pengguna_serv->penggunaGantiPasswd($dataMasukan);
				$err++;
				$errmsg = "Message sent!";
			}
		 
    }

	public function daftarpenelitianAction() {
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {
			$pageNumber = 1;
		}
	
		$itemPerPage = $_REQUEST['numToDisplay'];
		if(!$itemPerPage) {
			$itemPerPage 	= 20;
		}
	
		$this->view->numToDisplay = $itemPerPage;
		$this->view->currentPage = $pageNumber;
		
		if ( $_REQUEST['param1'])
			$this->view->cariiSb 			= $_REQUEST['param1'];
		else
			$this->view->cariiSb 			= $_REQUEST['cariiSb'];
	
		$sortBy			= 'start';
		$sort			= 'asc';

		$dataMasukan = array("kategoriCari" => 'n_judul',
							 "katakunciCari"=> trim($this->view->cariiSb),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort
		);
	
		$this->view->totalpenelitianList	= $this->penelitian_serv->caripenelitianList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage			= $itemPerPage;
		$this->view->penelitianList 		= $this->penelitian_serv->caripenelitianList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totalpenelitianList);
	  
    }

	public function depanAction() {
						
		
		
		$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
		$this->runningtextAction();
		
		$this->view->n_nama = $ssogroup->n_nama;
		$this->view->n_level =$ssogroup->n_level;	
		$this->view->c_group =$ssogroup->c_group;
										
	   $this->render('main');
    }
	public function loginAction(){
		// $this->view->login=$_GET['login'];
		$this->view->par=$_GET['par'];
		$this->view->usergroup=$_GET['usergroup'];
		$this->view->log=$_POST['log'];
		$this->view->pwd=$_POST['pwd'];
		
	}
	public function login2Action(){
		$this->view->par=$_GET['par'];
		$this->view->usergroup=$_GET['usergroup'];
		$this->render('indexaltr');
	}	
	public function pengadaanlistAction() {
		$pageNumber = $_REQUEST['currentPage'];
		if(!$pageNumber) {
			$pageNumber = 1;
		}
	
		$itemPerPage = $_REQUEST['numToDisplay'];
		if(!$itemPerPage) {
			$itemPerPage 	= 100;
		}
	
		$this->view->numToDisplay = $itemPerPage;
		$this->view->currentPage = $pageNumber;
		if ( $_REQUEST['param1'])
			$this->view->kategoriCariSb 	= $_REQUEST['param1'];
		else
			$this->view->kategoriCariSb 	= $_REQUEST['kategoriCariSb'];
	
		if ( $_REQUEST['param2'])
			$this->view->cariiSb 			= $_REQUEST['param2'];
		else
			$this->view->cariiSb 			= $_REQUEST['cariiSb'];
	
		$sortBy			= 'c_npwp';
		$sort			= 'asc';

		$dataMasukan = array("kategoriCari" => trim($this->view->kategoriCariSb),
							 "katakunciCari"=> trim($this->view->cariiSb),
							"sortBy"		=> $sortBy,
							"sort"			=> $sort,
			"tahun"		=> '-',
							"d_blokir"			=> '-'
		);
	
		$this->view->totalpengadaanList 	= $this->pengadaan_serv->caripengadaanList($dataMasukan, 0, 0, 0);
		$this->view->itemPerPage			= $itemPerPage;
		$this->view->pengadaanList 			= $this->pengadaan_serv->caripengadaanList($dataMasukan, $pageNumber, $itemPerPage, $this->view->totalpengadaanList);
	}
}
?>