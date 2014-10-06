<?php
require_once 'Zend/Controller/Action.php';
include_once('CAS/CAS.php');

class  Sso_IndexController extends Zend_Controller_Action {
    public function init() {
        // Local to this controller only; affects all actions, as loaded in init:
        $this->_helper->viewRenderer->setNoRender(true);
		/*
		phpCAS::setDebug();
		phpCAS::client(CAS_VERSION_1_0,'10.1.100.235',8443,'/cas/login');
		phpCAS::forceAuthentication();
		if (isset($_REQUEST['logout'])) {
			phpCAS::logout();
		}
		*/
    }
	
	public function loginAction() {
	    phpCAS::setDebug();
	    phpCAS::client(CAS_VERSION_2_0,'10.1.100.235',8443,'/cas/login');
		phpCAS::forceAuthentication();
		echo "SSO  Controller loginAction";
		
	}
	public function logoutAction() {
	   phpCAS::setDebug();
	   phpCAS::client(CAS_VERSION_2_0,'10.1.100.235',8443,'/cas/logout');
	   phpCAS::logout();
	   echo "logout";
	}
    public function indexAction() {
	   echo "SSO Controller indexAction";
    }
}
?>