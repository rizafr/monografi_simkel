<?php
class Adm_Admuser_Service {
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
	public function getnamaGroup($group_id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sqlProses = "select a.keterangan from tr_groupuser a where id = '$group_id'";	
			$sqlData = $sqlProses; 
			$keterangan = $db->fetchOne($sqlData);				
			return $keterangan;					  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	
	public function getGroupListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll(" select  id, n_nama, n_level from tr_groupuser  where status ='a'  order by n_nama ");
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getnamaMahasiswa($userid) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sqlProses = "select a.n_mhs from tm_mahasiswa a where id_npm = '$userid'";	
			$sqlData = $sqlProses; 
			$n_mhs = $db->fetchOne($sqlData);				
			return $n_mhs;					  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
		
	public function getnamaDosen($userid) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$userid = trim($userid);
			$sqlProses = "select a.n_nama from tm_pegawai a where id = '$userid'";	
			$sqlData = $sqlProses; 
			$n_nama = $db->fetchOne($sqlData);				
			return $n_nama;					  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function getnidnDosen($userid) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sqlProses = "select a.i_nidn from tm_pegawai a where id = '$userid'";	
			$sqlData = $sqlProses; 
			$n_nama = $db->fetchOne($sqlData);				
			return $n_nama;					  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function cariUserList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');

		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		
		$divre			= $dataMasukan['divre'];
		$upt			= $dataMasukan['upt'];
		$level			= trim($dataMasukan['level']);

		//if($divre == "" || $divre == "-") {$divre	="-";}		
		if($upt == "" || $upt == "-") {$upt	="-";}		
		if($level == "" || $level == "-") {$level	="-";}	
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			$whereBase = " where status ='a'";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			$whereOpt1 = " c_divre = '$divre' ";
			$whereOpt2 = " c_upt = '$upt' ";
			$whereOpt3 = " c_group = '$level' ";
			
			if($katakunciCari){
			$whereOptCar = " and $kategoriCari like '%$katakunciCari%' ";
			}

			if( $divre != "-"){$whereBase= $whereBase." and ".$whereOpt1;} else {$whereBase= $whereBase;}
			if( $upt != "-"){$whereBase= $whereBase." and ".$whereOpt2;} else {$whereBase= $whereBase;}
			if( $level != "-"){$whereBase= $whereBase." and ".$whereOpt3;} else {$whereBase= $whereBase;}
			
			$whereOpt =$where1.$where2.$where3.$where4.$where5.$whereOptCar;
			$where = $whereBase.$whereOpt;
			$order = " order by username ";
			
			//$order = " order by id ";SELECT id, username, userid, n_nama, kd_status, c_group, grup, n_level, password, e_ket, keterangan, n_nip, c_upt, c_divre, c_statusdelete FROM  v_user
			$sqlProses = "SELECT id, username, userid, n_nama, kd_status, c_group, grup, n_level, password, e_ket, keterangan, n_nip, c_upt, dbo.FGetNamaUpt(c_upt) as n_upt, c_divre, c_statusdelete FROM  v_user".$where;	
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
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  			=>(string)$result[$j]->id,
										"username"  	=>(string)$result[$j]->username,
										"userid"  		=>(string)$result[$j]->userid,
										"c_upt"  		=>(string)$result[$j]->c_upt,
										"n_upt"  		=>(string)$result[$j]->n_upt,
										"c_divre"  		=>(string)$result[$j]->c_divre,
										"n_nama"  		=>(string)$result[$j]->n_nama,
										"n_nip"  		=>(string)$result[$j]->n_nip,
										"kd_status" 	=>(string)$result[$j]->kd_status,
										"password"  	=>(string)$result[$j]->password,
										"c_group"  		=>(string)$result[$j]->c_group,
										"grup"  		=>(string)$result[$j]->grup,
										"c_statusdelete"=>(string)$result[$j]->c_statusdelete,
										"i_entry"      	=>(string)$result[$j]->i_entry,
										"d_entry"      	=>(string)$result[$j]->d_entry
										);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getUserListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT * FROM tm_user where c_statusdelete != 'Y'");
				
		 
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function userInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("username"  	=>$dataMasukan['username'],
								"userid" 		=>$dataMasukan['userid'],
								"n_nip" 		=>$dataMasukan['n_nip'],
								"kd_status" 	=>$dataMasukan['kd_status'],
								"n_level" 		=>$dataMasukan['n_level'],
								"password"  	=>md5($dataMasukan['password']),
								"c_group"  		=>$dataMasukan['c_group']);	
			//var_dump($paramInput);
			
