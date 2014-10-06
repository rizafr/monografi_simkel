<?php
date_default_timezone_set('Asia/Jakarta');

$path_image_berita="./data/images_berita/";
$foto_default="blank_image.gif";
$path_data_produkhukum="./data/cms/produkhukum/";

function returnBulan($value)
    {  
   list($tahun,$bulan,$tgl)=split("-",$value);  	
    switch($bulan)
    {
    case "1":
    $bulannya="Januari";
    break;
    case "2";
    $bulannya="Pebruari";
    break;
    case "3":
    $bulannya="Maret";
    break;
    case "4":
    $bulannya="April";
    break;
    case "5":
    $bulannya="Mei";
    break;
    case "6":
    $bulannya="Juni";
    break;
	case "7":
    $bulannya="Juli";
    break;
	case "8":
    $bulannya="Agustus";
    break;
	case "9":
    $bulannya="September";
    break;
	case "10":
    $bulannya="Oktober";
    break;	
	case "11":
    $bulannya="Nopember";
    break;	
	case "12":
    $bulannya="Desember";
    break;
    }
	
    $tglbaru=$tgl." ".$bulannya." ".$tahun;
	return $tglbaru;
    }
function returnBulan2($bulan)
    {  
    switch($bulan)
    {
    case "1":
    $bulannya="Jan";
    break;
    case "2";
    $bulannya="Peb";
    break;
    case "3":
    $bulannya="Mar";
    break;
    case "4":
    $bulannya="Apr";
    break;
    case "5":
    $bulannya="Mei";
    break;
    case "6":
    $bulannya="Jun";
    break;
	case "7":
    $bulannya="Jul";
    break;
	case "8":
    $bulannya="Ags";
    break;
	case "9":
    $bulannya="Sep";
    break;
	case "10":
    $bulannya="Okt";
    break;	
	case "11":
    $bulannya="Nop";
    break;	
	case "12":
    $bulannya="Des";
    break;
    }
	
	return $bulannya;
    }
function gettgl($value)  
    {
	list($tahun,$bulan,$tgl)=split("-",$value);
     return $tgl;	
    }
function getbln($value)  
    {
	list($tahun,$bulan,$tgl)=split("-",$value);
	$bln=returnBulan2($bulan);
     return $bln;	
    }
function formatDate($value)
    {  
	list($tahun,$bulan,$xtgl)=split("-",$value);
	$tgl=substr($xtgl,0,2);	
    $tgl2=$tahun."-".$bulan."-".$tgl;
	$tgl3=returnBulan($tgl2);
	$xtime=substr($value,11,8);
    list($jam,$mnt,$det)=split(":",$xtime);
	$tglbaru=$tgl3." | ".$jam.":".$mnt;
	return $tglbaru;
    }
function formatDate2($value)
    {  
	list($tahun,$bulan,$xtgl)=split("-",$value);
	$tgl=substr($xtgl,0,2);	
    $tgl2=$tgl."-".$bulan."-".$tahun;
	$xtime=substr($value,11,8);
    list($jam,$mnt,$det)=split(":",$xtime);
	$tglbaru=$tgl2." ".$jam.":".$mnt;
	return $tglbaru;
    }	
function formatDate3($value)
    {  
	list($tahun,$bulan,$xtgl)=split("-",$value);
	$tgl=substr($xtgl,0,2);	
    $tgl2=$tahun."-".$bulan."-".$tgl;
	$tglbaru=returnBulan($tgl2);
	return $tglbaru;

    }		
function formatDate4($value)    
	{  
	list($tahun,$bulan,$xtgl)=split("-",$value);
	$tgl=substr($xtgl,0,2);	
      $tglbaru=$tgl."/".$bulan."/".$tahun;
	return $tglbaru;
    }	
function formatDate5($value)
    {  
	list($tahun,$bulan,$xtgl)=split("-",$value);
	$tgl=substr($xtgl,0,2);	
    $tgl2=$tahun."-".$bulan."-".$tgl;
	$tglbaru=returnBulan($tgl2);
	return $tglbaru;
    }
function reformatDate($value)
    {  
   list($tgl,$bulan,$tahun)=split("/",$value);  	
	
    $tglbaru=$tahun."-".$bulan."-".$tgl;
	return $tglbaru;
    }
function reformatDate2($value)
    {  
   list($tgl,$bulan,$tahun)=split("-",$value);  	
	
    $tglbaru=$tahun."-".$bulan."-".$tgl;
	return $tglbaru;
    }
function getbulan($value) {
	list($tgl,$bulan,$tahun)=split(" ",$value);
	$bln=substr($bulan,0,3);
	return $bln;
	}
function gettanggal($value) {
	list($tgl,$bulan,$tahun)=split(" ",$value);
	return $tgl;
	}
