<?php
class Analisispasien_Service {
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

	//======================================================================
	// List Analisispasien
	//======================================================================

	public function getAnalisispasienList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" SELECT id, n_nama FROM tr_otoritas_user order by n_nama ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function cariAnalisispasienList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= strToLower($dataMasukan['katakunciCari']);
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$t_awal			= $dataMasukan['t_awal'];
		$t_akhir		= $dataMasukan['t_akhir'];
		
		if($t_awal == "" || $t_awal == "-") {$t_awal	="-";}		
		if($t_akhir == "" || $t_akhir == "-") {$t_akhir	="-";}		


		//31-05-2014'
		if($t_awal != '-') {
		$bln = substr($t_awal, 3, 2);$tgl = substr($t_awal, 0, 2);$thn = substr($t_awal, 6, 4);
		$awal = $thn."-".$bln."-".$tgl; 
		}

		if($t_akhir != '-') {
		$bln = substr($t_akhir, 3, 2);$tgl = substr($t_akhir, 0, 2);$thn = substr($t_akhir, 6, 4);
		$akhir = $thn."-".$bln."-".$tgl; 
		}


		if($kategoriCari == "") { $kategoriCari ="n_nama";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where c_status <> '' and c_status <> 'D' ";
			
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = "  (d_medrec BETWEEN ('$awal') AND ('$akhir'))";

			if($katakunciCari){
			$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";
			}

			if( $t_awal != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
			if( $t_akhir != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}

					
			$where = $whereBase.$whereOptCar;
			//echo $where;
			$groupby = "group by d_medrec";
			
			$sqlProses = "SELECT d_medrec, count(*) as jum_pasien FROM t_medrec ";	
			$sqlProses1 = $sqlProses.$order;
			$sqlProses0 = $sqlProses;
			

			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses0"." "."$where"."$groupby) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses0.$where.$groupby." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);
			}
			
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("d_medrec"				=> (string)$result[$j]->d_medrec,
										"jum_pasien"			=> (string)$result[$j]->jum_pasien
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function analisispasienInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
			$paramInput = array("kode_pasien"           => $dataMasukan['kode_pasien'],
								"n_nama"				=> $dataMasukan['n_nama'],
								"d_medrec"	            => $dataMasukan['d_medrec'],
								"b_badan"	            => $dataMasukan['b_badan'],
								"t_badan"	            => $dataMasukan['t_badan'],
								"n_tensi"	            => $dataMasukan['n_tensi'],
								"c_klasifikasi"	        => $dataMasukan['c_klasifikasi'],
								"c_tindakan"	        => $dataMasukan['c_tindakan'],
								"n_diagnosis"           => $dataMasukan['n_diagnosis'],
								"n_terapi"	            => $dataMasukan['n_terapi'],
								"c_hematologi"          => $dataMasukan['c_hematologi'],
								"c_kimiahati"			=> $dataMasukan['c_kimiahati'],
								"c_glukosa"				=> $dataMasukan['c_glukosa'],
								"c_cholesterol"         => $dataMasukan['c_cholesterol'],
								"c_alergi"				=> $dataMasukan['c_alergi'],
								"c_rematik"				=> $dataMasukan['c_rematik'],
								"v_hemoglobin"          => $dataMasukan['v_hemoglobin'],
								"v_leukosit"	        => $dataMasukan['v_leukosit'],
								"v_trombosit"           => $dataMasukan['v_trombosit'],
								"v_eritrosit"           => $dataMasukan['v_eritrosit'],
								"v_got"		            => $dataMasukan['v_got'],
								"v_gpt"		            => $dataMasukan['v_gpt'],
								"v_glukosa"	            => $dataMasukan['v_glukosa'],
								"v_cholesterol"         => $dataMasukan['v_cholesterol'],
								"v_igetotal"	        => $dataMasukan['v_igetotal'],
								"v_igeatopi"	        => $dataMasukan['v_igeatopi'],
								"n_igeket"	            => $dataMasukan['n_igeket'],
								"v_asto"		        => $dataMasukan['v_asto'],
								"v_anaif"	            => $dataMasukan['v_anaif'],
								"v_anaelisa"	        => $dataMasukan['v_anaelisa'],
								"v_letest"	            => $dataMasukan['v_letest'],
								"v_antidsdna"           => $dataMasukan['v_antidsdna'],
								"v_antiparietalsel"		=> $dataMasukan['v_antiparietalsel'],
								"v_imun"		        => $dataMasukan['v_imun'],
								"v_imunka"	            => $dataMasukan['v_imunka'],
								//"cuid"					=> $dataMasukan['cuid'],
								"cdate"					=> date('Y-m-d')
								);
			//var_dump($paramInput);
			$db->insert('t_medrec',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			$errMsg = $errmsgArr[0];
			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function detailAnalisispasienById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where d_medrec = '$id' ";
			$sqlProses = "SELECT * FROM t_medrec ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir = array("id"					=> (string)$result->id,
								"kode_pasien"           => (string)$result->kode_pasien,
								"n_nama"				=> (string)$result->n_nama,
								"d_medrec"	            => (string)$result->d_medrec,
								"c_klasifikasi"	        => (string)$result->c_klasifikasi,
								"c_tindakan"	        => (string)$result->c_tindakan,
								"n_diagnosis"           => (string)$result->n_diagnosis,
								"n_terapi"	            => (string)$result->n_terapi,
								"c_alergi"				=> (string)$result->c_alergi,
								"c_rematik"				=> (string)$result->c_rematik
								);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function AnalisispasienList($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where d_medrec = '$id' ";
			$sqlProses = "SELECT * FROM t_medrec ";	
			$sqlData = $sqlProses.$where;
			//echo $sqlData;
			$result = $db->fetchAll($sqlData);	
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) { 

			$c_klasifikasi = (string)$result[$j]->c_klasifikasi;
			$n_klasifikasi = $db->fetchOne("Select n_klasifikasi from tr_klasifikasi_med where id_klasifikasi ='$c_klasifikasi'");
			$c_tindakan = (string)$result[$j]->c_tindakan;
			$n_tindakan = $db->fetchOne("Select n_tindakan from tr_tindakan where id_tindakan ='$c_tindakan' ");
				
			$hasilAkhir[$j] = array("id"					=> (string)$result[$j]->id,
									"kode_pasien"           => (string)$result[$j]->kode_pasien,
									"n_nama"				=> (string)$result[$j]->n_nama,
									"d_medrec"	            => (string)$result[$j]->d_medrec,
									"c_klasifikasi"	        => (string)$result[$j]->c_klasifikasi,
									"c_tindakan"	        => (string)$result[$j]->c_tindakan,
									"n_klasifikasi"         => $n_klasifikasi,
									"n_tindakan"	        => $n_tindakan,
									"c_alergi"				=> (string)$result[$j]->c_alergi,
									"c_rematik"				=> (string)$result[$j]->c_rematik
								);
		}
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function rekapanList($t_awal,$t_akhir) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');

		if($t_awal != '-') {
		$bln = substr($t_awal, 3, 2);$tgl = substr($t_awal, 0, 2);$thn = substr($t_awal, 6, 4);
		$awal = $thn."-".$bln."-".$tgl; 
		}

		if($t_akhir != '-') {
		$bln = substr($t_akhir, 3, 2);$tgl = substr($t_akhir, 0, 2);$thn = substr($t_akhir, 6, 4);
		$akhir = $thn."-".$bln."-".$tgl; 
		}

		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where c_status <> '' and c_status <> 'D' and (d_medrec BETWEEN ('$awal') AND ('$akhir')) ";
			$sqlProses = "SELECT * FROM t_medrec ";	
			$sqlData = $sqlProses.$where;
			//echo $sqlData;
			$result = $db->fetchAll($sqlData);	
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) { 

			$c_klasifikasi = (string)$result[$j]->c_klasifikasi;
			$n_klasifikasi = $db->fetchOne("Select n_klasifikasi from tr_klasifikasi_med where id_klasifikasi ='$c_klasifikasi'");
			$c_tindakan = (string)$result[$j]->c_tindakan;
			$n_tindakan = $db->fetchOne("Select n_tindakan from tr_tindakan where id_tindakan ='$c_tindakan' ");
				
			$hasilAkhir[$j] = array("id"					=> (string)$result[$j]->id,
									"kode_pasien"           => (string)$result[$j]->kode_pasien,
									"n_nama"				=> (string)$result[$j]->n_nama,
									"d_medrec"	            => (string)$result[$j]->d_medrec,
									"c_klasifikasi"	        => (string)$result[$j]->c_klasifikasi,
									"c_tindakan"	        => (string)$result[$j]->c_tindakan,
									"n_klasifikasi"         => $n_klasifikasi,
									"n_tindakan"	        => $n_tindakan,
									"c_alergi"				=> (string)$result[$j]->c_alergi,
									"c_rematik"				=> (string)$result[$j]->c_rematik
								);
		}
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function analisispasienUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kode_pasien"           => $dataMasukan['kode_pasien'],
								"n_nama"				=> $dataMasukan['n_nama'],
								"d_medrec"	            => $dataMasukan['d_medrec'],
								"b_badan"	            => $dataMasukan['b_badan'],
								"t_badan"	            => $dataMasukan['t_badan'],
								"n_tensi"	            => $dataMasukan['n_tensi'],
								"c_klasifikasi"	        => $dataMasukan['c_klasifikasi'],
								"c_tindakan"	        => $dataMasukan['c_tindakan'],
								"n_diagnosis"           => $dataMasukan['n_diagnosis'],
								"n_terapi"	            => $dataMasukan['n_terapi'],
								"c_hematologi"          => $dataMasukan['c_hematologi'],
								"c_kimiahati"			=> $dataMasukan['c_kimiahati'],
								"c_glukosa"				=> $dataMasukan['c_glukosa'],
								"c_cholesterol"         => $dataMasukan['c_cholesterol'],
								"c_alergi"				=> $dataMasukan['c_alergi'],
								"c_rematik"				=> $dataMasukan['c_rematik'],
								"v_hemoglobin"          => $dataMasukan['v_hemoglobin'],
								"v_leukosit"	        => $dataMasukan['v_leukosit'],
								"v_trombosit"           => $dataMasukan['v_trombosit'],
								"v_eritrosit"           => $dataMasukan['v_eritrosit'],
								"v_got"		            => $dataMasukan['v_got'],
								"v_gpt"		            => $dataMasukan['v_gpt'],
								"v_glukosa"	            => $dataMasukan['v_glukosa'],
								"v_cholesterol"         => $dataMasukan['v_cholesterol'],
								"v_igetotal"	        => $dataMasukan['v_igetotal'],
								"v_igeatopi"	        => $dataMasukan['v_igeatopi'],
								"n_igeket"	            => $dataMasukan['n_igeket'],
								"v_asto"		        => $dataMasukan['v_asto'],
								"v_anaif"	            => $dataMasukan['v_anaif'],
								"v_anaelisa"	        => $dataMasukan['v_anaelisa'],
								"v_letest"	            => $dataMasukan['v_letest'],
								"v_antidsdna"           => $dataMasukan['v_antidsdna'],
								"v_antiparietalsel"		=> $dataMasukan['v_antiparietalsel'],
								"v_imun"		        => $dataMasukan['v_imun'],
								"v_imunka"	            => $dataMasukan['v_imunka'],
								//"muid"					=> $dataMasukan['muid'],
								"mdate"					=> date('Y-m-d')
								);	
			//var_dump($paramInput);
			$where[] = " id_medrec = '".$dataMasukan['id']."'";
			$db->update('t_medrec',$paramInput, $where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			$errMsg = $errmsgArr[0];
			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

		public function analisispasienFotoUpdate($id,$n_file) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_foto"           => $n_file);	
			//var_dump($dataMasukan['id']);
			$where[] = " id_medrec = '$id'";
			$db->update('t_medrec',$paramInput, $where);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			$errMsg = $errmsgArr[0];
			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}


	public function analisispasienHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_status" =>'D');	
			$where[] = " id_medrec = '".$dataMasukan['id']."'";
			$db->update('t_medrec',$paramInput, $where);
			
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}


public function limit2($sql, $count, $offset = 0,$total)
     {
        $count = intval($count);
        if ($count <= 0) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = intval($offset);
        if ($offset < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }
		$inner_sort = "desc";
		$outer_sort = "asc";
		$top2 = ($count+$offset);
		if(($count + $offset) > $total){
            //$offset = $total%$count;
			$offset = $total;
		    $inner_sort = "desc";
		    $outer_sort = "asc";
		    //$top2 = $offset;
			$top2 = $total;
			$count = $total%$count;
		}
        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
            //$sort  = (stripos($orderby, ' desc') !== false) ? 'desc' : 'asc';
            $order = str_ireplace('ORDER BY', '', $orderby);
            $order = trim(preg_replace('/\bASC\b|\bDESC\b/i', '', $order));
        }

        $sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . $top2 . ' ', $sql);

        $sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ' '.$inner_sort.') AS inner_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ';
            $sql .= $outer_sort;
        }
        $sql .= ') AS outer_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ' . $outer_sort;
        }

        return $sql;
    }
}
?>
