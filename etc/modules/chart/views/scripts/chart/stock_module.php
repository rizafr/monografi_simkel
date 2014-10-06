<?
//include_once "libs/common/Conn.php";
include "libs/StockBUMNServices/StockBUMNPortalServices.php"; 
include "libs/StockExchangeServices/StockExchangePortalServices.php"; 

	if(!$currentPage)
		$currentPage = 1;
	if(!$currentGroupPage)
		$currentGroupPage = 1;
	
	$numToDisplay = 3;
	$indexAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;


///-------- ListdateNow ------------
function module_stock_getcontent()
{
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);

global $numToDisplay,$currentPage;
$getcontent=$getcontent.'<div class="contentModule">';

$getcontent=$getcontent.'<div class="table-row">';
	$getcontent=$getcontent.'<div class="left-container14 bold"><H5 class="colhdrl">CODE</H5></div>';
	$getcontent=$getcontent.'<div class="left-container24"><H5 class="colhdr">CHG</H5></div>';
	$getcontent=$getcontent.'<div class="left-container34"><H5 class="colhdr">VAL</H5></div>';
	$getcontent=$getcontent.'<div class="right-container14"><H5 class="colhdr">PCT</H5></div>';
	$getcontent=$getcontent.'<div class="space-line"></div>';
$getcontent=$getcontent.'</div>';


$imgup="b.tickers.up.gif";
$imgdown="b.tickers.dn.gif";
$img="";
$date= date("Y-m-d"); 
//$date= "2007-05-24"; 

$void = getStockExchange($date);
$outPutList=new StockExchange();
foreach ($void as $tt) { 	
 	$outPutList=unserialize($tt);

		   $StockCode = $outPutList->getStockMarketCode();
		   $Change = $outPutList->getChange();
		   $Value = $outPutList->getValue();
		   $Percentage = $outPutList->getPercentage();
		   $DateStock = $outPutList->getDateStock();
		   $Active = $outPutList->getActive();


	$getcontent=$getcontent.'<div class="table-row">';
	$getcontent=$getcontent.'<div class="left-container14 bold"><p class = "text">'.$StockCode.'</p></div>';
		//if ($High > $Low) { $img=$imgup;}	else { $img=$imgdown;}
	if ($Percentage > 0) { $img=$imgup;}	else { $img=$imgdown;}
	$getcontent=$getcontent.'<div class="left-container24"><p class = "text"><img src="images/'.$img.'"></p></div>';
	//$getcontent=$getcontent.'<div class="left-container24"><p class = "text">'.$Change.'</p></div>';
	$getcontent=$getcontent.'<div class="left-container34"><p class = "text lgright">'.$Value.'</p></div>';
	$getcontent=$getcontent.'<div class="right-container14"><p class = "textr lgright">'.$Percentage.'</p></div>';
$getcontent=$getcontent.'</div>';
$getcontent=$getcontent.'<div class="space-line"></div>';
 }
$getcontent=$getcontent.'<div class="space-line"></div>';
$getcontent=$getcontent.'</div>';
$lihatsemua= getLocalText("lihatsemua");
$sumber= getLocalText("sumber");
$getcontent=$getcontent.'<div class="left bold">'.$sumber.' : <a href="http://www.imq21.com/" target="_blank"><img src="images/ico_imq.gif" ></a></div>';
$getcontent=$getcontent.'<div class="list-all"><a id="listallindexstock" href="" svr="s02" idreg="stock"  nametitle= "'.$LanguangeName.'"">'.$lihatsemua.'</a></div>';
$getcontent=$getcontent.'<div class="blank"></div>';
		
		
return $getcontent;
}

///-------- List MostActivites------------
function module_stock_getMostActivities()
{
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);

