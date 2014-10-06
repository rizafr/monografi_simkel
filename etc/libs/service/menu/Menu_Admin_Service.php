<?php
class Menu_Admin_Service {
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
    public function getMenuDtl(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $c_apl = $data['c_apl'];
	   $i_modul = $data['i_modul'];
	   $c_menu_level = $data['c_menu_level'];
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_apl,i_modul,c_menu_level,n_menu,e_menu,c_menu_statuscb,".
		 "n_resource,n_onclick from e_menu_0_0_tr where c_apl='".$c_apl."' and i_modul=".
		 $i_modul." and c_menu_level='".$c_menu_level."'");
		 
		 $output = array("c_apl"=>(string)$result[0]->c_apl,
							   "i_modul"=>(string)$result[0]->i_modul,
							   "c_menu_level"=>(string)$result[0]->c_menu_level,
							   "n_menu"=>(string)$result[0]->n_menu,
							   "e_menu"=>(string)$result[0]->e_menu,
							   "c_menu_statuscb"=>(string)$result[0]->c_menu_statuscb,
							   "n_resource"=>(string)$result[0]->n_resource,
							   "n_onclick"=>(string)$result[0]->n_onclick);
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
    public function getMenu(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   $c_apl = $data['c_apl'];
	   $i_modul = $data['i_modul'];
	   $n_menu = $data['n_menu'];
	   $n_onclick = $data['n_onclick'];
	   $where = "";
	   if ($n_menu!="") $where .= " and n_menu like '%".$n_menu."%'";
	   if ($n_onclick!="") $where .= " and n_onclick like '%".$n_onclick."%'";
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         /* $result = $db->fetchAll("SELECT c_apl,i_modul,c_menu_level,n_menu,e_menu,c_menu_statuscb,".
		 "n_resource,n_onclick from e_menu_0_0_tr where c_apl='".$c_apl."' and i_modul=".
		 $i_modul.$where." Order by c_menu_level"); */
		 
		 $result = $db->fetchAll("select a.c_apl, a.i_modul, a.c_menu_level, a.n_menu, a.e_menu, a.c_menu_statuscb, a.n_resource, b.i_resource,
										cast(lpad(split_part(a.c_menu_level,'.',1),2,'0') as int), 
										cast(lpad(split_part(a.c_menu_level,'.',2),2,'0') as int) ,
										cast(lpad(split_part(a.c_menu_level,'.',3),2,'0') as int)	,
										cast(lpad(split_part(a.c_menu_level,'.',4),2,'0') as int),a.n_onclick			 
										from e_menu_0_0_tr a
										left join (select n_resource, i_resource from e_sso_resource_0_tr ) b on (trim(b.n_resource) = trim(a.n_resource))
										where i_modul= '$i_modul' $where
										order by 1,9,10,11,12
								");
		 
		 $jmlResult = count($result);

		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_apl"=>(string)$result[$i]->c_apl,
							   "i_modul"=>(string)$result[$i]->i_modul,
							   "c_menu_level"=>(string)$result[$i]->c_menu_level,
							   "n_menu"=>(string)$result[$i]->n_menu,
							   "e_menu"=>(string)$result[$i]->e_menu,
							   "c_menu_statuscb"=>(string)$result[$i]->c_menu_statuscb,
							   "n_resource"=>(string)$result[$i]->n_resource,
							   "n_onclick"=>(string)$result[$i]->n_onclick);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getModul()
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT i_modul,n_menu from e_menu_0_0_tr ".
         " where trim(c_menu_level)='1' order by i_modul ");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("i_modul"=>(string)$result[$i]->i_modul,
		                       "n_menu"=>(string)$result[$i]->n_menu);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getModulDesc($i_modul)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT c_modul,n_modul from e_modul_id_0_tr where i_modul=".$i_modul." order by i_modul");
		 $output = array("c_modul"=>(string)$result[0]->c_modul,
		                 "n_modul"=>(string)$result[0]->n_modul);
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getListModul()
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
         $result = $db->fetchAll("SELECT i_modul,c_modul,n_modul from e_modul_id_0_tr order by i_modul");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("i_modul"=>(string)$result[$i]->i_modul,
		                       "c_modul"=>(string)$result[$i]->c_modul,
		                       "n_modul"=>(string)$result[$i]->n_modul);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getAplikasiDesc($c_appl)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try 
	   {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $result = $db->fetchOne("SELECT e_appl from a_appl_0_0_tr where c_appl='".$c_appl."'");
	     return $result;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function getAplikasi()
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $result = $db->fetchAll("SELECT c_appl,c_appl_singkatan,e_appl from a_appl_0_0_tr order by c_appl");
		 $jmlResult = count($result);
		 for ($i=0; $i<$jmlResult; $i++)
		 {
		   $output[$i] = array("c_apl"=>(string)$result[$i]->c_appl,
		                       "c_appl_singkatan"=>(string)$result[$i]->c_appl_singkatan,
		                       "n_menu"=>(string)$result[$i]->e_appl);
		 }
	     return $output;
	   } catch (Exception $e) {
	     return 'gagal'.$e->getMessage();
	   }
	}
	public function insMenu(array $dataResource, $dataMenu, $dataAction) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $ssoresource_prm = array("i_resource" =>$dataResource['i_resource'],
		                       "n_resource"=>$dataResource['n_resource'],
	                            "i_resource_parent" =>$dataResource['i_resource_parent']);
	     $db->insert('e_sso_resource_0_tr',$ssoresource_prm);
		 
		 $menu_prm = array("c_apl" =>$dataMenu['c_apl'],
	                            "i_modul" =>$dataMenu['i_modul'],
	                            "c_menu_level" =>$dataMenu['c_menu_level'],
							    "n_menu" =>$dataMenu['n_menu'],
								"e_menu" =>$dataMenu['e_menu'],
								"c_menu_statuscb" =>$dataMenu['c_menu_statuscb'],
								"n_resource" =>$dataMenu['n_resource'],
								"n_onclick" =>$dataMenu["n_onclick"]);
	     $db->insert('e_menu_0_0_tr',$menu_prm);
		 
		 $ssoaction_prm = array("n_resource" =>$dataAction['n_resource'],
	                            "n_action" =>$dataAction['n_action']);
	     $db->insert('e_sso_action_0_tr',$ssoaction_prm);
		 
		 
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function updMenu(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $menu_prm = array("i_modul" =>$data['i_modul'],
	                            "c_menu_level" =>$data['c_menu_level'],
							    "n_menu" =>$data['n_menu'],
								"e_menu" =>$data['e_menu'],
								"c_menu_statuscb" =>$data['c_menu_statuscb'],
								"n_resource" =>$data['n_resource'],
								"n_onclick" =>$data["n_onclick"]);
		 $where[] = "c_apl = '".$data['c_apl']."'";
		 $where[] = "i_modul = ".$data['i_modul'];
	     $where[] = "c_menu_level = '".$data['c_menu_level']."'";
		 
	     $db->update('e_menu_0_0_tr', $menu_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function delMenu(array $data, $n_resource) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {

 	     $db->beginTransaction();
		 $iResource = $db->fetchOne("select i_resource from e_sso_resource_0_tr where n_resource = '$n_resource'");
		 
		 $where[] = "c_apl = '".$data['c_apl']."'";
		 $where[] = "i_modul = ".$data['i_modul'];
		 $where[] = "c_menu_level = '".$data['c_menu_level']."'";
	     //var_dump($data);
		 
		 $db->delete('e_menu_0_0_tr', $where);

		 
		 
		 //ambil no_resource daro e_sso_resource_0_tr
		 //=======================================
		 if($iResource)
		 {
			 $where2[] = "n_resource = '".$n_resource."'";
		     $db->delete('e_sso_action_0_tr',$where2);
			 $db->delete('e_sso_resource_0_tr',$where2);
			 $db->delete('e_sso_acl_0_tr',$where2);
		 
			 $where3[] = "i_resource = ".$iResource;
			 
			 $db->delete('e_sso_user_0_tm',$where3);
		 }
		 
		 $db->commit();
		 unset($where);
		 unset($where2);
		 unset($where3);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function updSsoAction(array $data)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ssoaction_prm = array("n_resource" =>$data['n_resource'],
	                            "n_action" =>$data['n_action']);
	     $where[] = "n_resource = '".$data['n_resource']."'";
	     $db->update('e_sso_action_0_tr', $ssoaction_prm, $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function insSsoAction(array $data)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ssoaction_prm = array("n_resource" =>$data['n_resource'],
	                            "n_action" =>$data['n_action']);
	     $db->insert('e_sso_action_0_tr',$ssoaction_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function delSsoAction($n_resource) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "n_resource = '".$n_resource."'";
	     $db->delete('e_sso_action_0_tr',$where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function getNewSsoResource()
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $result = $db->fetchOne("SELECT max(i_resource) from e_sso_resource_0_tr");
	     return $result;
	   } catch (Exception $e) {
	     return 0;
	   }
	}
	public function insSsoResource(array $data)
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ssoresource_prm = array("i_resource" =>$data['i_resource'],
		                       "n_resource"=>$data['n_resource'],
	                            "i_resource_parent" =>$data['i_resource_parent']);
	     $db->insert('e_sso_resource_0_tr',$ssoresource_prm);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function delSsoResource($n_resource) 
	{
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "n_resource = '".$n_resource."'";
	     $db->delete('e_sso_resource_0_tr',$where);
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