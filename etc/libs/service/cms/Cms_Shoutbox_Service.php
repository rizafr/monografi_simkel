<?php
class Cms_shoutbox_Service {
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

	public function getshoutboxList()
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $xLimit=30;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT id,n_userid,n_name,n_message,d_entri from portal.tmshoutbox order by d_entri desc limit $xLimit offset 0";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT id,n_userid,n_nama,d_shoutbox,n_message,d_entri  from portal.tmshoutbox order by d_shoutbox $cari order by d_shoutbox desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 //echo "xxxxxxxxxxxxxxxxxxxx".$jmlResult;
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		  //echo "xxxx <br>".$result[$i]->id;
		   $output[$i] = array("id"=>(string)$result[$i]->id,
		                       "n_userid"=>(string)$result[$i]->n_userid,
		                       "n_name"=>(string)$result[$i]->n_name,
		                       "n_message"=>(string)$result[$i]->n_message,
		                       "d_entri"=>(string)$result[$i]->d_entri);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
 	public function getshoutboxallList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  portal.tmshoutbox  $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT id,n_userid,n_name,n_message,d_entri from portal.tmshoutbox $cari $orderby limit $xLimit offset $xOffset";
					$result = $db->fetchAll("$strQuery");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
					$data[$j] = array("id"=>(string)$result[$j]->id,
		                       "n_message"=>(string)$result[$j]->n_message,
		                       "n_userid"=>(string)$result[$j]->n_userid,
		                       "d_entri"=>(string)$result[$j]->d_entri,
		                       "n_name"=>(string)$result[$j]->n_name);	
					}
			}							
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
    public function findshoutboxByKey($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT id,n_userid,n_name,n_message,d_entri  from portal.tmshoutbox where id='$id' ";
		 //echo $strQuery;
         $result = $db->fetchAll($strQuery);
		 
	     $output[] = array("id"=>(string)$result[0]->id,
		                       "n_userid"=>(string)$result[0]->n_userid,
		                       "n_name"=>(string)$result[0]->n_name,
		                       "n_message"=>(string)$result[0]->n_message,
		                       "d_entri"=>(string)$result[0]->d_entri);
	   return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}	
	public function maintainData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("n_userid"=>$data['n_userid'],		 
								"n_name"=>$data['n_name'],
								"n_message"=>$data['n_message'],
								"d_entri"=>$data['d_entri']);
		$db->insert('portal.tmshoutbox',$maintain_data);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function maintainDataedit(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("n_userid"=>$data['n_userid'],		 
								"n_name"=>$data['n_name'],
								"n_message"=>$data['n_message'],
								"d_entri"=>$data['d_entri']);
		$db->update('portal.tmshoutbox',$maintain_data, "id = '".trim($data['id'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}		
	public function maintainHapusData($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			
		$db->fetchOne("delete from  portal.tmshoutbox  where id='$id'");
		$db->commit();
		return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
 	public function getMaxId() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{		
				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);			
				$data = $db->fetchOne("select count(*) from  portal.tmshoutbox ");
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	
}
?>