global $history;	

function cut_karakter($char,$length_char){
  if(strlen($char) > $length_char){
	$char=preg_replace('/<img.*src="(.*?)".*\/?>/', '', $char);
	$char=preg_replace('/<div.*style="(.*?)".*\/?>/', '', $char);
	$char=preg_replace('/<div.*\/?>/', '', $char);
	for ($i=$length_char;$i<=$length_char+1000;$i++){
	  if(substr($char,$i,1)==" " or substr($char,$i,1)==""){
		$new_char = substr($char,0,$i);
		return $new_char."&nbsp;[...]";
		break;
	  }
	}
  }
  else return $char;
}
	
	
  $BULAN=Array();
  $BULAN[1]="Jan";
  $BULAN[2]="Peb";
  $BULAN[3]="Mar";
  $BULAN[4]="Apr";
  $BULAN[5]="Mei";
  $BULAN[6]="Jun";
  $BULAN[7]="Jul";
  $BULAN[8]="Ags";
  $BULAN[9]="Sep";
  $BULAN[10]="Okt";
  $BULAN[11]="Nop";
  $BULAN[12]="Des";
	
function konvBulan($value)
    {  
   list($tgl,$bulan,$tahun)=split(" ",$value);  	
    switch($bulan)
    {
    case "January":
    $bulannya="1";
    break;
    case "February";
    $bulannya="2";
    break;
    case "March":
    $bulannya="3";
    break;
    case "April":
    $bulannya="4";
    break;
    case "May":
    $bulannya="5";
    break;
    case "June":
    $bulannya="6";
    break;
	case "July":
    $bulannya="7";
    break;
	case "August":
    $bulannya="8";
    break;
	case "September":
    $bulannya="9";
    break;
	case "October":
    $bulannya="10";
    break;	
	case "November":
    $bulannya="11";
    break;	
	case "December":
    $bulannya="12";
    break;
    }
	
    $tglbaru=$tahun."-".$bulannya."-".$tgl;
	return $tglbaru;
    }		
	
function konvBulan2($value)
    {  
   list($tgl,$bulan,$tahun)=split(" ",$value);  	
    switch($bulan)
    {
    case "Januari":
    $bulannya="1";
    break;
    case "Februari";
    $bulannya="2";
    break;
    case "Maret":
    $bulannya="3";
    break;
    case "April":
    $bulannya="4";
    break;
    case "Mei":
    $bulannya="5";
    break;
    case "Juni":
    $bulannya="6";
    break;
	case "Juli":
    $bulannya="7";
    break;
	case "Agustus":
    $bulannya="8";
    break;
	case "September":
    $bulannya="9";
    break;
	case "Oktober":
    $bulannya="10";
    break;	
	case "November":
    $bulannya="11";
    break;	
	case "Desember":
    $bulannya="12";
    break;
    }
	
    $tglbaru=$tahun."-".$bulannya."-".$tgl;
	return $tglbaru;
    }		
/*Function for upload image*/
//fungsi resize imge
function resizeImage($image,$width,$height,$scale,$stype) {
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($stype) {
        case 'gif':
        $source = imagecreatefromgif($image);
        break;
        case 'jpg':
        $source = imagecreatefromjpeg($image);
        break;
        case 'jpeg':
        $source = imagecreatefromjpeg($image);
        break;
        case 'png':
        $source = imagecreatefrompng($image);
        break;
    }
	imagecopyresampled($newImage, $source,0,0,0,0, $newImageWidth, $newImageHeight, $width, $height);
	imagejpeg($newImage,$image,90);
	chmod($image, 0777);
	return $image;
}

//fungsi mendapatkan tinggi image
function getHeight($image) {
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}
//fungsi mendapatkan lebar image 
function getWidth($image) {
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}
//fungsi crop image
function cropImage($nw, $nh, $source, $stype, $dest) {
 
    $size = getimagesize($source);
    $w = $size[0];
    $h = $size[1];
 
    switch($stype) {
        case 'gif':
        $simg = imagecreatefromgif($source);
        break;
        case 'jpg':
        $simg = imagecreatefromjpeg($source);
        break;
        case 'png':
        $simg = imagecreatefrompng($source);
        break;
    }
 
    $dimg = imagecreatetruecolor($nw, $nh);
 
    $wm = $w/$nw;
    $hm = $h/$nh;
 
    $h_height = $nh/2;
    $w_height = $nw/2;
 
    if($w> $h) {
 
        $adjusted_width = $w / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;
 
        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
 
    } elseif(($w <$h) || ($w == $h)) {
 
        $adjusted_height = $h / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;
 
        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
 
    } else {

        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
    }
 
    imagejpeg($dimg,$dest,100);
	chmod($dest, 0777);
} 
	
	
?>