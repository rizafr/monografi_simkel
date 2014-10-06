<?php
class DataPhoto_Service {
    private static $instance;
  
    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }
public function getTmPhotoPegawai($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sql ="select id,n_nama, n_nip,a_photofile from tm_pegawai where 1=1 $cari order by n_nama desc "; 
			$result = $db->fetchAll($sql);
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("id" =>(string)$result[$j]->id,
								  "n_nama" =>(string)$result[$j]->n_nama,
								  "n_nip" =>(string)$result[$j]->n_nip,
								  "a_photofile" =>(string)$result[$j]->a_photofile									
									);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}
	
	public function updateData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $tambah_data = array(
								//"id_npm"=>$data['id_npm'],
								"a_photofile"=>$data['a_photofile']
			 );

		$where[] = " id = '".$data['id']."'";
			
	    $db->update('tm_pegawai',$tambah_data, $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	
	public function hapusData($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "id = '".$id."' ";
		 $db->delete('tm_pegawai', $where);
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
