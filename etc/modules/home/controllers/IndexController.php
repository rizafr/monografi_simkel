<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/sso/enkripsi.php";
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
		$ssouser_id = new Zend_Session_Namespace('ssouser_id');
		$ssouser_idpswd = new Zend_Session_Namespace('ssouser_idpswd');
		
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		$this->group_user =$ssogroup->n_level;
		
		$this->user_paswd =$ssouser_idpswd->user_paswd;			
		$this->user_id =$ssouser_id->user_id;
		$this->group_id =$ssousergroup->group_id;

    }
	
    public function indexAction() {
		//indexAction default kepunyaan Home_IndexController dalam modul home
		
		$this->view->p = $_REQUEST['p'];
		$this->view->user_id = $_REQUEST['user_id'];
		$this->view->username = $this->sso_serv->getUsername($this->view->user_id);
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
	
		
		$key = "ini key rahasia loh";
		$username = base64_decrypt($_REQUEST['token1'],$key);
	    $passwd = base64_decrypt($_REQUEST['token2'],$key);		
		$this->view->username = $username  ;
		$this->view->passwd = $passwd  ;
		
		$par = $_POST['par'];
		$usergroup = $_POST['usergroup'];
		$this->view->usergroup=$_POST['usergroup'];
		$this->view->par=$_POST['par']; 
		$ubahpwd = $_POST['ubahpwd'];
		$user_id = $_POST['user_id'];
		$pwdBaru = $_POST['pwdBaru'];
		$ulangpwdBaru = $_POST['ulangpwdBaru'];
		if(!$ubahpwd){	
			
			if ($username && $passwd) {				
				
					$hasiluser = $this->sso_serv->getDataUser1($username,$passwd);
					//var_dump($hasiluser);
					if($hasiluser){//username, iduser, paskey, idlevel, nama
						$ssousergroup	= new Zend_Session_Namespace('ssousergroup');
						$ssouser_id		= new Zend_Session_Namespace('ssouser_id');///berdasarkan user group
						$ssogroup		= new Zend_Session_Namespace('ssogroup');///berdasarkan user group
						$ssouser_idpswd	= new Zend_Session_Namespace('ssouser_idpswd');///berdasarkan user group
						$ssousergroup->setExpirationSeconds(6000);	//uid, id, user_id, nama, email, group_id
						
						$username		= $hasiluser->user_id;	
						$user_id			= $hasiluser->user_id;	
						$group_id		= $hasiluser->group_id;	
						$nama			= $hasiluser->nama;	

						$ssogroup->username		= $hasiluser->user_id;	
						$ssogroup->user_id		= $hasiluser->user_id;	
						$ssogroup->group_id		= $hasiluser->group_id;	
						$ssogroup->nama			= $hasiluser->nama;	
						$ssogroup->kd_kel			= $hasiluser->kd_kel;	
						
						$this->view->username	= $ssogroup->user_id;
						$this->view->user_id	= $ssogroup->user_id;
						$this->view->group_id	= $ssogroup->group_id;
						$this->view->nama		= $ssogroup->nama;

						if (!$user_id){$user_id =$this->user_id;$group_id =$this->group_id;}

							$ssousergroup->group_id		= $group_id;
							$ssouser_id->user_id			= $user_id;
							$ssouser_idpswd->user_paswd  = $passwd;

							$this->view->group_id =$this->view->group_id;
							$this->view->user_id =$this->view->user_id;
							$this->view->username =$this->view->user_id;
						
							$this->runningtextAction();
							$this->view->nama = $ssogroup->nama;
							$this->view->kd_kel = $ssogroup->kd_kel;
							//$this->view->n_group = $this->adm_serv->getnamaGroup($group_id);
						
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
			$user_id = $_POST['user_id'];
			$pwdBaru = $_POST['pwdBaru'];
			$ulangpwdBaru = $_POST['ulangpwdBaru'];
			
			if($pwdBaru == $ulangpwdBaru){
				$encryptedPasswd = $pwdBaru;
				$dataMasukan = array("password" => $encryptedPasswd,
									 "user_id" => $user_id);
									 
				$hasil = $this->adm_serv->ubahPasswd($dataMasukan);
				$this->indexAction();
			    $this->render('index');
				$this->view->par=$_POST['par']; 
			}
			else{
				$_REQUEST['u'] = $user_id;
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
		$user_id		= $_POST['user_id'];  
		$pertanyaan	 = $_POST['pertanyaan']; 
		$jawab		 = $_POST['jawab'];
		$hash_secret = "adlina_azizan_muti";
		$uid         = md5(uniqid($hash_secret));
			
		$dataMasukan = array("user_id"		=>$user_id,
							 "pertanyaan"  	=>$pertanyaan,
							 "jawab"  		=>$jawab,
							 "uid"  		=>$uid
							);
		$cekdata = $this->pengguna_serv->cekDataUser($user_id);

			$mail = new Zend_Mail();

			$mailto = $user_id;
			$subjek = "Selamat datang di sistem E-Komite Etik Penelitian Kesehatan Fakultas Kedokteran Universitas Padjadjaran ";
			$isi    = "Selamat datang di sistem E-Komite Etik Penelitian Kesehatan Fakultas Kedokteran Universitas Padjadjaran ,<br>Pendaftaran anda berhasil.<br>Data login berikut ini dapat digunakan :<br>user_id : ".$user_id."<br>Password : ".substr($uid,5,6)." (perhatian !! password case sensitive harus sesuai huruf besar dan huruf kecilnya)<br>Setelah memasukan masukan user dan password, anda dapat melakukan proses pengisian data pribadi anda.<br>Setelah masuk ke sistem , anda juga dapat melakukan pengubahan password anda.<br>Terima kasih,<br>Administrator Sekretariat Unit Komite Etik Penelitian Kesehatan FK UNPAD";

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
						if($this->view->userInsert =='sukses'){$angInsert = $this->pengguna_serv->anggotaInsert($user_id);}
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
		$user_id		 = $_POST['user_id']; 
		$pertanyaan	 = $_POST['pertanyaan']; 
		$jawab		 = $_POST['jawab'];
		$hash_secret = "adlina_azizan_muti";
		$uid         = md5(uniqid($hash_secret));
			
		$dataMasukan = array("user_id"  	=>$user_id,
							 "pertanyaan"  	=>$pertanyaan,
							 "jawab"  	=>$jawab,
							 "uid"  	=>$uid
							);
			$mail = new Zend_Mail();

			$mailto = $user_id;
			$subjek = "Selamat datang di sistem E-Komite Etik Fakultas Kedokteran Universitas Padjadjaran ";
			$isi    = "Selamat datang di sistem E-Komite Etik Fakultas Kedokteran Universitas Padjadjaran ,<br>Permohonan penggantian Anda kami terima.<br>Login dan Password baru Anda adalah sebagai berikut :<br>user_id   : ".$user_id."<br>Password : ".substr($uid,5,6)."	(perhatian !! password case sensitive harus sesuai huruf besar dan huruf kecilnya)<br>Setelah masuk ke sistem , anda dapat melakukan pengubahan password anda.<br>Terima kasih,<br>Administrator Sekretariat Unit Komite Etik FK UNPAD";

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
		
		$this->view->nama = $ssogroup->nama;
		$this->view->kd_kel = $ssogroup->kd_kel;
										
	   $this->render('home');
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