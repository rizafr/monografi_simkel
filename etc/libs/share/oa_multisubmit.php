<?php 
require_once 'Zend/View.php';

class oa_multisubmit {
  /**
	Fungsi yang digunakan untuk generate satu atau beberap tombol submit sekaligus
	Param $namaCmd adalah untuk mewakili property name tombol submit String|null
	Param $nilaiCmd adalah untuk mewakili property value tombol submit Array|String|null
	Param $attrib adalah untuk mewakili property attribute tombol submit Array|String|null
      */
  public function formMultiSubmit_oa($namaCmd, $nilaiCmd, $attrib) {
    $ctrl = new Zend_View();
    if (is_array($nilaiCmd)) {
	  $hitung = count($nilaiCmd); 
	  for($i = 0; $i < $hitung; $i++) {
	    if ($i == 0) {
		  $xhtml = $ctrl->formSubmit($namaCmd, $nilaiCmd[$i], $attrib);
		} else {
		  $xhtml = $xhtml."&nbsp;".$ctrl->formSubmit($namaCmd, $nilaiCmd[$i], $attrib);
		}
	  }
	  //echo 'array';
	} else {
	  $xhtml = $ctrl->formSubmit($namaCmd, $nilaiCmd, $attrib);
	}
    return $xhtml;
  }
}
?>