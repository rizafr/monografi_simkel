<?php
class Sso_User_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
	
	 public function getDataUser($userid,$paswd) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $rabkaklist = $db->fetchAll("SELECT * FROM [SIMKEL].[dbo].[user] where user_id = '$userid' and password=md5('$paswd')");	
	     return $rabkaklist;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}		
	public function getDataUser1($username,$paswd) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		// $username = md5($username);
		 $ktsandi = md5($paswd);
		 $sql = "SELECT * FROM [SIMKEL].[dbo].[user] U
					where U.user_id ='$username' and U.password ='$ktsandi'  ";
		// echo $sql;
		 $hasil = $db->fetchRow($sql);
		 return $hasil;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getDataUser3($username,$paswd,$usergroup) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $ktsandi = md5($paswd);
		 $sql = "SELECT * FROM user where username ='$username' and pass ='$ktsandi' ";
		// echo $sql;
		 $hasil = $db->fetchRow($sql);
		 return $hasil;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getIdsupp($uid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT id_supp FROM t_user where uid = '$uid' and c_status ='A'";
		//echo $sql;
		$result = $db->fetchRow($sql);
		$hasilAkhir = array("id_supp"  	=>(string)$result->id_supp);
	   return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getPendaftar($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $sql = "SELECT n_nama FROM tm_anggota where id_user = '$id' and (c_statusdelete != 'Y' or c_statusdelete is null) ";
		 //echo $sql;
         $result = $db->fetchRow($sql);
		 $hasilAkhir = array("n_nama"  	=>(string)$result->n_nama);
		 return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUser2($userid,$paswd) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT userid FROM tm_pegawai where userid = '$userid' and password=md5('$paswd') and c_status ='A'");		
		 if($hasil){
		 return $hasil;
		 }
	     return '0';
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function getUsername($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT * FROM  [SIMKEL].[dbo].[user] where user_id='$userid' ");	
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUserNama($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT user_id FROM  [SIMKEL].[dbo].[user]  where user_id='$userid'");	
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUserGroup($usergroup) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $ktsandi = md5($paswd);
		 
         $hasil = $db->fetchRow("select c_group from  [SIMKEL].[dbo].[user] where c_group in ($usergroup) and 
		 c_status ='A' ");
		 
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
}
?>