global $numToDisplay,$currentPage, $currentGroupPage,$indexAwal,$idctgr;
$no=0;
$getlist=$getlist.'<div class="blank"></div>';
$getlist=$getlist.'<h4>TOP 20 MOST ACTIVES</h4>';
$getlist=$getlist.'<table width="100%" border="0" cellspacing="1" cellpadding="2" class="tbl">';
$getlist=$getlist.'<th>No.';
$getlist=$getlist.'<th>Stock Code';
$getlist=$getlist.'<th>Last Done';
$getlist=$getlist.'<th>Last Value';
$getlist=$getlist.'<th>Last Volume';
$getlist=$getlist.'<th>Total Freq';
$getlist=$getlist.'<th>Change';
$getlist=$getlist.'<th>Pct';
	 $cdate= date("Y-m-d"); 
	 //$cdate= "2007-04-30"; 
$cc ="0";	
	 $void = getTopActive($cdate);
	 $outPutList=new TopStock();
	 foreach ($void as $tt) { 	
		$outPutList=unserialize($tt);
		$StockCode = $outPutList->getStockCode();
		$IdBUMN = $outPutList->getIdBUMN();
		$LastDone = $outPutList->getLastDone();
		$LastValue = $outPutList->getLastValue();
		$LastVolume = $outPutList->getLastVolume();    
		$TotalFrequency = $outPutList->getTotalFrequency();
		$Change = $outPutList->getChange();
		$Percentage = $outPutList->getPercentage();
		$NamaBumn = $outPutList->getNameBUMN();
		
		$StockCode = trim($StockCode);
		$no=$no+1; 
				if($cc=="0") { $even="even"; $cc="1";}
				elseif($cc=="1") { $even="even2"; $cc="0";}
			$getlist=$getlist.'<tr class='.$even.'>';
			$getlist=$getlist.'<td class="clright">'.$no.'.&nbsp;</td>';

			if (($IdBUMN=="") ||($IdBUMN==" ") || ($IdBUMN=="undefined")) 
			{
			$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20MOSTACTIVES" idBumn='.$IdBUMN.' alt="'.$NamaBumn.'" title="'.$NamaBumn.'" nametitle= "'.$LanguangeName.'" no="'.$no.'"> '.$StockCode.'</a></td>';
			
			}
			else
			{
			$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.' idstock= "TOP20MOSTACTIVES" idBumn='.$IdBUMN.'  nametitle= "'.$LanguangeName.'" no="'.$no.'"><span class="hightlight" alt="'.$NamaBumn.'"  title="'.$NamaBumn.'"> '.$StockCode.' </span></a></td>';						

			}
			$getlist=$getlist.'<td class="clright">'.$LastDone.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$LastValue.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$LastVolume.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$TotalFrequency.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$Change.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$Percentage.'&nbsp; </td>';
			$getlist=$getlist.'</tr>';
}

$getlist=$getlist.'</table>';

return $getlist;
}  


///-------- List Gainers ------------
function module_stock_getGainers()
{
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);

global $numToDisplay,$currentPage, $currentGroupPage,$indexAwal,$idctgr;
$no=0;
$getlist=$getlist.'<div class="blank"></div>';
$getlist=$getlist.'<h4>TOP 20 GAINERS</h4>';
$getlist=$getlist.'<table width="100%" border="0" cellspacing="1" cellpadding="2" class="tbl">';
$getlist=$getlist.'<th>No.';
$getlist=$getlist.'<th>Stock Code';
$getlist=$getlist.'<th>Last Done';
$getlist=$getlist.'<th>Last Value';
$getlist=$getlist.'<th>Last Volume';
$getlist=$getlist.'<th>Total Freq';
$getlist=$getlist.'<th>Change';
$getlist=$getlist.'<th>Pct';
	 $cdate= date("Y-m-d"); 
	 //$cdate= "2007-04-30"; 
