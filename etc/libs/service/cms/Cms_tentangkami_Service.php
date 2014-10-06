<?php
class Cms_tentangkami_Service {
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

	public function gettentangkamiPubList($cari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT c_tentangkami,n_judul,n_detil,c_status  from portal.tmtentangkami where c_status=1 $cari";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT c_tentangkami,n_judul,n_detil,c_status  from portal.tmtentangkami order by d_tentangkami $cari order by d_tentangkami desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_tentangkami"=>(string)$result[$i]->c_tentangkami,
		                       "n_judul"=>(string)$result[$i]->n_judul,
		                       "n_detil"=>(string)$result[$i]->n_detil,
		                       "c_status"=>(string)$result[$i]->c_status);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
 	public function gettentangkamiList() 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_tentangkami,n_judul,n_detil,c_status  from portal.tmtentangkami ";
					$result = $db->fetchAll("$strQuery");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_tentangkami"=>(string)$result[$j]->c_tentangkami,
		                       "n_judul"=>(string)$result[$j]->n_judul,
		                       "n_detil"=>(string)$result[$j]->n_detil,
		                       "c_status"=>(string)$result[$j]->c_status);	
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

    public function findtentangkamiByKey($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_tentangkami,n_judul,n_detil,c_status  from portal.tmtentangkami where c_tentangkami='$id' ");
		 
	     $output[] = array("c_tentangkami"=>(string)$result[0]->c_tentangkami,
		                       "n_judul"=>(string)$result[0]->n_judul,
		                       "n_detil"=>(string)$result[0]->n_detil,
		                       "c_status"=>(string)$result[0]->c_status);
	   return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	
	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $maintain_data = array("c_tentangkami"=>$data['c_tentangkami'],
								"n_judul"=>$data['n_judul'],		 
								"n_detil"=>$data['n_detil'],
								"c_status"=>$data['c_status'],
								"i_entri"=>$data['i_entri'],
								"d_entri"=>$data['d_entri']);
		if ($par=='insert'){$db->insert('portal.tmtentangkami',$maintain_data);}
		if ($par=='update'){$db->update('portal.tmtentangkami',$maintain_data, "c_tentangkami = '".trim($data['c_tentangkami'])."' ");}	 
		if ($par=='delete'){$db->delete('portal.tmtentangkami', "c_tentangkami = '".trim($data['c_tentangkami'])."'  and i_entri = '".trim($data['i_entri'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function maintainHapusData($idtentangkami,$userlogin) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$maintain_data = array("c_stat_aktivasi"=>"D");
		$db->fetchOne("delete from  portal.tmtentangkami  where c_tentangkami='$idtentangkami' and i_entri='$userlogin'");
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
				$data = $db->fetchOne("select count(*) from  portal.tmtentangkami ");
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	
}
?>