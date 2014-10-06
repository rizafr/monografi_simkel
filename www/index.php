<?php
set_include_path('.' . PATH_SEPARATOR . '../etc/libs'
                     . PATH_SEPARATOR . '../etc/data'
					 . PATH_SEPARATOR . 'C:/xampp/php/pear/ZendFramework-1.9.5/library'
	                 . PATH_SEPARATOR . get_include_path());
	 
require_once 'Zend/Loader.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Mail/Transport/Smtp.php';

Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Auth_Adapter_Exception');
Zend_Loader::loadClass('Zend_Auth_Result');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Controller_Router_Rewrite');
Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Controller_Router_Route_Module');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Debug');
Zend_Loader::loadClass('Zend_Session');
Zend_Loader::loadClass('Zend_Session_Namespace');
Zend_Loader::loadClass('Zend_Mail_Transport_Smtp');
Zend_Loader::loadClass('Zend_Mail');

//require_once '../etc/oa_service_loader.php';
$config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'kepk.fk.unpad@gmail.com', 'password' => 'fkunpad2014');
$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
Zend_Mail::setDefaultTransport($smtpConnection);

Zend_Session::start();

// load configuration
$config = new Zend_Config_Ini('../etc/config.ini', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);

// setup database
$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
$registry->set('db', $db);

//setup base path
$registry->set('basepath', '');
$registry->set('procpath', '/oaproc');
$registry->set('pathUPLD', '/home/oadev');
//$registry->set('baseData', '/home/oadev/etc/data');
$registry->set('baseData', '../etc/data');
$registry->set('baseData2', 'F:/bambang/project/htdocs/poltekpos_sdm/etc/data');

//setup base home
$registry->set('basehome', '../etc/modules/home/views/scripts/index/');
$registry->set('report_server', 'http://localhost:8080');
$registry->set('report_dir', 'D:/project/laporanIPU');

//SSO LOGIN 
/*
$CASClient = new Oa_Service_Cas;
$sso = new Zend_Session_Namespace('sso');

if  (isset($_SESSION['sso']['ticket']) ) {
	}else {
       $CASClient->login();
	}
*/

//var_dump($ssogroup);
$router  = new Zend_Controller_Router_Rewrite();

$ctl = Zend_Controller_Front::getInstance()
        ->setBaseUrl($registry->get('basepath'))
		->addModuleDirectory('../etc/modules');
$ctl->dispatch();	  
?> 