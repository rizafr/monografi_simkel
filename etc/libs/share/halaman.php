<?php 
require_once 'Zend/View.php';

class halaman {

	public function pager($totalData, $maxDisplay)
	{
		
		$div = $totalData/$maxDisplay;
		$mod = $totalData%$maxDisplay;
		
		$x = explode(".",$div);
		
		if($mod == 0)
			$totalPages = $x[0];
		else
			$totalPages = $x[0] + 1;

		return $totalPages;
	}

	public function showPage($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4)
	{
		
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		$keluaran = $keluaran.' <div class="pagination rightside">';
		$keluaran = $keluaran."      $totalData Data &bull; ";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages  &bull; ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a>  &bull;";			
		}
		if($currentPage == 1)
		{
			$keluaran =  $keluaran . "<span class=\"disabled\">&laquo; sebelumnya</span>";
		}		
		if($currentPage > 1)
		{
			$a = 1;
			//$keluaran =  $keluaran . "&nbsp;<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">&laquo; </a>&nbsp;";
		}
	
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				$keluaran =  $keluaran . "<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">&laquo;sebelumnya</a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;<span class=\"current\">$a&nbsp;</span></strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">berikutnya &raquo;</a>&nbsp;";
			}
			if ($currentPage <= $totalPages)
			{
				$a = $totalPages;
				//$keluaran =  $keluaran . "&nbsp;<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$fungsi','$a','$param1','$param2','$param3','$param4');\">&raquo;</a>&nbsp;";
			}
		}	
		
		
		$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}
	
	public function showPage2($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4, $divId)
	{
		//echo "$modul";
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;

		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		//$keluaran = $keluaran."<div id=\"ctBox\">";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto2('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$divId'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		
		//if(($currentGroupPage > 1) && ($totalGroup != 1))
		if($currentPage > 1)
		{
			//$a = ($indexStartPage -$totalPerPages);
			$a = 1;
			//$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;<<</a></div>";
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
/* 		if($currentGroupPage > 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$pertama&nbsp;</a></div>";
		} */
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;<&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					//$keluaran =  $keluaran . "<div class=\"pageCurrent\">&nbsp;$a&nbsp;</div>"; 
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;$a&nbsp;</a></div>";		
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;>&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#62;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
			}
		/* if($currentGroupPage < $totalGroup)
		{
			$a = $totalPages;
			$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$terakhir&nbsp;</a></div>";
		}	 */
			//if(($totalGroup > 1) && ($currentGroupPage != $totalGroup))
			if ($currentPage <= $totalPages)
			{
				//$a = ($currentGroupPage * $totalPerPages) + 1;
				$a = $totalPages;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;>></a></div>";
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&#62;&#62;<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		
		/* $indexPage = "<div class=\"page\">Halaman $currentPage dari $totalPages  : </div>".$indexPage;
		if($totalPages > 1)
		{
			$keluaran = $keluaran."<a href=\"#\" onclick=\"return !showPopup('mn_paging', event);\" title=\"Cari Halaman\"><div class=\"pageNav\">";
		}
		$keluaran = $keluaran."			</div></a>"; */
		$keluaran = $keluaran."      </div>";
		//$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}



	public function showPage5($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4, $param5, $param6)
	{
	//echo "totalData= ".$totalData."<br>"."numToDisplay= ".$numToDisplay."<br>"."currentPage= ".$currentPage."<br>"."modul= ".$modul."<br>"."fungsi= ".$fungsi."<br>";
	//echo "param1= ".$param1."<br>"."param2= ".$param2."<br>"."param3= ".$param3."<br>"."param4= ".$param4."<br>"."param5= ".$param5."<br>";
	
		//echo "param5= ".$param5."<br>";
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		//$keluaran = $keluaran."<div id=\"ctBox\">";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto5('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$param5', '$param6'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		
		//if(($currentGroupPage > 1) && ($totalGroup != 1))
		if($currentPage > 1)
		{
			//$a = ($indexStartPage -$totalPerPages);
			$a = 1;
			//$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;<<</a></div>";
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
/* 		if($currentGroupPage > 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$pertama&nbsp;</a></div>";
		} */
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;<&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					//$keluaran =  $keluaran . "<div class=\"pageCurrent\">&nbsp;$a&nbsp;</div>"; 
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;$a&nbsp;</a></div>";		
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;>&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#62;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
			}
		/* if($currentGroupPage < $totalGroup)
		{
			$a = $totalPages;
			$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$terakhir&nbsp;</a></div>";
		}	 */
			//if(($totalGroup > 1) && ($currentGroupPage != $totalGroup))
			if ($currentPage <= $totalPages)
			{
				//$a = ($currentGroupPage * $totalPerPages) + 1;
				$a = $totalPages;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;>></a></div>";
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&#62;&#62;<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		
		/* $indexPage = "<div class=\"page\">Halaman $currentPage dari $totalPages  : </div>".$indexPage;
		if($totalPages > 1)
		{
			$keluaran = $keluaran."<a href=\"#\" onclick=\"return !showPopup('mn_paging', event);\" title=\"Cari Halaman\"><div class=\"pageNav\">";
		}
		$keluaran = $keluaran."			</div></a>"; */
		$keluaran = $keluaran."      </div>";
		//$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}
	
	
	
	
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
  public function formTanggal_oa($namaTgl,$valueTgl,$namaBln,$valueBln,$namaThn,$valueThn) {
    $ctrl = new Zend_View();
	$tglArr = array("#"=>"--","01"=>"1","02"=>"2","03"=>"3","04"=>"4","05"=>"5","06"=>"6","07"=>"7",
					"08"=>"8","09"=>"9","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14","15"=>"15",
					"16"=>"16","17"=>"17","18"=>"18","19"=>"19","20"=>"20","21"=>"21","22"=>"22","23"=>"23",
					"24"=>"24","25"=>"25","26"=>"26","27"=>"27","28"=>"28","29"=>"29","30"=>"30","31"=>"31");
	if ($valueTgl == '' || $valueTgl == null) {
	  $valueTgl = '#';
	}
    $tgl = $ctrl->formSelect($namaTgl,$valueTgl, null, $tglArr);
	
	$blnArr = array("#"=>"--","01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei",
					"06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November",
					"12"=>"Desember");
	if ($valueBln == '' || $valueBln == null) {
	  $valueBln = '#';
	}
    $bln = $ctrl->formSelect($namaBln,$valueBln, null, $blnArr);
	
	$thnAtrib = array("size"=>"4",
	                  "maxlength"=>"4",
					  "onkeyup"=>"javascript:checkNumber(this)",
					  "onkeypress"=>"javascript:checkNumber(this)");
	if ($valueThn == '' || $valueThn == null) {
	  $valueThn = null;
	}
	$thn = $ctrl->formText($namaThn, $valueThn,$thnAtrib);
	
	if ($namaTgl != null) {
	  $xhtml = $tgl;  
	}
	
	if ($namaBln != null) {
	  if ($namaTgl != null) {
	    $xhtml = $xhtml."&nbsp;".$bln;
	  } else {
	    $xhtml = $bln;
	  }
	}
	
	if ($namaThn != null) {
	  if ($namaTgl != null || $namaBln != null) {
	    $xhtml = $xhtml."&nbsp;".$thn;
	  } else {
	    $xhtml = $thn;
	  }
	}
	
	return $xhtml;
  }
  
  /**
         Fungsi untuk konversi tanggal dari format indonesia DD-MM-YYYY menjadi format database YYYY-MM-DD
       */
  public function convertTglHumanToMachine($tglhuman) {
	$tgl = substr($tglhuman, 0, 2);
	//echo "<br>".$tgl;
	$bln = substr($tglhuman, 3, 2);
	//echo "<br>".$bln;
    $thn = substr($tglhuman, 6, 4);
	//echo "<br>".$thn;
	return $thn."-".$bln."-".$tgl;
  }
  
  /**
         Fungsi untuk konversi tanggal dari format database YYYY-MM-DD menjadi format indonesia DD-MM-YYYY
       */
  public function convertTglMachineToHuman($tglmachine) {
	$thn = substr($tglmachine, 0, 4);
	//echo "<br>".$thn;
	$bln = substr($tglmachine, 5, 2);
	//echo "<br>".$bln;
    $tgl = substr($tglmachine, 8, 2);
	//echo "<br>".$tgl;
	return $tgl."-".$bln."-".$thn;
  }
  
  /**
         Fungsi untuk konversi bulan dari format database MM menjadi format bulan singkat indonesia 
       */
	public function getNamaBulanSingkat($mm){

		$mm = $mm*1;
		$namaBulanArr = array('1' =>'Jan', 'Peb', 'Mar', 'Apr',  'Mei', 'Jun',  'Jul', 'Ags', 'Sep', 'Okt', 'Nop', 'Des');
		$namaBulan = $namaBulanArr[$mm];
		
		return $namaBulan;
	}

	/**
         Fungsi untuk konversi bulan dari format database MM menjadi format bulan indonesia 
       */

	public function getNamaBulan($mm)
	{
		$mm = $mm*1;
		$namaBulanArr = array('1' =>'Januari', 'Pebuari', 'Maret', 'April',  'Mei', 'Juni',  'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
		$namaBulan = $namaBulanArr[$mm];
	
		return $namaBulan;
	}
	
	/**
         Fungsi untuk konversi tanggal dari format databaseYYYY-MM-DD  menjadi format tanggal indonesia DD-Month-YYYY
       */
	public function formatTglLengkap($tglmachine){
		$convDate = new oa_date();
		
		$thn = substr($tglmachine, 0, 4);
		$bln = $convDate->getNamaBulan(substr($tglmachine, 5, 2));
		$tgl = substr($tglmachine, 8, 2);
	
		return $tgl."-".$bln."-".$thn;
	}
	
	public function formatTglSingkat($tglmachine){
		$convDate = new oa_date();
		
		$thn = substr($tglmachine, 0, 4);
		$thnSingkat = substr($tglmachine, 2, 2);
		$bln = $convDate->getNamaBulanSingkat(substr($tglmachine, 5, 2));
		$tgl = substr($tglmachine, 8, 2);
	
		return $tgl."-".$bln."-".$thnSingkat;
	}
}
?>
<script language="JavaScript" type="text/javascript">
<!--// calculate the ASCII code of the given character
function CalcKeyCode(aChar) {
  var character = aChar.substring(0,1);
  var code = aChar.charCodeAt(0);
  return code;
}

function checkNumber(val) {
  var strPass = val.value;
  var strLength = strPass.length;
  var lchar = val.value.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);

  if ((cCode < 48) || (cCode > 57)) {
    var myNumber = val.value.substring(0, (strLength) - 1);
    val.value = myNumber;
  }
  return false;
}
-->
</script>