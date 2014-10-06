<?php
require_once 'Zend/View/Helper/FormSelect.php';
require_once 'Zend/View/Helper/FormText.php';
class OA_View_Helper_Tahun
{
	public $view;

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}


	public function tahun($name=null,$default=null,$mxd=null)
	{
		$today = getdate();
		$tahun = $today['year'];
		$tahunArray=array();
		for ($i = ($tahun - 10); $i < ($tahun + 11); $i++)
		{
			$tahunArray[$i] =$i;
		}
	   $other = $mxd;  
	   if(!isset($name)){
         	$name = "tahun";
         } 
        if(!isset($default)){
         	$default = intval(date("Y"));
         }  
      // $optionTahun = $this->view->formSelect($name,$default, $other, $tahunArray);
      $optionTahun = $this->view->formText($name,$default, array('size' => 8));
		
		return $optionTahun;
	}


}
?>