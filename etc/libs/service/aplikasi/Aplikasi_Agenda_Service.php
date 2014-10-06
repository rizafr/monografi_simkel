<?php
require_once "share/globalReferensi.php";

class Aplikasi_Agenda_Service {
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
	// List Pengumuman
	//======================================================================
	public function cariAgendaList(array $dataMasukan,$pageNumber,$itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	   
	  //var_dump($dataMasukan); 
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			$whereBase = "where c_statusdelete != 'Y' and c_statusdelete is not null";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			if($kategoriCari != "" && $katakunciCari != "" ) { 
				$where = $whereBase." and ".$whereOpt;
			} 
			else { 
				$where = $whereBase;
			}
			$order = " order by i_agenda ";
			$sqlProses = "select i_agenda, e_agenda_pesan,dbo.FGetTglMachineToHuman(d_agenda) as d_agenda from tm_agenda ".$where;	
			$sqlProses1 = $sqlProses.$order;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset,$total);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);	
			}
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_agenda"  	=>(string)$result[$j]->i_agenda,
									   "e_agenda_pesan"      		=>(string)$result[$j]->e_agenda_pesan,
									   "d_agenda"      		=>(string)$result[$j]->d_agenda
										);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function daftarPegawai(array $dataMasukan) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$pageNumber 	= $dataMasukan['pageNumber'];
		$itemPerPage 	= $dataMasukan['itemPerPage'];
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	   
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			$whereBase = "where c_statusdelete != 'Y' or c_statusdelete is null";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			if($kategoriCari) { $where = $whereBase." and ".$whereOpt;} 
			else { $where = $whereBase;}
			$order =  " order by nip ";
			$sqlProses = "select nip, nama from tm_pegawai ".$where;	
			$sqlProses1 = $sqlProses.$order;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses.$where) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				//$sqlData = $sqlProses.$where.$order." limit $xLimit offset $xOffset";
				$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset,$total);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);	
			}
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("nip"  	=>(string)$result[$j]->nip,
									   "nama"  	=>(string)$result[$j]->nama
										);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function agendaInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
										
			$paramInput = array("e_agenda_pesan"  =>$dataMasukan['eAgendaPesan'],
							   "d_agenda"  =>$dataMasukan['dAgenda'],
							   "i_entry"       	=>$dataMasukan['i_entry']);	

							   var_dump($paramInput);
			$db->insert('tm_agenda',$paramInput);
			$db->commit();
			$this->tulisEvent();
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

	public function detailAgendaById($iAgenda) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = "where i_agenda = '$iAgenda' ";
			$sqlProses = "select i_agenda, e_agenda_pesan, d_agenda from tm_agenda ";

			$sqlData = $sqlProses.$where;					
			
			$result = $db->fetchRow($sqlData);
			$hasilAkhir = array("i_agenda"  	=>(string)$result->i_agenda,
							   "e_agenda_pesan"      		=>(string)$result->e_agenda_pesan,
							   "d_agenda"      		=>(string)$result->d_agenda
								);
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function agendaUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
										
			$paramInput = array("e_agenda_pesan"  =>$dataMasukan['eAgendaPesan'],
							   "d_agenda"  =>$dataMasukan['dAgenda'],
							   "i_entry"       	=>$dataMasukan['i_entry'],
							   "d_entry"       	=>new Zend_Db_Expr('NOW()'));	
			$where[] = "i_agenda = '".$dataMasukan['iAgendaHidden']."'";
			
			var_dump($paramInput);
			echo "<br>";
			var_dump($where);
			
			$db->update('tm_agenda',$paramInput, $where);
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

	public function agendaHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y',
							   "i_entry"       	=>$dataMasukan['i_entry'],
							   "d_entry"       	=>new Zend_Db_Expr('NOW()'));	
								
			$where[] = "i_agenda = '".$dataMasukan['i_agenda']."'";
			
			$db->update('tm_agenda',$paramInput, $where);
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
	
	public function tulisEvent() {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$whereBase = "where c_statusdelete != 'Y' or c_statusdelete is null";
			
			$sqlProses = "select i_agenda, dbo.FGetTglMachineToHumanSlash(d_agenda) as d_agenda,e_agenda_pesan from tm_agenda where (c_statusdelete != 'Y' or c_statusdelete is null) ";	

			$sqlData = $sqlProses;
			$result = $db->fetchAll($sqlData);	
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			$myFile = "../etc/data/event.txt";
			if (file_exists($myFile)) {
				unlink($myFile);
			}
			$fh = fopen($myFile, 'w') or die("can't open file");
			//echo "myFile = $myFile";
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_agenda"  	=>(string)$result[$j]->i_agenda,
									   "d_agenda"  	=>(string)$result[$j]->d_agenda,
									   "e_agenda_pesan"      		=>(string)$result[$j]->e_agenda_pesan
										);
				$tglarr = explode('/', $result[$j]->d_agenda);
				
				$hari = ($tglarr[2]*1);
				$bulan = ($tglarr[1]*1);
				$tahun = $tglarr[0];
				
				$stringData = $result[$j]->d_agenda.";;".$result[$j]->e_agenda_pesan.";;\n";
				fwrite($fh, $stringData);
				//echo "$stringData <br>";
				
			}	
			fclose($fh);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
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
               $offset = $total%$count;
			   $inner_sort = "asc";
			   $outer_sort = "asc";
			   $top2 = $offset;
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