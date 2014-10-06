<?php
class Adm_Dds_Service {
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
	
//=======================SEARCH DATA =============================================
    /**
	 */
 	public function getTableList($modul) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		// $where[] = $nip;
		// $where[] = $nama;
		//echo "nip--> ".$nip;
		//echo "nama--> ".$nama;
		$result = $db->fetchAll("select distinct n_owner, n_table,e_table   from tmtable
		                         where n_owner != 'PORTAL PUBLIK'
		                         and n_owner like ?",$modul ); 
                                          	
				 
                $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
                $hasilAkhir[$j] = array("n_owner"  =>(string)$result[$j]->n_owner,
	                                 "n_table"   =>(string)$result[$j]->n_table,
	                                 "e_table"   =>(string)$result[$j]->e_table  );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

//==========
 	public function getDataList($modul) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		// $where[] = $nip;
		// $where[] = $nama;
		//echo "nip--> ".$nip;
		//echo "nama--> ".$nama;
		$result = $db->fetchAll("select distinct n_data,a.n_column, c_data_type,q_data_length,e_data
                                         from tmdata a,tmtable b
                                         where n_owner != 'PORTAL PUBLIK'
                                         and a.n_column=b.n_column
		                         and n_owner like ?",$modul ); 
                                          	
				 
                $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
                $hasilAkhir[$j] = array("n_data"  =>(string)$result[$j]->n_data,
	                                 "n_column"   =>(string)$result[$j]->n_column,
	                                 "c_data_type"   =>(string)$result[$j]->c_data_type,
	                                 "q_data_length"   =>(string)$result[$j]->q_data_length,
	                                 "e_data"   =>(string)$result[$j]->e_data );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

public function ntablesearch($ntabel,$etabel) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $where[] = 'PORTAL PUBLIK';
		 $where[] = '%'.$ntabel.'%';
		 $where[] = '%'.$etabel.'%';
		 $result = $db->fetchAll("select distinct n_owner, n_table,e_table   
		                          from tmtable
		                          where n_owner != ?
		                          and upper(n_table) like upper(?)  
		                          and upper(e_table) like upper(?)
		                          ",$where ); 
                                          	
				 
                $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
                $hasilAkhir[$j] = array("n_owner"  =>(string)$result[$j]->n_owner,
	                                 "n_table"   =>(string)$result[$j]->n_table,
	                                 "e_table"   =>(string)$result[$j]->e_table  );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
  
 //----------------
     public function getTabelBykode($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
								 
		$result = $db->fetchAll('select  n_owner,n_table,e_table,i_Data_pos,i_table_pk, 
                                         b.n_column , c_data_type , q_data_length ,  e_data
                                         from  tmdata   a, tmtable b
                                         where a.n_column=b.n_column
                                         and n_table = ?
                                         order by i_Data_pos
                                         ',$kode);
        
		$jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		  $hasilAkhir[$j] = array("n_owner"  	   =>(string)$result[$j]->n_owner,
	                                  "n_table"        =>(string)$result[$j]->n_table,
					  "e_table"        =>(string)$result[$j]->e_table,
					  "i_Data_pos"     =>(string)$result[$j]->i_Data_pos,
	                                  "i_table_pk"     =>(string)$result[$j]->i_table_pk,
					  "n_column"       =>(string)$result[$j]->n_column,
					  "c_data_type"    =>(string)$result[$j]->c_data_type,
					  "q_data_length"  =>(string)$result[$j]->q_data_length,
					  "e_data"  	   =>(string)$result[$j]->e_data );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
 
 //---------------- 
  public function ndatasearch($nkolom,$ekolom) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 $where[] = '%'.$nkolom.'%';
		 $where[] = '%'.$ekolom.'%';
		 $result = $db->fetchAll("select n_column , c_data_type ,q_data_length ,e_data   
		                          from tmdata 
		                          where upper(n_column) like upper(?)  
		                          and upper(e_data) like upper(?)
		                          ",$where ); 
                                          	
				 
                $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
                $hasilAkhir[$j] = array("n_column"  =>(string)$result[$j]->n_column,
	                                 "c_data_type"   =>(string)$result[$j]->c_data_type,
	                                 "q_data_length"   =>(string)$result[$j]->q_data_length ,
	                                 "e_data"   =>(string)$result[$j]->e_data  );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
   //----------------
     public function getDataBykode($kode) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	  
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
								 
		$result = $db->fetchAll('select  b.n_column ,c_data_type ,q_data_length ,e_data,
                                         n_owner,n_table,e_table        
                                         from  tmdata   a, tmtable b
                                         where a.n_column=b.n_column
                                         and b.n_column = ?
                                         order by n_owner,n_table
                                         ',$kode);
        
		$jmlResult = count($result);
		 
		 for ($j = 0; $j < $jmlResult; $j++) {
		  $hasilAkhir[$j] = array("n_column"       =>(string)$result[$j]->n_column,
		                          "c_data_type"    =>(string)$result[$j]->c_data_type,
		                          "q_data_length"  =>(string)$result[$j]->q_data_length,
		                          "e_data"  	   =>(string)$result[$j]->e_data,
		                          "n_owner"  	   =>(string)$result[$j]->n_owner,
	                                  "n_table"        =>(string)$result[$j]->n_table,
					  "e_table"        =>(string)$result[$j]->e_table 
                                         );
								   
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
 
 //----------------
  
  

}
?>