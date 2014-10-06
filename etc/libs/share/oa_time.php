<?php 
require_once 'Zend/View.php';

class oa_time {
  /**
           Fungsi ini berguna untuk menampilkan combo tanggal, bulan dan textbox tahun
	 param $namaTgl adalah untuk mewakili property name combo tanggal String|null
	 param $valueTgl adalah untuk mewakili value default combo tanggal String|null
	 param $namaBln adalah untuk mewakili property name combo bulan String|null
	 param $valueBln adalah untuk mewakili value default combo tanggal String|null
	 param $namaThn adalah untuk mewakili property name textbox tahun String|null
	 param $valueThn adalah untuk mewakili value default textbox tanggal String|null
	 Jika property tersebut diisi null maka property tersebut tidak akan muncul di page html
   */
  public function formJam_oa($namaJam,$valueJam,$namaMenit,$valueMenit,$namaDetik,$valueDetik) {
    $ctrl = new Zend_View();
	$jamArr = array("#"=>"--","00"=>"00","01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06",
	                "07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14",
					"15"=>"15","16"=>"16","17"=>"17","18"=>"18","19"=>"19","20"=>"20","21"=>"21","22"=>"22",
					"23"=>"23");
	if ($valueJam == '' || $valueJam == null) {
	  $valueJam = '#';
	}
    $jam = $ctrl->formSelect($namaJam,$valueJam, null, $jamArr);
	
	$menitArr = array("#"=>"--","00"=>"00","01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06",
	                "07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14",
					"15"=>"15","16"=>"16","17"=>"17","18"=>"18","19"=>"19","20"=>"20","21"=>"21","22"=>"22",
					"23"=>"23","24"=>"24","25"=>"25","26"=>"26","27"=>"27","28"=>"28","29"=>"29","30"=>"30",
					"31"=>"31","32"=>"32","33"=>"33","34"=>"34","35"=>"35","36"=>"36","37"=>"37","38"=>"38",
					"39"=>"39","40"=>"40","41"=>"41","42"=>"42","43"=>"43","44"=>"44","45"=>"45","46"=>"46",
					"47"=>"47","48"=>"48","49"=>"49","50"=>"50","51"=>"51","52"=>"52","53"=>"53","54"=>"54",
					"55"=>"55","56"=>"56","57"=>"57","58"=>"58","59"=>"59");
	if ($valueMenit == '' || $valueMenit == null) {
	  $valueMenit = '#';
	}
    $menit = $ctrl->formSelect($namaMenit,$valueMenit, null, $menitArr);
	
	$detikArr = array("#"=>"--","00"=>"00","01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06",
	                "07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14",
					"15"=>"15","16"=>"16","17"=>"17","18"=>"18","19"=>"19","20"=>"20","21"=>"21","22"=>"22",
					"23"=>"23","24"=>"24","25"=>"25","26"=>"26","27"=>"27","28"=>"28","29"=>"29","30"=>"30",
					"31"=>"31","32"=>"32","33"=>"33","34"=>"34","35"=>"35","36"=>"36","37"=>"37","38"=>"38",
					"39"=>"39","40"=>"40","41"=>"41","42"=>"42","43"=>"43","44"=>"44","45"=>"45","46"=>"46",
					"47"=>"47","48"=>"48","49"=>"49","50"=>"50","51"=>"51","52"=>"52","53"=>"53","54"=>"54",
					"55"=>"55","56"=>"56","57"=>"57","58"=>"58","59"=>"59");
	if ($valueMenit == '' || $valueMenit == null) {
	  $valueMenit = '#';
	}
    $detik = $ctrl->formSelect($namaDetik,$valueDetik, null, $detikArr);
	
	if ($namaJam != null) {
	  $xhtml = $jam;  
	}
	
	if ($namaMenit != null) {
	  if ($namaMenit != null) {
	    $xhtml = $xhtml."&nbsp;".$menit;
	  } else {
	    $xhtml = $menit;
	  }
	}
	
	if ($namaDetik != null) {
	  if ($namaJam != null || $namaMenit != null) {
	    $xhtml = $xhtml."&nbsp;".$detik;
	  } else {
	    $xhtml = $detik;
	  }
	}
	
	return $xhtml;
  }
}
?>