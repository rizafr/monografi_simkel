<?php
class Kejadian_Service {
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
	// List Kejadian
	//======================================================================

	public function getKejadianList() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT * FROM [SIMKEL].[dbo].[m_kelurahan] K,[SIMKEL].[dbo].[mon_kelurahan] MK,[SIMKEL].[dbo].[mon_kejadian] 	KEJ 
					WHERE K.kd_kel = MK.kd_kel AND KEJ.kd_kel=Mk.kd_kel");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function cariKejadianList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$kd_kel			= $dataMasukan['kd_kel'];
		
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			if($kd_kel!='00'){ $hak = " AND K.kd_kel='$kd_kel'";}
			
			$whereOpt = " AND ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$group = "";
			$order = "";
			
			
			$sqlProses = "SELECT TOP 1000 kej.idx_kejadian,kej.kd_kel, kej.hari,kej.tanggal, kej.uraian,kej.waktu,kej.lokasi, kej.file_lampiran, kej.bulan,kej.tahun, kej.kerugian,kej.nominal,kej.tanggal_laporan, kej.pelapor,kej.keterangan,kej.lampiran, K.kelurahan
								 FROM [SIMKEL].[dbo].[m_kelurahan] K ,[SIMKEL].[dbo].[mon_kelurahan] MK,[SIMKEL].[dbo].[mon_kejadian] KEJ 
								 WHERE K.kd_kel= MK.kd_kel AND kej.kd_kel=MK.kd_kel".$hak.$where." order by kej.tanggal desc";	
			$sqlProses1 = $sqlProses.$group;
			// var_dump($sqlProses);
			if(($pageNumber==0) && ($itemPerPage==0)){	
				$sqlTotal = "select count(*) from ($sqlProses1) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);
			}else{
				$sqlData = $sqlProses1.$order ;//." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);				
			}
			
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array(
										"idx_kejadian"				=> (string)$result[$j]->idx_kejadian,
										"kd_kel"				=> (string)$result[$j]->kd_kel,
										"kelurahan"				=> (string)$result[$j]->kelurahan,
										"hari"		=> (string)$result[$j]->hari,
										"tanggal"		=> (string)$result[$j]->tanggal,
										"uraian"				=> (string)$result[$j]->uraian,
										"waktu"				=> (string)$result[$j]->waktu,
										"lokasi"				=> (string)$result[$j]->lokasi,										
										"kerugian"				=> (string)$result[$j]->kerugian,
										"tanggal_laporan"				=> (string)$result[$j]->tanggal_laporan,
										"nominal"				=> (string)$result[$j]->nominal,
										"pelapor"		=> (string)$result[$j]->pelapor,
										"keterangan"			=> (string)$result[$j]->keterangan,
										"lampiran"	=> (string)$result[$j]->lampiran,	
										"file_lampiran"	=> (string)$result[$j]->file_lampiran,	
										"bulan"	=> (string)$result[$j]->bulan,	
										"tahun"	=> (string)$result[$j]->tahun	
										);
			}
			return $hasilAkhir; 
			
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}

	public function cariKejadianList1(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= strToLower($dataMasukan['katakunciCari']);
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		$kelurahan	=     $dataMasukan['kelurahan'];

		if($kategoriCari == "") { $kategoriCari ="kelurahan";}		

			try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " AND K.kelurahan <> '' ";
			
			if($katakunciCari){
			$whereOptCar = " and lower($kategoriCari) like '%$katakunciCari%' ";
			}
					
			$where = $whereOptCar;
			//$order = " order by noreg ";

			$sqlProses = "SELECT * 
								FROM [SIMKEL].[dbo].[m_kelurahan] K ,[SIMKEL].[dbo].[mon_kelurahan] MK,[SIMKEL].[dbo].[mon_kejadian] KEJ 
								WHERE KEJ.kd_kel = MK.kd_kel AND K.kd_kel = MK.kd_kel ";	
			$sqlProses1 = $sqlProses.$order;
		//	echo $where;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses"." "."$where)";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses1.$where." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);
			}
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array(
										"idx_kejadian"				=> (string)$result[$j]->idx_kejadian,
										"kd_kel"				=> (string)$result[$j]->kd_kel,
										"kelurahan"				=> (string)$result[$j]->kelurahan,
										"hari"		=> (string)$result[$j]->hari,
										"tanggal"		=> (string)$result[$j]->tanggal,
										"uraian"				=> (string)$result[$j]->uraian,
										"waktu"				=> (string)$result[$j]->waktu,
										"lokasi"				=> (string)$result[$j]->lokasi,										
										"kerugian"				=> (string)$result[$j]->kerugian,
										"nominal"				=> (string)$result[$j]->nominal,
										"tanggal_laporan"		=> (string)$result[$j]->tanggal_laporan,
										"pelapor"		=> (string)$result[$j]->pelapor,
										"keterangan"			=> (string)$result[$j]->keterangan,
										"lampiran"	=> (string)$result[$j]->lampiran	,
										"file_lampiran"	=> (string)$result[$j]->file_lampiran	,
										"bulan"	=> (string)$result[$j]->bulan,
										"tahun"	=> (string)$result[$j]->tahun
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	public function kejadianInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();

		$paramInput	= array(	"kd_kel"		=> $dataMasukan['kd_kel'],
								"hari"			=> $dataMasukan['hari'],
								"tanggal"		=> $dataMasukan['tanggal'],
								"waktu"			=> $dataMasukan['waktu'],
								"uraian"		=> $dataMasukan['uraian'],
								"lokasi"		=> $dataMasukan['lokasi'],
								"kerugian"		=> $dataMasukan['kerugian'],
								"nominal"		=> $dataMasukan['nominal'],
								"tanggal_laporan"		=> $dataMasukan['tanggal_laporan'],
								"pelapor"		=> $dataMasukan['pelapor'],
								"keterangan"	=> $dataMasukan['keterangan'],
								"lampiran"		=> $dataMasukan['lampiran'],
								"file_lampiran"		=> $dataMasukan['file_lampiran'],
								"bulan"		=> $dataMasukan['bulan'],
								"tahun"		=> $dataMasukan['tahun']
							);
						
			
			// var_dump($paramInput);
			$db->insert('SIMKEL.dbo.mon_kejadian',$paramInput);
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


	public function detailKejadianById($kd_kel,$idx_kejadian) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = "  where KEJ.kd_kel = '$kd_kel' AND KEJ.idx_kejadian='$idx_kejadian' AND KEJ.kd_kel=MK.kd_kel AND K.kd_kel=Mk.kd_kel ";
			$sqlProses = "SELECT  KEJ.*, K.kelurahan 
							FROM SIMKEL.dbo.mon_kejadian KEJ ,SIMKEL.dbo.mon_kelurahan MK,SIMKEL.dbo.m_kelurahan K  ";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);
			//echo $sqlData;
			$hasilAkhir	= array(		
										"idx_kejadian"			=> (string)$result->idx_kejadian,
										"kd_kel"				=> (string)$result->kd_kel,
										"kelurahan"				=> (string)$result->kelurahan,
										"hari"					=> (string)$result->hari,
										"tanggal"				=> (string)$result->tanggal,
										"uraian"				=> (string)$result->uraian,
										"waktu"					=> (string)$result->waktu,
										"lokasi"				=> (string)$result->lokasi,										
										"kerugian"				=> (string)$result->kerugian,
										"nominal"				=> (string)$result->nominal,
										"tanggal_laporan"		=> (string)$result->tanggal_laporan,
										"pelapor"				=> (string)$result->pelapor,
										"keterangan"			=> (string)$result->keterangan,
										"lampiran"				=> (string)$result->lampiran,
										"file_lampiran"				=> (string)$result->file_lampiran
							);						
					
			return $hasilAkhir;		
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	

	
	public function kejadianUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			
			if($dataMasukan['file_lampiran']!= NULL){ 
					$paramInput	= array(	
								"uraian"		=> $dataMasukan['uraian'],
								"lokasi"		=> $dataMasukan['lokasi'],
								"kerugian"		=> $dataMasukan['kerugian'],
								"nominal"		=> $dataMasukan['nominal'],
								"tanggal_laporan"		=> $dataMasukan['tanggal_laporan'],
								"pelapor"		=> $dataMasukan['pelapor'],
								"keterangan"	=> $dataMasukan['keterangan'],
								"lampiran"		=> $dataMasukan['lampiran'],
								"file_lampiran"		=> $dataMasukan['file_lampiran'],
								"bulan"		=> $dataMasukan['bulan'],
								"tahun"		=> $dataMasukan['tahun']
							);
				} 
			else{
				$paramInput	= array("uraian"      => $dataMasukan['uraian'],
								"hari"			=> $dataMasukan['hari'],
								"tanggal"		=> $dataMasukan['tanggal'],
								"waktu"			=> $dataMasukan['waktu'],
								"lokasi"		=> $dataMasukan['lokasi'],
								"kerugian"		=> $dataMasukan['kerugian'],
								"nominal"		=> $dataMasukan['nominal'],
								"tanggal_laporan"		=> $dataMasukan['tanggal_laporan'],
								"pelapor"		=> $dataMasukan['pelapor'],
								"keterangan"	=> $dataMasukan['keterangan'],
								"bulan"		=> $dataMasukan['bulan'],
								"tahun"		=> $dataMasukan['tahun']
							);
			}
			 //var_dump($paramInput);
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND idx_kejadian = '".$dataMasukan['idx_kejadian']."' ";
			$db->update('SIMKEL.dbo.mon_kejadian',$paramInput, $where);
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


	public function kejadianHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " kd_kel = '".$dataMasukan['kd_kel']."' AND idx_kejadian = '".$dataMasukan['idx_kejadian']."' ";
			$db->delete('SIMKEL.dbo.mon_kejadian', $where);
			
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
