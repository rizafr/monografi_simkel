<?php
class Cms_agenda_Service {
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

	public function getagendaPubList($cari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		//$xLimit=10;
		 $cari="and d_agenda >= now()";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT c_agenda,n_judul,n_detil,d_agenda,n_tempat,c_status  from portal.tmagenda where c_status=1 $cari order by d_agenda asc";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT c_agenda,n_judul,n_detil,d_agenda,n_tempat,c_status  from portal.tmagenda order by d_agenda $cari order by d_agenda desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_agenda"=>(string)$result[$i]->c_agenda,
		                       "n_judul"=>(string)$result[$i]->n_judul,
		                       "n_detil"=>(string)$result[$i]->n_detil,
		                       "d_agenda"=>(string)$result[$i]->d_agenda,
		                       "n_tempat"=>(string)$result[$i]->n_tempat,
		                       "c_status"=>(string)$result[$i]->c_status);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
 	public function getagendaList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  portal.tmagenda  $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_agenda,n_judul,n_detil,d_agenda,n_tempat,c_status  from portal.tmagenda $cari $orderby limit $xLimit offset $xOffset";
					$result = $db->fetchAll("$strQuery");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_agenda"=>(string)$result[$j]->c_agenda,
		                       "n_judul"=>(string)$result[$j]->n_judul,
		                       "n_detil"=>(string)$result[$j]->n_detil,
		                       "d_agenda"=>(string)$result[$j]->d_agenda,
		                       "n_tempat"=>(string)$result[$j]->n_tempat,
		                       "c_status"=>(string)$result[$j]->c_status);	
					}
			}							
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}

    public function findagendaByKey($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_agenda,n_judul,n_detil,d_agenda,n_tempat,c_status  from portal.tmagenda where c_agenda='$id' ");
		 
	     $output[] = array("c_agenda"=>(string)$result[0]->c_agenda,
		                       "n_judul"=>(string)$result[0]->n_judul,
		                       "n_detil"=>(string)$result[0]->n_detil,
		                       "d_agenda"=>(string)$result[0]->d_agenda,
		                       "n_tempat"=>(string)$result[0]->n_tempat,
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

	     $maintain_data = array("n_judul"=>$data['n_judul'],		 
								"n_detil"=>$data['n_detil'],
								"n_tempat"=>$data['n_tempat'],
								"d_agenda"=>$data['d_agenda'],
								"c_status"=>$data['c_status'],
								"i_entri"=>$data['i_entri'],
								"d_entri"=>$data['d_entri']);
		if ($par=='insert'){$db->insert('portal.tmagenda',$maintain_data);}
		if ($par=='update'){$db->update('portal.tmagenda',$maintain_data, "c_agenda = '".trim($data['c_agenda'])."' ");}	 
		if ($par=='delete'){$db->delete('portal.tmagenda', "c_agenda = '".trim($data['c_agenda'])."'  and i_entri = '".trim($data['i_entri'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function maintainHapusData($idagenda,$userlogin) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$maintain_data = array("c_stat_aktivasi"=>"D");
		$db->fetchOne("delete from  portal.tmagenda  where c_agenda='$idagenda' and i_entri='$userlogin'");
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
				$data = $db->fetchOne("select count(*) from  portal.tmagenda ");
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	
}
?>