$cc ="0";	
	 $void = getTopGainers($cdate);
	 $outPutList=new TopStock();
	 foreach ($void as $tt) { 	
		$outPutList=unserialize($tt);
		$StockCode = $outPutList->getStockCode();
		$IdBUMN = $outPutList->getIdBUMN();
		$LastDone = $outPutList->getLastDone();
		$LastValue = $outPutList->getLastValue();
		$LastVolume = $outPutList->getLastVolume();    
		$TotalFrequency = $outPutList->getTotalFrequency();
		$Change = $outPutList->getChange();
		$Percentage = $outPutList->getPercentage();
		$NamaBumn = $outPutList->getNameBUMN();
		$no=$no+1; 
		$StockCode = trim($StockCode);
				if($cc=="0") { $even="even"; $cc="1";}
				elseif($cc=="1") { $even="even2"; $cc="0";}
			$getlist=$getlist.'<tr class='.$even.'>';
			$getlist=$getlist.'<td class="clright">'.$no.'.&nbsp;</td>';
			//$getlist=$getlist.'<td class="clcenter">'.$StockCode.'&nbsp;</td>';
			//$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s02" idreg="stock" idstock= '.$StockCode.'</a>'.$StockCode.'&nbsp;</td>';

			if (($IdBUMN=="") ||($IdBUMN==" ") || ($IdBUMN=="undefined")) 
			{
   		    	$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20GAINERS" idBumn='.$IdBUMN.' alt="'.$NamaBumn.'" title="'.$NamaBumn.'" nametitle= "'.$LanguangeName.'"> '.$StockCode.'</a></td>';
			}
			else
			{
			$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20GAINERS" idBumn='.$IdBUMN.'  nametitle= "'.$LanguangeName.'"><span class="hightlight" alt="'.$NamaBumn.'"  title="'.$NamaBumn.'"> '.$StockCode.'</span></a></td>';
			}
			$getlist=$getlist.'<td class="clright">'.$LastDone.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$LastValue.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$LastVolume.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$TotalFrequency.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$Change.'&nbsp;</td>';
			$getlist=$getlist.'<td class="clright">'.$Percentage.'&nbsp; </td>';
			$getlist=$getlist.'</tr>';
}

$getlist=$getlist.'</table>';
return $getlist;
}  


///-------- List Losers ------------
function module_stock_getLosers()
{
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);
global $numToDisplay,$currentPage, $currentGroupPage,$indexAwal,$idctgr;
$no=0;
		$getlist=$getlist.'<div class="blank"></div>';
			$getlist=$getlist.'<h4>TOP 20 LOSERS</h4>';
			$getlist=$getlist.'<table width="100%" border="0" cellspacing="1" cellpadding="2" class="tbl">';
			$getlist=$getlist.'<th>No.';
			$getlist=$getlist.'<th>Stock Code';
			$getlist=$getlist.'<th>Last Done';
			$getlist=$getlist.'<th>Last Value';
			$getlist=$getlist.'<th>Last Volume';
			$getlist=$getlist.'<th>Total Freq';
			$getlist=$getlist.'<th>Change';
			$getlist=$getlist.'<th>Pct';
			$cdate= date("Y-m-d"); 

			//$cdate= "2007-04-30"; 

			$cc ="0";	
				 $void = getTopLosers($cdate);
				 $outPutList=new TopStock();
				 foreach ($void as $tt) { 	
					$outPutList=unserialize($tt);
					$StockCode = $outPutList->getStockCode();
					$IdBUMN = $outPutList->getIdBUMN();
					$LastDone = $outPutList->getLastDone();
					$LastValue = $outPutList->getLastValue();
					$LastVolume = $outPutList->getLastVolume();    
					$TotalFrequency = $outPutList->getTotalFrequency();
					$Change = $outPutList->getChange();
					$Percentage = $outPutList->getPercentage();
					$NamaBumn = $outPutList->getNameBUMN();
					$StockCode = trim($StockCode);
					$no=$no+1; 
							if($cc=="0") { $even="even"; $cc="1";}
							elseif($cc=="1") { $even="even2"; $cc="0";}
						$getlist=$getlist.'<tr class='.$even.'>';
						$getlist=$getlist.'<td class="clright">'.$no.'.&nbsp;</td>';
						if (($IdBUMN=="") ||($IdBUMN==" ") || ($IdBUMN=="undefined")) 
						{
						$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20LOSERS" idBumn='.$IdBUMN.' alt="'.$NamaBumn.'" title="'.$NamaBumn.'" nametitle= "'.$LanguangeName.'" > '.$StockCode.'</a></td>';
						}
						else
						{
						$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20LOSERS" idBumn='.$IdBUMN.'  nametitle= "'.$LanguangeName.'"><span class="hightlight" alt="'.$NamaBumn.'"  title="'.$NamaBumn.'"> '.$StockCode.' </span></a></td>';
	
						}	
						$getlist=$getlist.'<td class="clright">'.$LastDone.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$LastValue.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$LastVolume.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$TotalFrequency.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$Change.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$Percentage.'&nbsp; </td>';
						$getlist=$getlist.'</tr>';
			}
			
			$getlist=$getlist.'</table>';
