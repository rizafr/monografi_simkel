<?php
class Admsys_Admutama_Service {
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

	// check apakah user admin utama ada
	public function isUserUtamaExist($userid)
	{
		$registry = Zend_Registry::getInstance($userid);
		$db = $registry->get('db');

		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchOne("SELECT count(*) from a_sso_user_0_tr where i_ssouser_userid = '$userid'");

			 return $result;

		 } catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'Data tidak ada <br>';
		}
	}
	
	public function updateUserUtama(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

		
	  	 for($i=0; $i<count($data); $i++)
		 {
			$param = array("i_ssouser_email"  =>$data[$i]['i_ssouser_email'],
				   "i_ssouser_telp"         =>$data[$i]['i_ssouser_telp']);
	 
			$where[] = "i_ssouser_userid = '".$data[$i]['i_ssouser_userid']."'";

			$db->update('a_sso_user_0_tr',$param, $where);
		 }		 
	     
		 $db->commit();
		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function insertUserUtama(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

		
	  	 for($i=0; $i<count($data); $i++)
		 {
			$param = array("i_ssouser_appl" => $data[$i]['i_ssouser_appl'],
							"i_ssouser_role" => $data[$i]['i_ssouser_role'],
							"i_ssouser_userid" => $data[$i]['i_ssouser_userid'],
							"i_bumn" => $data[$i]['i_bumn'],
							"i_ssouser_email"  =>$data[$i]['i_ssouser_email'],
							"i_ssouser_telp"         =>$data[$i]['i_ssouser_telp'],
							"i_entry"         =>$data[$i]['i_entry'],
							"d_entry"         =>date('Y-m-d'));

			$db->insert('a_sso_user_0_tr',$param);
		 }		 
	     
		 $db->commit();
		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function findBUMN($data, $pageNumber, $itemPerPage) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$keyword = strtoupper($data);
			if(($pageNumber == 0) && ($itemPerPage == 0))
			{
				$hasilAkhir = $db->fetchOne("SELECT count(*)
										FROM a_bumn_0_0_tr
										where UPPER(n_bumn) like '%$keyword%' and i_bumn != '9999'");
										
			}
			else
			{
				
					$xLimit=$itemPerPage;
			  		$xOffset=($pageNumber-1)*$itemPerPage;		
					$result = $db->fetchAll("SELECT i_bumn, n_bumn 
										FROM a_bumn_0_0_tr
										where UPPER(n_bumn) like '%$keyword%'
										 and i_bumn != '9999'
								order by i_bumn limit $xLimit offset $xOffset");
												
					$jmlresult = count($result);
					for ($j = 0; $j < $jmlresult; $j++) 
					{
						$hasilAkhir[$j] = array("i_bumn"  =>(string)$result[$j]->i_bumn,
												"n_bumn"  =>(string)$result[$j]->n_bumn);
					}
			}
						
	 //print_r($result);
				
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getUserAdmsysExist($i_bumn)
	{
		$registry = Zend_Registry::getInstance($userid);
		$db = $registry->get('db');

		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("SELECT * from a_sso_user_0_tr where 
									i_bumn = '$i_bumn' 
									and i_ssouser_appl = 'admsys' 
									and i_ssouser_role = 'admsys'
							 ");	
			$jmlresult = count($result);
			
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("i_ssouser_userid"  =>(string)$result[$j]->i_ssouser_userid,
										"i_bumn"  =>(string)$result[$j]->i_bumn,
										"i_ssouser_appl"  =>(string)$result[$j]->i_ssouser_appl,
										"i_ssouser_role"  =>(string)$result[$j]->i_ssouser_role,
										"i_ssouser_email"  =>(string)$result[$j]->i_ssouser_email,
										"i_ssouser_telp"  =>(string)$result[$j]->i_ssouser_telp);
			}
			return $hasilAkhir;

		 } catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'Data tidak ada <br>';
		}
	}
	
	public function getUserAdmsysBaru($iBUMN)
	{
		$registry = Zend_Registry::getInstance($userid);
		$db = $registry->get('db');

		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchOne("SELECT count(*) from a_sso_user_0_tr where i_bumn = '$iBUMN'");

			 return $result;

		 } catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'Data tidak ada <br>';
		}
	}

	public function insertUserAdmin($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	  	 for($i=0; $i<count($data); $i++)
		 {		 
			$param = array("i_ssouser_appl" => $data[$i]['i_ssouser_appl'],
							"i_ssouser_role" => $data[$i]['i_ssouser_role'],
							"i_ssouser_userid" => $data[$i]['i_ssouser_userid'],
							"i_bumn" => $data[$i]['i_bumn'],
							"i_ssouser_email"  =>$data[$i]['i_ssouser_email'],
							"i_ssouser_telp"         =>$data[$i]['i_ssouser_telp'],
							"i_entry"         =>$data[$i]['i_entry'],
							"d_entry"         =>date('Y-m-d'));
			$db->insert('a_sso_user_0_tr',$param);
		 }		 
	     
		 $db->commit();
		 unset($param);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function isUserSystemExist($userid, $aplikasi, $role, $i_bumn)
	{
		$registry = Zend_Registry::getInstance($userid);
		$db = $registry->get('db');

		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $result = $db->fetchOne("SELECT count(*) from a_sso_user_0_tr 
								 where i_ssouser_userid = '$userid'
								 and i_ssouser_appl = '$aplikasi'
								 and i_ssouser_role = '$role'
								 and i_bumn = '$i_bumn'");	
								 
//echo "SELECT count(*) from a_sso_user_0_tr where i_ssouser_userid = '$userid' and i_ssouser_appl = '$aplikasi' and i_ssouser_role = '$role'  and i_bumn = '$i_bumn'";								 
			 return $result;

		 } catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'Data tidak ada <br>';
		}
	}  
	
	 public function jmlUserAdmin($aplikasi, $role, $i_bumn)
	{
		$registry = Zend_Registry::getInstance($userid);
		$db = $registry->get('db');

		try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			 $result = $db->fetchOne("SELECT count(*) from a_sso_user_0_tr 
								 where i_ssouser_appl = '$aplikasi'
								 and i_ssouser_role = '$role'
								 and i_bumn = '$i_bumn'");	
								 
			 return $result;

		 } catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'Data tidak ada <br>';
		}
	} 
	
	public function updateUserAdmin(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	  	 for($i=0; $i<count($data); $i++)
		 {
			$param = array("i_ssouser_email"  =>$data[$i]['i_ssouser_email'],
				   "i_ssouser_telp"         =>$data[$i]['i_ssouser_telp'],
				   "i_entry"         =>$data[$i]['i_entry']);
	 
			$where[] = "i_ssouser_userid = '".$data[$i]['i_ssouser_userid']."'";

			$db->update('a_sso_user_0_tr',$param, $where);
		 }		 
		 $db->commit();
		 unset($param);
		 unset($where);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function deleteUserAdmin(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	  	 for($i=0; $i<count($data); $i++)
		 {
			$where[] = "i_ssouser_appl = '".$data[$i]['i_ssouser_appl']."'";
			$where[] = "i_ssouser_role = '".$data[$i]['i_ssouser_role']."'";
			$where[] = "i_ssouser_userid = '".$data[$i]['i_ssouser_userid']."'";
			$where[] = "i_bumn = '".$data[$i]['i_bumn']."'";

			$db->delete('a_sso_user_0_tr',$where);
		 }		 
		 $db->commit();
		 unset($where);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
////////////////////////////////	
	public function getAplikasi() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("SELECT i_modul, c_modul, n_modul FROM e_modul_id_0_tr");
	 
	 //print_r($result);
			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("i_modul"  =>(string)$result[$j]->i_modul,
										"c_modul"  =>(string)$result[$j]->c_modul,
										"n_modul"  =>(string)$result[$j]->n_modul);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getGroup() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("SELECT distinct n_role FROM e_sso_acl_0_tr");
	 
	 //print_r($result);
			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("n_role"  =>(string)$result[$j]->n_role);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getGroupTerpilih($userId) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$result = $db->fetchAll("select * from e_user_sso_0_tm where i_user = '$userId'");
	 
	 //print_r($result);
			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("n_user_group"  =>(string)$result[$j]->n_user_group);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getModul($aplId) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select a.c_apl, a.i_modul, a.c_menu_level, a.n_menu, a.e_menu, a.c_menu_statuscb, a.n_resource, b.i_resource,
										cast(lpad(split_part(c_menu_level,'.',1),2,'0') as int), 
										cast(lpad(split_part(c_menu_level,'.',2),2,'0') as int) ,
										cast(lpad(split_part(c_menu_level,'.',3),2,'0') as int)	,
										cast(lpad(split_part(c_menu_level,'.',4),2,'0') as int)			 
										from e_menu_0_0_tr a
										left join (select n_resource, i_resource from e_sso_resource_0_tr ) b on (trim(b.n_resource) = trim(a.n_resource))
										where i_modul= '$aplId'
										order by 1,9,10,11,12");

	 //print_r($result);
			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("c_apl"  =>(string)$result[$j]->c_apl,
										"i_modul"  =>(string)$result[$j]->i_modul,
										"c_menu_level"  =>(string)$result[$j]->c_menu_level,
										"n_menu"  =>(string)$result[$j]->n_menu,
										"e_menu"  =>(string)$result[$j]->e_menu,
										"c_menu_statuscb"  =>(string)$result[$j]->c_menu_statuscb,
										"n_resource"  =>(string)$result[$j]->n_resource,
										"i_resource"  =>(string)$result[$j]->i_resource);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getUser($user,$nuser,$nip,$dept) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select a.i_user, a.n_user, a.i_user_nip, b.c_unit_kerja
										from e_user_info_0_tm a, e_sdm_pegawai_0_tm b
										where a.i_user = '$user'
											AND a.i_user_nip = b.i_peg_nip
										order by a.n_user ");
	 
   /* echo "select a.i_user, a.n_user, a.i_user_nip, b.c_unit_kerja
										from e_user_info_0_tm a, e_sdm_pegawai_0_tm b
										where a.i_user = '$user'
											AND a.i_user_nip = b.i_peg_nip
										order by a.n_user "; */   

			$jmlresult = count($result);
			if($jmlresult > 0 )
			{
				for ($j = 0; $j < $jmlresult; $j++) 
				{
					$hasilAkhir[$j] = array("i_user"  =>(string)$result[$j]->i_user,
								"n_user"  =>(string)$result[$j]->n_user,
								"i_user_nip"  =>(string)$result[$j]->i_user_nip,
								"c_unit_kerja"  =>(string)$result[$j]->c_unit_kerja
								);
				}	
			}
			else
			{
				
				$hasilAkhir[0] = array("i_user"  =>$user,
							"n_user"  =>$nuser,
							"i_user_nip"  =>$nip,
							"n_user_dept"  =>$dept
						      );											
			}
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function isUserExist($user) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $jmlUser = $db->fetchOne("select count(*)
										from e_user_info_0_tm 
										where i_user = '$user'");
	 
 // echo "select i_user, n_user, i_user_nip, n_user_dept
										// from e_user_info_0_tm 
										// where i_user = '$user' 
										// order by n_user"; 
			
		     return $jmlUser;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getUserModulKategori($user) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select distinct i_user, c_modul, c_user_kategori
										from e_sso_user_0_tm 
										where i_user = '$user' 
									");
	 

			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("i_user"  =>(string)$result[$j]->i_user,
										"c_modul"  =>(string)$result[$j]->c_modul,
										"c_user_kategori"  =>(string)$result[$j]->c_user_kategori
										);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function getUserOtoritas($user, $aplId) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select i_user, c_modul, i_resource, c_user_kategori
										from e_sso_user_0_tm 
										where i_user = '$user' 
										AND c_modul = '$aplId'
									");
	

			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("i_user"  =>(string)$result[$j]->i_user,
										"c_modul"  =>(string)$result[$j]->c_modul,
										"c_user_kategori"  =>(string)$result[$j]->c_user_kategori,
										"i_resource"  =>(string)$result[$j]->i_resource
										);
			}	
		     return $hasilAkhir;
			 unset($hasilAkhir);
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data tidak ada <br>';
	   }
	}
	
	public function insertUserBaru(array $data, $datatoritas) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

		
		 for($a=0; $a<count($data); $a++)
		 {
			if($a == 0)
			{
				$param = array("i_user" => $data[$a]['i_user'],
					   "n_user"  => $data[$a]['n_user'],
					   "i_user_nip" => $data[$a]['i_user_nip'],
					   "n_user_dept" => "");
				//echo "TEST CONN ".$db->getConnection()."<br>";
				// echo "2<br>";
				// var_dump($param);

				$db->insert('e_user_info_0_tm',$param);
				
				//delete group yg ada dulu
				$where[] = "i_user = '".$data[$a]['i_user']."'";	 
				$db->delete('e_user_sso_0_tm',$where);
				
			}
			
			
			
			//insert group
			$paramGroup = array("i_user"  =>$data[$a]['i_user'],
								"n_user_group"=>$data[$a]['n_user_group']);
								
				// echo "3<br>";
								
// var_dump($paramGroup);								
			//echo "TEST CONN ".$db->getConnection()."<br>";
			$db->insert('e_user_sso_0_tm',$paramGroup);
			
			//insert otoritas
			
			//echo "insert otoritas";
			//var_dump($datatoritas);
			$jmlData = count($datatoritas);
		 
			$userid = $datatoritas[0]['i_user'];
			$c_modul = $datatoritas[0]['c_modul'];
			$i_entry = $datatoritas[0]['i_entry'];
			$c_user_kategori = $datatoritas[0]['c_user_kategori'];
			$i_peg_nip = $datatoritas[0]['i_peg_nip'];
			
//echo "$userid | $c_modul | $i_entry | $c_user_kategori | $i_peg_nip<br>";			
			$otoritas = array();

			for($x = 0; $x<$jmlData; $x++)
			{
			$i_resource = $datatoritas[$x]['i_resource'];
			//echo "resource = $i_resource<br>";

			$i_resourceArr = explode(".",$i_resource);

			//echo "sssss=".count($i_resourceArr)." | resource = ".$i_resourceArr[0];
			for($y=0; $y<count($i_resourceArr); $y++)
			{
				if($y == 0)
				{
					$level = $i_resourceArr[$y];
				}
				else
				{
					$level = $level.".".$i_resourceArr[$y];
				}
				
				if($level != "-1")
				{
					$i_resource = $db->fetchOne("
											select b.i_resource
											from e_menu_0_0_tr a, e_sso_resource_0_tr b
											where trim(a.n_resource) = trim(b.n_resource)
											and a.i_modul = '$c_modul'
											and a.c_menu_level = '$level'
											");
				}
				else
				{
					$i_resource = -1;
				}
				$otoritas["$level"] = $i_resource;
			}		
			}

			foreach($otoritas as $key=>$value)
			{
			$i_resource = $value;

			$paramOtoritas = array("i_user"  => $userid,
						   "c_modul"  => $c_modul,
						   "c_user_kategori" => $c_user_kategori,
						   "i_resource"=> $i_resource,
						   "i_peg_nip"   => $i_peg_nip,
						   "i_entry"   => $i_entry,						       
						   "d_entry"       =>date("Y-m-d"));
						   
			$db->insert('e_sso_user_0_tm',$paramOtoritas);
			}
			//end insert otoritas
			
		 }

		 $db->commit();
		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	
		
	public function findPegawai(array $data, $pageNumber, $itemPerPage) {
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$i_peg_nip = $data['i_peg_nip'];
		$n_peg = strtoupper($data['n_peg']);
		$i_orgb = strtoupper($data['i_orgb']);

		if($i_peg_nip)	
		{
			if($n_peg)
			{
				$where = "i_peg_nip like '%$i_peg_nip%' AND UPPER(n_peg) like '%$n_peg%'";
			}
			else
			{
				$where = "i_peg_nip like '%$i_peg_nip%'";
			}
		}
		else
		{
			if($n_peg)
                        {
                                $where = "UPPER(n_peg) like '%$n_peg%'";
                        }
                        else
                        {
				$where = "i_peg_nip like '%%' AND UPPER(n_peg) like '%%'";
                        }

		}
		 
		if(($pageNumber == 0) && ($itemPerPage == 0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) 
									FROM e_sdm_pegawai_0_tm 
									where $where
									AND UPPER(c_unit_kerja) like '%$i_orgb%'");
									
		}
		else
		{
			if($itemPerPage == 99)
			{
				$result = $db->fetchAll("select *
                                                         FROM e_sdm_pegawai_0_tm
                                                         where $where 
														 AND UPPER(c_unit_kerja) like '%$i_orgb%'
                                                         order by n_peg");

			}
			else
			{
				$xLimit=$itemPerPage;
		  		$xOffset=($pageNumber-1)*$itemPerPage;		
				$result = $db->fetchAll("select * 
							FROM e_sdm_pegawai_0_tm 
							where $where 
							AND UPPER(c_unit_kerja) like '%$i_orgb%'
							order by n_peg limit $xLimit offset $xOffset");
			} 
		

							
	         $jmlResult = count($result);
			 for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("i_peg_nip"  =>(string)$result[$j]->i_peg_nip,
										"n_peg"  =>(string)$result[$j]->n_peg,
										"n_jabatan"  =>(string)$result[$j]->n_jabatan,
										"i_orgb"  =>(string)$result[$j]->i_orgb,
										"unitKerja"	=>(string)$result[$j]->c_unit_kerja,
										"d_peg_pnilaiakhir" =>(string)$result[$j]->d_peg_pnilaiakhir);
			 }	
		}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function findGroup(array $data, $pageNumber, $itemPerPage) {
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$n_role = strtoupper($data['n_role']);

		$where = "n_role like '%$n_role%'";					
		 
		if(($pageNumber == 0) && ($itemPerPage == 0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) 
									FROM 
									(
										select distinct n_role from e_sso_acl_0_tr where $where
									) a");
									
		}
		else
		{
			
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;		
			$result = $db->fetchAll("select distinct n_role from e_sso_acl_0_tr where $where 
						order by n_role limit $xLimit offset $xOffset");
		 
	         $jmlResult = count($result);
			 for ($j = 0; $j < $jmlResult; $j++) {
								
				$hasilAkhir[$j] = array("n_role"  =>(string)$result[$j]->n_role);
			 }	
		}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function deleteUser(array $datainfo, array $dataotoritas) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

		 //delete dari table e_user_info_0_tm
		 //=============================
		 $whereinfo[] = "i_user = '".$datainfo['i_user']."'";
	     $db->delete('e_user_info_0_tm',$whereinfo);
		 
		 //delete dari table e_sso_user_0_tm
		 //==============================
		 $whereotoritas[] = "i_user = '".$dataotoritas['i_user']."'";
	     $db->delete('e_sso_user_0_tm',$whereotoritas);
		 
		 //delete dari table e_user_sso_0_tm
		 //==============================
		 $wheregroup[] = "i_user = '".$dataotoritas['i_user']."'";
	     $db->delete('e_user_sso_0_tm',$wheregroup);
		 
		 $db->commit();
		 unset($whereinfo);
		 unset($whereotoritas);
		 unset($wheregroup);
		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
}
?>
