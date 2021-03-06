<?php 
class oa_dec_cur_conv {
  /**
	Fungsi yang yang digunakan untuk conversi dari type data String Currency ke Decimal
	Param $nilaiCurrency adalah variabel untuk inputan data bertype String Currency
      */
  public function convertCurToDec($nilaiCurrency) {
	$replstr = array("-"=>"","."=>"",","=>".");
    return strtr($nilaiCurrency,$replstr);
  }

  /**
	Fungsi yang yang digunakan untuk conversi dari type data Decimal ke String Currency
	Param $nilaiCurrency adalah variabel untuk inputan data bertype Decimal
      */
  public function convertDecToCurNoBehind($nilaiDecimal) {
    return number_format($nilaiDecimal,0,',','.');
  }
  
  /**
	Fungsi yang yang digunakan untuk conversi dari type data Decimal ke String Currency
	Param $nilaiCurrency adalah variabel untuk inputan data bertype Decimal
      */
  public function convertDecToCur($nilaiDecimal) {
    return number_format($nilaiDecimal,0,',','.');
  }

 
   public function convertDecToCurversi2($nilaiDecimal) {
    return number_format($nilaiDecimal);
  }

   function terbilang1($bil){
	   
         $bil = intval($bil);
        $bilangan = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan");
         $level = array("", "Ribu", "Juta", "Milyar", "Trilyun", "Bilyun");

        if (intval($bil)>=0 and intval($bil)<=999) {
             $a = intval($bil/100);
            $b = intval(($bil%100)/10);
            $c = intval($bil%10);

            $hasil = "";
            $temp1 = "";
            switch($a){
                case 0 :
                    $temp1 = "";
                break;

                case 1 :
                    $temp1 = "Seratus";
                break;

                default :
                    $temp1 = $bilangan[$a]." Ratus";
                break;
            }

            if ($temp1!="") {
                $hasil = $hasil." ".$temp1;
            }

            // puluhan
            $temp2 = "";
            switch($b){
                case 0:
                    $temp2 = $bilangan[$c];
                break;

                case 1:
                    if($c==0){
                        $temp2 = "Sepuluh";
                    }elseif($c==1){
                        $temp2 = "Sebelas";
                    }else{
                        $temp2 = $bilangan[$c]." Belas";
                    }
                break;

                default:
                    $temp2 = $bilangan[$b]." Puluh ".$bilangan[$c];
                break;
            }

            if ($temp2!="") {
                $hasil = $hasil." ".$temp2;
            }
         }//echo $hasil;
        return $hasil;
    }

    function terbilang($n){
         $co_level = array("", "Ribu", "Juta", "Milyar", "Trilyun", "Bilyun");
         $hasil = "";
         $level = 0;
         while($n<>0){
             $tripet = $n%1000;
            $n=$n/1000;

            $temp=$this->terbilang1($tripet);
            if ($temp!="") {
                $hasil = $temp." ".$co_level[$level]." ".$hasil;
            }
            $level++;
         }
    return $hasil;
    }

}
?>