return $getlist;
}  

function module_stock_getchart()
{
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);

global $numToDisplay,$currentPage, $currentGroupPage,$indexAwal,$idctgr;
$cdate= date("Y-m-d"); 
//$cdate= "2007-04-30"; 
//$idBumn = $GLOBALS['BUMN_ID'];
$idBumn =$_GET['idBumn']; 
$getstockcode=$_GET['stockcode'];
$idstock=$_GET['idstock'];
$no2=$_GET['no'];

$GLOBALS['FORM_POLL'] = true;

		   if ($idstock=="TOP20LOSERS") {	
			 	 $bursa="TOP 20 LOSERS";
				 $void = getTopLosers($cdate);
				 }
		    if ($idstock=="TOP20GAINERS") {	
				$bursa="TOP 20 GAINERS";
				 $void = getTopGainers($cdate);
				 }
		    if ($idstock=="TOP20MOSTACTIVES") {	
				$bursa="TOP 20 MOSTACTIVES";
				 $void = getTopActive($cdate);
				 } 



include "charts.php";
$sCode = $getstockcode;
$iBUMN = $idBumn;
$getlist=$getlist.'<div class="graphic">';
$findchart =InsertChart ( "modules/stock/charts.swf", "charts_library", "modules/stock/chart_stock.php?sCode=".$sCode."&iBUMN=".$idBumn."" ,600, 400, "8844FF", true );
$getlist=$findchart;
$getlist=$getlist.'</div>';





		$getlist=$getlist.'<div class="blank"></div>';


			$getlist=$getlist.'<h4>'.$bursa.'</h4>';
			$getlist=$getlist.'<table width="100%" border="0" cellspacing="1" cellpadding="2" class="tbl">';
			$getlist=$getlist.'<th>No.';
			$getlist=$getlist.'<th>Stock Code';
			$getlist=$getlist.'<th>Last Done';
			$getlist=$getlist.'<th>Last Value';
			$getlist=$getlist.'<th>Last Volume';
			$getlist=$getlist.'<th>Total Freq';
			$getlist=$getlist.'<th>Change';
			$getlist=$getlist.'<th>Pct';
			$cdate= date("Y-m-d"); 
			//$cdate= "2007-04-30"; 
			$cc ="0";

