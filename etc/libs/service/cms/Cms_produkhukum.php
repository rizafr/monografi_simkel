<?php
class Cms_produkhukum_Service {
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

	
 	public function getprodukhukumList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{if(($currentPage==0) && ($numToDisplay==0))
			{$data = $db->fetchOne("select count(*) from  tm_produkhukum where 1=1 $cari");}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_produkhukum,c_kategori,n_judul,n_detil,n_file,c_status,i_entry,d_entry,i_nomor_produkhukum,d_tahun_produkhukum
								from tm_produkhukum where 1=1 $cari $orderby";// limit $xLimit offset $xOffset";
					//echo $strQuery;			
					$result = $db->fetchAll("$strQuery");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_kategori=$result[$j]->c_kategori;
						//echo "ckat=".$c_kategori;
						$strQueryx="SELECT n_judul FROM tr_kategoriprodukhukum WHERE c_kategori ='$c_kategori'";
						//echo $strQueryx;
						$nkategori= $db->fetchOne($strQueryx);
						//echo "n=".$nkategori;
						$data[$j] = array("n_kategori"=>$nkategori,
							 "c_kategori"=>(string)$result[$j]->c_kategori,
							   "c_produkhukum"=>(string)$result[$j]->c_produkhukum,
							   "n_judul"=>(string)$result[$j]->n_judul,
							   "n_detil"=>(string)$result[$j]->n_detil,
							   "n_file"=>(string)$result[$j]->n_file,
							   "d_tahun_produkhukum"=>(string)$result[$j]->d_tahun_produkhukum,
							   "i_nomor_produkhukum"=>(string)$result[$j]->i_nomor_produkhukum,
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
 	public function getprodukhukumTenPubList($cari) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{
				$xLimit=10;
				$xOffset=0;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_produkhukum,c_kategori,n_judul,n_detil,n_file,c_status,i_entry,d_entry,i_nomor_produkhukum,d_tahun_produkhukum
								from tm_produkhukum where c_status='1' $cari order by d_tahun_produkhukum";// limit $xLimit offset $xOffset";
					//echo $strQuery;			
					$result = $db->fetchAll("$strQuery");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_kategori=$result[$j]->c_kategori;
						//echo "ckat=".$c_kategori;
						$strQueryx="SELECT n_judul FROM tr_kategoriprodukhukum WHERE c_kategori ='$c_kategori'";
						//echo $strQueryx;
						$nkategori= $db->fetchOne($strQueryx);
						//echo "n=".$nkategori;
						$data[$j] = array("c_kategori"=>$nkategori,
							   "c_produkhukum"=>(string)$result[$j]->c_produkhukum,
							   "n_judul"=>(string)$result[$j]->n_judul,
							   "n_detil"=>(string)$result[$j]->n_detil,
							   "n_file"=>(string)$result[$j]->n_file,
							   "d_tahun_produkhukum"=>(string)$result[$j]->d_tahun_produkhukum,
							   "i_nomor_produkhukum"=>(string)$result[$j]->i_nomor_produkhukum,
							   "c_status"=>(string)$result[$j]->c_status);
					}
										
					return $data;
			} catch (Exception $e) 
			{
		         	echo $e->getMessage().'<br>';
			     	return 'Data tidak ada <br>';
			}
	}
	
 	public function getprodukhukumPubList($cari,$currentPage, $numToDisplay,$orderby) 
	{
			$registry = Zend_Registry::getInstance();
			$db = $registry->get('db');
			try 
			{if(($currentPage==0) && ($numToDisplay==0))
			{$data = $db->fetchOne("select count(*) from  tm_produkhukum where 1=1 $cari");}
			else		
			{	
				$xLimit=$numToDisplay;
				$xOffset=($currentPage-1)*$numToDisplay;				
				$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				    $strQuery="SELECT c_produkhukum,c_kategori,n_judul,n_detil,n_file,c_status,i_entry,d_entry,i_nomor_produkhukum,d_tahun_produkhukum
								from tm_produkhukum where c_status='1' $cari $orderby";// limit $xLimit offset $xOffset";
					//echo $strQuery;			
					$result = $db->fetchAll("$strQuery");
					$jmlResult = count($result);
					for ($j = 0; $j < $jmlResult; $j++) 
					{ 
						$c_kategori=$result[$j]->c_kategori;
						//echo "ckat=".$c_kategori;
						$strQueryx="SELECT n_judul FROM tr_kategoriprodukhukum WHERE c_kategori ='$c_kategori'";
						//echo $strQueryx;
						$nkategori= $db->fetchOne($strQueryx);
						//echo "n=".$nkategori;
						$data[$j] = array("c_kategori"=>$nkategori,
							   "c_produkhukum"=>(string)$result[$j]->c_produkhukum,
							   "n_judul"=>(string)$result[$j]->n_judul,
							   "n_detil"=>(string)$result[$j]->n_detil,
							   "n_file"=>(string)$result[$j]->n_file,
							   "d_tahun_produkhukum"=>(string)$result[$j]->d_tahun_produkhukum,
							   "i_nomor_produkhukum"=>(string)$result[$j]->i_nomor_produkhukum,
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
	     $maintain_data = array("c_kategori"=>$data['c_kategori'],
								"n_judul"=>$data['n_judul'],
								"c_status"=>$data['c_status'],
								"n_detil"=>$data['n_detil'],
								"n_file"=>$data['n_file'],
								"d_tahun_produkhukum"=>$data['d_tahun_produkhukum'],
								"i_nomor_produkhukum"=>$data['i_nomor_produkhukum'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>$data['d_entry']);
		if ($par=='insert'){$db->insert('tm_produkhukum',$maintain_data);}
		if ($par=='update'){$db->update('tm_produkhukum',$maintain_data, "c_produkhukum = '".trim($data['c_produkhukum'])."' ");}	 
		if ($par=='delete'){$db->delete('tm_produkhukum', "c_produkhukum = '".trim($data['c_produkhukum'])."'  and i_entry = '".trim($data['i_entry'])."' ");}
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getKategoriHukum() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT c_kategori,n_judul from tr_kategoriprodukhukum where c_status=1");						 
		$jmlResult = count($result);
	     return $result;;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
}
?>