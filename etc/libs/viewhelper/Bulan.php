<?php
require_once 'Zend/View/Helper/FormSelect.php';

class OA_View_Helper_Bulan 
{
	public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function bulan($name=null,$default=null,$mxd=null)
    {
    	 $bulanArray = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret", 
    	 "04"=>"April", "05"=>"Mei", "06"=>"Juni",
         "07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober", 
         "11"=>"November", "12"=>"Desember");
        $other = $mxd;   
        if(!isset($name)){
         	$name = 'bulan';
         } 
          if(!isset($default)){
         	$default = intval(date("m"));
         } 
       $optionBulan = $this->view->formSelect($name,$default, $other, $bulanArray);
        
        return $optionBulan;
    }

}
?>