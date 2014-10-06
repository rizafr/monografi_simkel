<?php 
require_once 'Zend/View.php';

class oa_paging {

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
	
	public function showPager($totalData,$numToDisplay,$currentPage,$homeimg)
	{
	   $totalPages = $this->pager($totalData,$numToDisplay);
	   if ($currentPage>1) $prevpage = $currentPage+1;
	   else $prevpage=1;
	   if ($totalPages<($currentPage+1)) $nextpage=$currentPage+1;
	   else $nextpage = $totalPages;
	   $html="";
	   $html=$html."Halaman ";
	   $html=$html."<img src='".$homeimg."/images/arrowleft.gif' id='imcPage' class='mcPages' style='cursor:pointer' value='".$prevpage."'>&nbsp;";
	   $html=$html."<input type='text' value='".$currentPage."' id='iiPage' class='icPages' style='width:50px'> dari ";
	   $html=$html.$totalPages."&nbsp;<img src='".$homeimg."/images/arrowright.gif' id='imPage' class='imcPages' style='cursor:pointer' value='".$nextpage."'>";
       return $html;
	}

	public function showPage($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4,$homeimg)
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
		$dataAkhir = $currentPage * $numToDisplay ;
		
		$keluaran = "";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		if($currentPage < 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$a','$param1','$param2','$param3','$param4');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$a','$param1','$param2','$param3','$param4');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
                   $keluaran =  $keluaran . "<input class='page-sep' type='submit' id='currentPage' value='$a' name='currentPage' style='border-style:none;width:20px;height:15px;background:#eeeeee'>
					<span class=\"page-sep\">, </span></span>";
					//$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$a','$param1','$param2','$param3','$param4');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$modul','$a','$param1','$param2','$param3','$param4');\"><strong>&#62;</strong></a>";
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
                   //$keluaran =  $keluaran . "<input class='page-sep' type='submit' id='currentPage' value='$a' name='currentPage' style='border-style:none;width:20px;height:15px;background:#eeeeee'><img src='".$homeimg."/images/garuda.gif' id='iijump' class='icjump' style='width:15px;height:15px'>
					//<span class=\"page-sep\">, </span></span>";
				$keluaran =  $keluaran . "<span><a href='#' class='icjump' id='iijump' currentPage='".$a."' $param3>&nbsp;$a&nbsp;</a><input type='image' src='".$homeimg."/images/garuda.gif' id='iijump' value='".$a."' name='iijump' class='icjump' style='width:15px;height:15px'>
					<span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" ><strong>&#62;</strong>
				<span class=\"page-sep\">, </span></a>&nbsp;";
			}
			if ($currentPage <= $totalPages)
			{
				$a = $totalPages;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" >&#62;&#62;
				<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		$keluaran = $keluaran."      </div>";
	
		return $keluaran;
	}
}
?>