$no=0;				 
				 $outPutList=new TopStock();
				 foreach ($void as $tt) { 	
					$outPutList=unserialize($tt);
					$StockCode = $outPutList->getStockCode();
					$IdBUMN = $outPutList->getIdBUMN();
					$LastDone = $outPutList->getLastDone();
					$LastValue = $outPutList->getLastValue();
					$LastVolume = $outPutList->getLastVolume();    
					$TotalFrequency = $outPutList->getTotalFrequency();
					$Change = $outPutList->getChange();
					$Percentage = $outPutList->getPercentage();
					$NamaBumn = $outPutList->getNameBUMN();
					$StockCode = trim($StockCode);
					$no=$no+1; 
							if($cc=="0") { $even="even"; $cc="1";}
							elseif($cc=="1") { $even="even2"; $cc="0";}
						$getlist=$getlist.'<tr class='.$even.'>';
						$getlist=$getlist.'<td class="clright">'.$no.'.&nbsp;</td>';
						//$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s02" idreg="stock" stockcode= '.$StockCode.' idstock= "losers" </a>'.$StockCode.'&nbsp;</td>';
			if (($IdBUMN=="") ||($IdBUMN==" ") || ($IdBUMN=="undefined")) 
			{
   		    	$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20GAINERS" idBumn='.$IdBUMN.' alt="'.$NamaBumn.'" title="'.$NamaBumn.'" nametitle= "'.$LanguangeName.'"  no="'.$no.'"> '.$StockCode.'</a></td>';
			}
			else
			{
			$getlist=$getlist.'<td class="clcenter"><a class="listgetstock" href="#" svr="s06" idreg="stock" stockcode= '.$StockCode.'  idstock= "TOP20GAINERS" idBumn='.$IdBUMN.'  nametitle= "'.$LanguangeName.'"  no="'.$no.'"><span class="hightlight" alt="'.$NamaBumn.'"  title="'.$NamaBumn.'"> '.$StockCode.'</span></a></td>';
			}						

						$getlist=$getlist.'<td class="clright">'.$LastDone.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$LastValue.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$LastVolume.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$TotalFrequency.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$Change.'&nbsp;</td>';
						$getlist=$getlist.'<td class="clright">'.$Percentage.'&nbsp; </td>';
						$getlist=$getlist.'</tr>';
			}
			
$getlist=$getlist.'</table>';

/*
include "charts.php";
$sCode = $getstockcode;
$iBUMN = $idBumn;
$getlist=$getlist.'<div class="graphic">';
$findchart =InsertChart ( "modules/stock/charts.swf", "charts_library", "modules/stock/chart_stock.php?sCode=".$sCode."&iBUMN=".$idBumn."" ,600, 400, "8844FF", true );
$getlist=$getlist.$findchart;
$getlist=$getlist.'</div>';

*/
		
return $getlist;
}  



function module_stock_getList() {
$idLang = $GLOBALS['LANG_ID'];
$iModul ='8';
$LanguangeName= getLanguage($idLang, $iModul);

			$keluaran = $keluaran.'<div class="subMenu">';
				$keluaran = $keluaran.'<ul id="ls2">';
				$keluaran = $keluaran.'<br>';
				$keluaran = $keluaran.'<li><a id ="mostactv" href="" svr="s02" idreg="stock" nametitle= "'.$LanguangeName.'">Top 20 Most Actives</a>';
				$keluaran = $keluaran.'<li><a id ="gainers" href="" svr="s03" idreg="stock" nametitle= "'.$LanguangeName.'">Top 20 Gainers</a>';
				$keluaran = $keluaran.'<li><a id ="losers" href="" svr="s04" idreg="stock" nametitle= "'.$LanguangeName.'">Top 20 Losers</a></ul>';
				$keluaran = $keluaran.'</ul>';
				$keluaran = $keluaran.'<br>';
				//$keluaran = $keluaran.'<span>Sumber: <a href="http://www.imq21.com/" target="_blank">www.imq21.com</a></span><br>';
				//$keluaran = $keluaran.'<span>Jakarta, 10-05-2007</span>';
			$keluaran = $keluaran.'</div>';
return $keluaran;

}

function module_stock_getLang () 
{

$idBumn = $GLOBALS['BUMN_ID']; 
$idLang = $GLOBALS['LANG_ID'];
$iModul ="8";
$LanguangeName= getLanguage($idLang, $iModul);

$getcontent =$getcontent.'
		stock_modules ("s02","stock","","","","'.$LanguangeName.'")';
return $getcontent;

}
?>
