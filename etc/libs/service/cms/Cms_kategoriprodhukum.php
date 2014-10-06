<?php
class Cms_kategoriprodhukum_Service {
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

	public function getkategoriprodukhukumPubList($cari)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$xLimit=10;
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $strQuery="SELECT c_kategori,n_judul,c_status,i_entry,d_entry  from tr_kategoriprodukhukum where c_status=1 $cari order by n_judul ";
         //echo $strQuery;
		 //$result = $db->fetchAll("SELECT c_berita,n_judul,n_detil,d_berita,n_sumber,c_status  from portal.tmberita order by d_berita $cari order by d_berita desc");
         $result = $db->fetchAll("$strQuery");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_kategori"=>(string)$result[$i]->c_kategori,
		                       "n_judul"=>(string)$result[$i]->n_judul);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}    
	
 	public function getkategoriprodhukumList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{if(($currentPage==0) && ($numToDisplay==0))
			{$data = $db->fetchOne("select count(*) from  tr_kategoriprodukhukum where 1=1 $cari");}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_kategori,n_judul,c_status,i_entry,d_entry  from tr_kategoriprodukhukum where 1=1 $cari $orderby";// limit $xLimit offset $xOffset";
					$result = $db->fetchAll("$strQuery");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$data[$j] = array("c_kategori"=>(string)$result[$j]->c_kategori,
		                       "n_judul"=>(string)$result[$j]->n_judul,
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

	
	public function maintainData(array $data,$par) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $maintain_data = array(
								"n_judul"=>$data['n_judul'],
								"c_status"=>$data['c_status'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>$data['d_entry']);
		if ($par=='insert'){$db->insert('tr_kategoriprodukhukum',$maintain_data);}
		if ($par=='update'){$db->update('tr_kategoriprodukhukum',$maintain_data, "c_kategori = '".trim($data['c_kategori'])."' ");}	 
		if ($par=='delete'){$db->delete('tr_kategoriprodukhukum', "c_kategori = '".trim($data['c_kategori'])."'  and i_entry = '".trim($data['i_entry'])."' ");}
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