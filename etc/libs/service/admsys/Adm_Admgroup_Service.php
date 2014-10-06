<?php
class Adm_Admgroup_Service {
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
	
	public function getModul() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select a.c_apl, a.i_modul, a.c_menu_level, a.n_menu, a.e_menu, a.c_menu_statuscb, a.n_resource, b.i_resource,
										cast(lpad(split_part(c_menu_level,'.',1),2,'0') as int), 
										cast(lpad(split_part(c_menu_level,'.',2),2,'0') as int) ,
										cast(lpad(split_part(c_menu_level,'.',3),2,'0') as int)	,
										cast(lpad(split_part(c_menu_level,'.',4),2,'0') as int), c.n_modul			 
										from e_menu_0_0_tr a
										left join (select n_resource, i_resource from e_sso_resource_0_tr ) b on (trim(b.n_resource) = trim(a.n_resource))
										left join (select i_modul, n_modul from e_modul_id_0_tr ) c on (trim(c.i_modul) = trim(a.i_modul))
										order by 2,9,10,11,12");

	 //print_r($result);
			$jmlresult = count($result);
			for ($j = 0; $j < $jmlresult; $j++) 
			{
				$hasilAkhir[$j] = array("c_apl"  =>(string)$result[$j]->c_apl,
										"i_modul"  =>(string)$result[$j]->i_modul,
										"n_modul"  =>(string)$result[$j]->n_modul,
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
	
	public function getDetailUserGroup($i_role) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 

			 $result = $db->fetchAll("select b.i_role, a.i_acl, a.n_role, a.n_resource, a.c_permission
									from e_sso_acl_0_tr a, e_sso_role_0_tr b
									where trim(a.n_role)= trim(b.n_role)
									and b.i_role = '$i_role'");
										
			$jmlresult = count($result);
			if($jmlresult > 0 )
			{
				for ($j = 0; $j < $jmlresult; $j++) 
				{
					$hasilAkhir[$j] = array("i_acl"  =>(string)$result[$j]->i_acl,
								"n_role"  =>(string)$result[$j]->n_role,
								"n_resource"  =>(string)$result[$j]->n_resource,
								"c_permission"  =>(string)$result[$j]->c_permission
								);
				}	
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
	
	public function insertGroupMenu(array $data) {
	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		    $db->beginTransaction();
			
			$nUserGroup = $data[0]['n_user_group'];
			$kategori = $data[0]['c_user_kategori'];
			$userid = $data[0]['i_entry'];
			
			//insert group baru di table e_sso_role_0_tr
			//====================================
			$i_role_max = $db->fetchOne("select max(i_role) from e_sso_role_0_tr");
			$iRole = $i_role_max + 1;

			$paramRole = array("i_role"  => $iRole,
							   "n_role"  => $nUserGroup,
							   "i_role_parent"=> null					   
							   );

			//var_dump($paramMenuGroup);
			$db->insert('e_sso_role_0_tr',$paramRole);
				
			$jmlData = count($data);
			for($a=0; $a<$jmlData; $a++)
			{
				$c_modul_menu_level = $data[$a]['c_modul_menu_level'];
				$c_modul_menu_levelArr = explode("_",$c_modul_menu_level);
				$c_modul = $c_modul_menu_levelArr[0];
				$c_menu_level = $c_modul_menu_levelArr[1];
				
				$c_menu_levelArr = explode(".",$c_menu_level);
				
				for($y=0; $y<count($c_menu_levelArr); $y++)
				{
					if($y == 0)
					{
						$level = $c_menu_levelArr[$y];
					}
					else
					{
						$level = $level.".".$c_menu_levelArr[$y];
					}
					$n_resource = $db->fetchOne("
												select trim(n_resource) as n_resource
												from e_menu_0_0_tr a
												where a.i_modul = '$c_modul'
													and a.c_menu_level = '$level'
												");
					$menu["$c_modul"."$level"] = $n_resource;
					
				}
				
			}
			
			$i_user_group = $db->fetchOne("select max(i_acl) from e_sso_acl_0_tr");
			$no_user_group = $i_user_group;
				
			foreach($menu as $key=>$value)
			{
				$n_resource = $value;
				$no_user_group = $no_user_group+1;
				
				$paramMenuGroup = array("i_acl"  => $no_user_group,
							   "n_role"  => $nUserGroup,
							   "n_resource"=> $n_resource,
							   "c_permission"   => "ALLOW"						   
							   );

			    //var_dump($paramMenuGroup);
				$db->insert('e_sso_acl_0_tr',$paramMenuGroup);
			}
			 
			 $db->commit();
			 
		    return 'sukses';			
		} catch (Exception $e) {
		$db->rollBack();
		echo $e->getMessage().'<br>';
		return 'gagal';
		}
	}
	
	public function updateGroupMenu(array $data) {
	
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		    $db->beginTransaction();
			
			$iRole = $data[0]['i_role'];
			$nRole = $data[0]['n_role'];
			$userid = $data[0]['i_entry'];
			
			//update e_sso_role_0_tr
			//=====================
			$paramRole = array("i_role"  => $iRole,
							   "n_role"  => $nRole,
							   "i_role_parent"=> null					   
							   );

			$whereRole = "i_role = '".$iRole."'";
			$db->update('e_sso_role_0_tr',$paramRole,$whereRole);
			
			$jmlData = count($data);
						
			for($a=0; $a<$jmlData; $a++)
			{
				$c_modul_menu_level = $data[$a]['c_modul_menu_level'];
				$c_modul_menu_levelArr = explode("_",$c_modul_menu_level);
				$c_modul = $c_modul_menu_levelArr[0];
				$c_menu_level = $c_modul_menu_levelArr[1];
				
				$c_menu_levelArr = explode(".",$c_menu_level);
				
				for($y=0; $y<count($c_menu_levelArr); $y++)
				{
					if($y == 0)
					{
						$level = $c_menu_levelArr[$y];
					}
					else
					{
						$level = $level.".".$c_menu_levelArr[$y];
					}
					$n_resource = $db->fetchOne("
												select trim(n_resource) as n_resource
												from e_menu_0_0_tr a
												where a.i_modul = '$c_modul'
													and a.c_menu_level = '$level'
												");
					$menu["$c_modul"."$level"] = $n_resource;
					//echo "$n_resource| $c_modul | $level<br>";
				}
				
			}

			/*  delete group dgn id yg dipilih */
			/*==========================*/
			$where[] = "n_role = '".$nRole."'";
			$db->delete('e_sso_acl_0_tr',$where);
			
			$i_user_group = $db->fetchOne("select max(i_acl) from e_sso_acl_0_tr");
			$no_user_group = $i_user_group;
				
			foreach($menu as $key=>$value)
			{
				$n_resource = $value;
				$no_user_group = $no_user_group+1;
				
				$paramMenuGroup = array("i_acl"  => $no_user_group,
							   "n_role"  => $nRole,
							   "n_resource"=> $n_resource,
							   "c_permission"   => "ALLOW"
							   );
				//var_dump($paramMenuGroup);
				//echo "<br>";
				$db->insert('e_sso_acl_0_tr',$paramMenuGroup);
			} 
			 
			 $db->commit();
			 
		    return 'sukses';			
		} catch (Exception $e) {
		$db->rollBack();
		echo $e->getMessage().'<br>';
		return 'gagal';
		}
		
	}

	
	public function findGroup(array $data, $pageNumber, $itemPerPage) {
		
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$n_user_group = $data['n_user_group'];

		$where = "UPPER(a.n_role) like '%$n_user_group%'";			
		 

									
		if(($pageNumber == 0) && ($itemPerPage == 0))
		{
			$hasilAkhir = $db->fetchOne("select count(*) 
									FROM 
									(
										select distinct a.i_role,a.n_role
										FROM e_sso_role_0_tr a, e_sso_acl_0_tr b
										where trim(a.n_role) = trim(b.n_role)
											AND $where
									) a
									");
								
		}
		else
		{
			$result = $db->fetchAll("select distinct a.i_role, a.n_role
									FROM e_sso_role_0_tr a, e_sso_acl_0_tr b
									where trim(a.n_role) = trim(b.n_role)
										AND $where 
									order by a.n_role");
								
		 
	         $jmlResult = count($result);
			 for ($j = 0; $j < $jmlResult; $j++) 
			 {
				$hasilAkhir[$j] = array("i_role"  =>(string)$result[$j]->i_role,
										"n_role"  =>(string)$result[$j]->n_role);
			 }	
		}
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'Data Tidak Ada <br>';
	   }
	}
	
	public function hapusGroupMenu(array $data) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

		 $whereAcl[] = "n_role = '".$data['n_role']."'";
	     $db->delete('e_sso_acl_0_tr',$whereAcl);
		 
		 $whereRole[] = "i_role = '".$data['i_role']."'";
	     $db->delete('e_sso_role_0_tr',$whereRole);
		 
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
