<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
        protected function _initApp() {
		            $autoloader = Zend_Loader_Autoloader::getInstance();
					Zend_Registry::set('basepath','');
					Zend_Registry::set('baseData','../etc/data');
					Zend_Registry::set('baseUrl','');
					Zend_Registry::set('report_server', 'http://localhost:8080');
					Zend_Registry::set('basehome', '../etc/modules/home/views/scripts/index/');
					
	
		}
		
		protected function _initSession() {
			Zend_Session::start();
			return ;
		}
}

