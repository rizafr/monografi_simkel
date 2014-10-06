<?php

class OA_View_Helper_Formater
{	
	
   
		function kekata($x) {
		    $x = abs($x);
		    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
		    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		    $temp = "";
		    if ($x <12) {
		        $temp = " ". $angka[$x];
		    } else if ($x <20) {
		        $temp = $this->kekata($x - 10). " belas";
		    } else if ($x <100) {
		        $temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		    } else if ($x <200) {
		        $temp = " seratus" . $this->kekata($x - 100);
		    } else if ($x <1000) {
		        $temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		    } else if ($x <2000) {
		        $temp = " seribu" . $this->kekata($x - 1000);
		    } else if ($x <1000000) {
		        $temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		    } else if ($x <1000000000) {
		        $temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		    } else if ($x <1000000000000) {
		        $temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		    } else if ($x <1000000000000000) {
		        $temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		    }      
		        return $temp;
		}
	function terbilang($x, $style=4) {
	    if($x<0) {
	        $hasil = "minus ". trim($this->kekata($x));
	    } else {
	        $hasil = trim($this->kekata($x));
	    }      
	    switch ($style) {
	        case 1:
	            $hasil = strtoupper($hasil);
	            break;
	        case 2:
	            $hasil = strtolower($hasil);
	            break;
	        case 3:
	            $hasil = ucwords($hasil);
	            break;
	        default:
	            $hasil = ucfirst($hasil);
	            break;
	    }      
	    return $hasil;
	}

    public function formater($number,$key = 0)
    {               
          $useragent = $_SERVER['HTTP_USER_AGENT']; 
            if($key == 1){
			   return $this->terbilang($number);
			}else{
					if (strstr($useragent,'Win')) {
					     return $this->formatNumber($number);
					} else if (strstr($useragent,'Mac')) {
					     return $number;
					} else if (strstr($useragent,'Linux')) {
					    setlocale(LC_MONETARY, 'it_IT');
		                return money_format('%.2n', $number) . "\n";
					} else if (strstr($useragent,'Unix')) {
					    setlocale(LC_MONETARY, 'it_IT');
		                return money_format('%.2n', $number) . "\n";
					} else {
					    return $this->formatNumber($number);
					}
          }
		
    }
	
	public function formatNumber($number) {
         return number_format($number,2,',','.');
  }



}
?>