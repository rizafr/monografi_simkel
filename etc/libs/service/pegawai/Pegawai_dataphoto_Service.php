<?php
class Pegawai_DataPhoto_Service {
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
			$sql ="select id_npm,n_mhs,a_photofile
										from tm_mahasiswa where 1=1 $cari order by id_npm desc "; 
				$result = $db->fetchAll("select id_npm,n_mhs,a_photofile
										from tm_mahasiswa where 1=1 $cari order by id_npm desc ");
			//echo $sql;							
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("id_npm" =>(string)$result[$j]->id_npm,
								  "n_mhs" =>(string)$result[$j]->n_mhs,
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
		//var_dump($tambah_data);
		//echo "npm---->".$data['id_npm'];
		$where[] = " id_npm = '".$data['id_npm']."'";
			
	    $db->update('tm_mahasiswa',$tambah_data, $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	
	public function hapusData($id_npm) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "id_npm = '".$id_npm."' ";
		 $db->delete('tm_photopegawai', $where);
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
