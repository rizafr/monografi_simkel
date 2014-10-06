<?php
class Cms_Berita_Service {
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

	public function getBeritaPubList($cari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$xLimit=10;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT c_berita, dbo.FGetTglMachineToHuman(d_berita) as d_berita1, d_berita, n_judul_berita, n_intro_berita, n_text_berita, n_image_berita, n_file_berita, status, c_type_berita, d_pub_berita  from t_berita where status='a' $cari order by d_berita desc";// limit $xLimit offset 0";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT c_berita,n_judul,n_detil,d_berita,n_sumber,c_status  from t_berita order by d_berita $cari order by d_berita desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_berita"=>(string)$result[$i]->c_berita,
		                       "n_judul"=>(string)$result[$i]->n_judul,
		                       "n_detil"=>(string)$result[$i]->n_detil,
		                       "d_berita"=>(string)$result[$i]->d_berita,
		                       "d_berita1"=>(string)$result[$i]->d_berita1,
		                       "n_sumber"=>(string)$result[$i]->n_sumber,
		                       "c_status"=>(string)$result[$i]->c_status);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
 	public function getBeritaList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				if(($currentPage==0) && ($numToDisplay==0))
			{
				$data = $db->fetchOne("select count(*) from  t_berita  $cari");
	
			}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_berita,n_judul,n_detil,dbo.FGetTglMachineToHuman(d_berita) as d_berita1,d_berita,n_sumber,c_status  from t_berita $cari $orderby ";//limit $xLimit offset $xOffset";
					$result = $db->fetchAll("$strQuery");
									
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_berita"=>(string)$result[$j]->c_berita,
		                       "n_judul"=>(string)$result[$j]->n_judul,
		                       "n_detil"=>(string)$result[$j]->n_detil,
		                       "d_berita1"=>(string)$result[$j]->d_berita1,
		                       "d_berita"=>(string)$result[$j]->d_berita,
		                       "n_sumber"=>(string)$result[$j]->n_sumber,
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

    public function findBeritaByKey($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_berita,n_judul,n_detil,dbo.FGetTglMachineToHuman1(d_berita) as d_berita1,d_berita,n_sumber,c_status  from t_berita where c_berita='$id' ");
		 
	     $output[] = array("c_berita"=>(string)$result[0]->c_berita,
		                       "n_judul"=>(string)$result[0]->n_judul,
		                       "n_detil"=>(string)$result[0]->n_detil,
		                       "d_berita1"=>(string)$result[0]->d_berita1,
		                       "d_berita"=>(string)$result[0]->d_berita,
		                       "n_sumber"=>(string)$result[0]->n_sumber,
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
								"n_sumber"=>$data['n_sumber'],
								"d_berita"=>$data['d_berita'],
								"c_status"=>$data['c_status'],
								"i_entri"=>$data['i_entri'],
								"d_entri"=>$data['d_entri']);
		if ($par=='insert'){$db->insert('t_berita',$maintain_data);}
		if ($par=='update'){$db->update('t_berita',$maintain_data, "c_berita = '".trim($data['c_berita'])."' ");}	 
		if ($par=='delete'){$db->delete('t_berita', "c_berita = '".trim($data['c_berita'])."'  and i_entri = '".trim($data['i_entri'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	public function maintainHapusData($idberita,$userlogin) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$maintain_data = array("c_stat_aktivasi"=>"D");
		$db->fetchOne("delete from  t_berita  where c_berita='$idberita' and i_entri='$userlogin'");
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
				$data = $db->fetchOne("select count(*) from  t_berita ");
				return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
	
}
?>