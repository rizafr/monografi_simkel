<?php
require_once 'Zend/Controller/Action.php';
require_once "Zend/chart/charts.php";


class Chart_ChartController extends Zend_Controller_Action {
	private $pagu_serv;
	private $pagu_ref_serv;
	private $userlog;
	private $userid;
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->pathUPLD = $registry->get('pathUPLD');
	    $this->view->procPath = $registry->get('procpath');
	    $this->view->baseData = $registry->get('baseData');
		//echo  $this->view->pathUPLD;
/*		
	    $this->ssogroup = new Zend_Session_Namespace('ssogroup');	
		$auth = Zend_Auth::getInstance();
		$this->id   = $auth->getIdentity(); 
		$this->userlog = strtoupper($this->id['dept']);
		$this->userid = strtoupper($this->id['userid']);
		$this->username = $this->id['username']; 
  
		$userlog=$this->userlog;		
 		$userlog2 = $this->pagu_ref_serv->getUserId($userlog);

		if (!$userlog2) {		
			$this->userlog = strtoupper($this->id['dept']);
			$this->username = $this->id['username']; 
		}
		else {
			$this->userlog = $userlog2;
		}
*/
			$this->userlog = "SK0000";
			$this->username = "SK0000";
    }
	
    public function indexAction() {
	   //echo "Pagu Masuk Controller indexAction";
    }
	
	
    public function chartAction() {

$findchart =InsertChart ( "Zend/chart/charts.swf", "Zend/charts_library", "Zend/chart/chart.php?sCode=".$sCode."&iBUMN=".$idBumn."" ,600, 400, "8844FF", true );
echo $findchart;	
			
    }
	
	
}

?>