			$db->insert('tm_user',$paramInput);
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
				return "berhasil.";
			}
	   }
	}

	public function detailUserById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id = '$id' ";
			$sqlProses = "select id, username, userid, n_level, kd_status, (select c_upt from tm_pegawai where n_nip = tm_user.n_nip) as c_upt, password, c_group from tm_user";	
			$sqlData = $sqlProses.$where;

			$result = $db->fetchRow($sqlData);	
			$c_upt = (string)$result->c_upt;
			$c_area = $db->fetchOne("SELECT distinct Idwilayah FROM tr_kantor where nopend ='$c_upt' and status ='a' ");
			$hasilAkhir = array("id"  		    =>(string)$result->id,
								"userid"  		=>(string)$result->userid,
								"c_upt"  		=>(string)$result->c_upt,
								"c_area"  		=>$c_area,
								"username"  	=>(string)$result->username,
								"kd_status" 	=>(string)$result->kd_status,
								"n_level" 		=>(string)$result->n_level,
								"password"  	=>(string)$result->password,
								"c_group"  		=>(string)$result->c_group,
								"c_statusdelete"=>(string)$result->c_statusdelete,
								"i_entry"      	=>(string)$result->i_entry,
								"d_entry"      	=>(string)$result->d_entry
								);
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function userUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("username"  	=>$dataMasukan['username'],
								"userid" 		=>$dataMasukan['userid'],
								"kd_status" 	=>$dataMasukan['kd_status'],
								"n_level" 		=>$dataMasukan['n_level'],
								"c_group"  		=>$dataMasukan['c_group'],
								"password"  	=>md5($dataMasukan['password'])
				);
			//var_dump($dataMasukan);
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_user',$paramInput, $where);
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
	
	public function ubahStatus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_status" 	=>$dataMasukan['kd_status']);
				
			//var_dump($paramInput);
			$where[] = " id = '".$dataMasukan['userid']."'";
			
			$db->update('tm_user',$paramInput, $where);
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

	public function userHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_user',$paramInput, $where);
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
				return "sukses";
			}
	   }
	}
	
	public function ubahPasswd(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			//var_dump($dataMasukan);
			$paramInput = array("password"	=> $dataMasukan['password']);	
			$where[] = " id = '".$dataMasukan['id']."'";
			$db->update('t_user',$paramInput, $where);
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
	
public function getUltah($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nip,n_nama,n_gelar,n_tmplahir,d_tgllahir,n_jnskelamin,c_agama,
									c_goldarah,n_hobby, c_pendidikan, c_identitas, n_identitas, c_status, 
									c_bagian, c_jabatan, c_gol, c_jurusan, n_alamatrmh, i_kodepos, 
									c_propinsi, c_kota, i_telp, i_hp, n_email, i_entry, d_entry, 
									i_entry_update, d_entry_update,
									cast(Month(d_tglLahir)AS VARCHAR) as bulanlahir,
									DATENAME(d, d_tglLahir) as tanggallahir,
									DATENAME(yyyy, d_tglLahir) as tahunlahir,
									i_nik,i_nidn,c_pendidik,id 								
									from tm_pegawai where 1=1 $cari order by n_nama asc");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$c_jur=trim($result[$j]->c_jurusan);
				$n_jurusan = $db->fetchOne('select n_jur from tr_jurusan where n_singkatan = ?',$c_jur);				
				$data[$j] = array("i_nip"=>(string)$result[$j]->i_nip,
								"i_nik"=>(String)$result[$j]->i_nik,
								"n_nama"=>(string)$result[$j]->n_nama,
								"n_gelar"=>(string)$result[$j]->n_gelar,
								"n_tmplahir"=>(string)$result[$j]->n_tmplahir,
								"n_jnskelamin"=>(string)$result[$j]->n_jnskelamin,
								"n_jurusan"=>$n_jurusan ,
								"tanggallahir"=>(String)$result[$j]->tanggallahir,	
								"bulanlahir"=>(String)$result[$j]->bulanlahir,	
								"tahunlahir"=>(String)$result[$j]->tahunlahir);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
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
	
public function getTmUser($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select * from t_user where 1=1 $cari");
			//echo "select * from t_user where 1=1 $cari"; 
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {			
				$data[$j] = array("id"	=>(string)$result[$j]->id,
								"userid"	=>(string)$result[$j]->userid,
								"nama"		=>(string)$result[$j]->nama,
								"email"		=>(string)$result[$j]->email,
								"password"	=>(string)$result[$j]->password
								);
				}
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}		
}

?>
