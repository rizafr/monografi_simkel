<?php
class Portal_Useronline_Service {
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

	public function getUseronlineList($tm)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT DISTINCT (userid) from portal.tmuseronline where tm > '$tm' and status='ON' order by userid";
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $userid =(string)$result[$i]->userid;
		   $strQueryx ="select i_ym from adm.tm_user where userid = '$userid'";
		   //echo  $strQueryx;
		   $i_ym = $db->fetchOne($strQueryx);
		   $output[$i] = array("i_ym"=>$i_ym,"userid"=>$userid);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getUseronlineSum($tm)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $strQuery="SELECT count( DISTINCT (userid) ) from portal.tmuseronline where tm > '$tm' and status='ON'";
         $data = $db->fetchOne("$strQuery");
	     return $data;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}

 	public function maintainDatainsertOl(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("id"=>$data['id'],
								"userid"=>$data['userid'],		 
								"ip"=>$data['ip'],
								"tm"=>$data['tm'],
								"status"=>$data['status']);
		$db->insert('portal.tmuseronline',$maintain_data);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function maintainDataupdateOl(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("id"=>$data['id'],
								"tm"=>$data['tm'],
								"status"=>$data['status']);
		$db->update('portal.tmuseronline',$maintain_data, "id =  '".trim($data['id'])."' ");	 
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	

	
}
?>