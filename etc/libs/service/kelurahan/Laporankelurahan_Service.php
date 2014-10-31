<?php
class Laporankelurahan_Service {
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
	// List Monografi
	//======================================================================

	public function getCariLaporanList($kd_kel,$bulan,$tahun) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT K.kelurahan, P.[tahun] ,P.[bulan],P.[kd_kel]  ,P.[nip_lurah] ,P.[gol_lurah] ,P.[nama_lurah] ,P.[nama_seklur] FROM [SIMKEL].[dbo].[mon_personil] P, [SIMKEL].[dbo].[m_kelurahan] K WHERE P.kd_kel = K.kd_kel AND P.kd_Kel='$kd_kel' AND P.bulan='$bulan' AND P.tahun='$tahun' ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


    //======================================================================
    // List Kejadian
    //======================================================================

    public function getCariLaporanKejadianList($kd_kel,$bulan,$tahun) {
    
       $registry = Zend_Registry::getInstance();
       $db = $registry->get('db');
       try {
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $db->fetchAll("SELECT K.kelurahan, KEJ.*  FROM [SIMKEL].[dbo].[mon_kejadian] KEJ, [SIMKEL].[dbo].[m_kelurahan] K WHERE KEJ.kd_kel = K.kd_kel  AND KEJ.kd_kel='$kd_kel' AND KEJ.bulan='$bulan' AND KEJ.tahun='$tahun'"); 
		
        $jmlResult = count($result);
         return $result;
        } catch (Exception $e) {
         echo $e->getMessage().'<br>';
         return 'gagal';
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
