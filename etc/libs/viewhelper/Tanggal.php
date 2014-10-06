<?php
require_once 'Zend/View/Helper/FormSelect.php';

class OA_View_Helper_Tanggal
{
	public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }



    public function tanggal($name=null,$default=null,$mxd=null,$bulan=null)
    {
        $today = getdate();
       /* if (isset($bulan)) {
        	$num = cal_days_in_month(CAL_GREGORIAN, $today['mon'], $today['year']);
        }else{
        	$num = cal_days_in_month(CAL_GREGORIAN, $today['mon'], $today['year']);
        }*/
       $num = 31;
        $tgl=array();
        for ($i = 1; $i <= $num; $i++)
        {
            $tgl[$i] =   $i ;
        }
         $other = $mxd;
         if(!isset($name)){
         	$name = "tanggal";
         }
         if(!isset($default)){
         	$default = intval(date("d"));
         }
        $optionTanggal =$this->view->formSelect($name,$default,$other, $tgl);
        return $optionTanggal;
       // return $tgl[intval(date("d"))-1] ;
    }
}
?>