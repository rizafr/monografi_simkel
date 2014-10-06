<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/portal/Portal_Useronline_Service.php";
require_once 'share/Portalconf.php'; 

class Portalmodule_useronlineController extends Zend_Controller_Action {

		
    public function init() {
		$this->_helper->layout->setLayout('target-column');
		$registry = Zend_Registry::getInstance();
		//$this->view->photoPath = $registry->get('photoPath');
		 
		
		$this->useronline_serv = Portal_Useronline_Service::getInstance();
		$this->view->userid= $this->userid;
		$this->view->i_ym= $this->i_ym;
		
		//$sespeg = new Zend_Session_Namespace('sespeg');
    }
	
    public function indexAction() {
    }
public function useronlinejsAction() 
{
	header('content-type : text/javascript');
//	$this->render('useronlinejs');
}	


public function listuseronlineAction() {    
    

			////// To update session status for tmuseronline table to get who is online ////////
			//if(isset(session_id())){
				$tm=date("Y-m-d H:i:s");
				$DataUseronlineUpdate = array(
					"id"=>session_id(),
					"tm"=>$tm,
					"status"=>'ON');
				$hasilupdateol = $this->useronline_serv->maintainDataupdateOl($DataUseronlineUpdate);
				//$sqlquery="update plus_login set status='ON',tm='$tm' where id='$session[id]'";
				//echo $sqlquery;
				//$q=mysql_query($sqlquery);
			//}
			// Find out who is online /////////
			$gap=1; // change this to change the time in minutes, This is the time for which active users are collected. 
			$tm=date ("Y-m-d H:i:s", mktime (date("H"),date("i")-$gap,date("s"),date("m"),date("d"),date("Y")));
			$this->view->jmluserol = $this->useronline_serv->getUseronlineSum($tm);
			
			$this->view->useronlinelist = $this->useronline_serv->getUseronlineList($tm);
    }




 

	
}
?>