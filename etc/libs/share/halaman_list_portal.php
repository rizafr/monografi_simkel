<?php 
require_once 'Zend/View.php';

class halaman_list {

	public function halamanList($totalData, $numToDisplay, $currentPage, $modul)
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
		$keluaran = "<div id=\"\"> </div>";		
		$keluaran = $keluaran." <div class=\"pagination alignright\">$totalData  Data  &bull;";		
		
		if($totalPages == 1)
		{
			$keluaran = $keluaran."Halaman $currentPage dari $totalPages &bull;";			
		}
		else
		{
			$keluaran = $keluaran."<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"nextTo('$modul','$totalPages'); return false;\" href=\"#\">Halaman $currentPage dari $totalPages</a> &bull; ";			
		}
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				$keluaran =  $keluaran . "<a href=\"#\" onClick=\"javascript:nextPage('$modul','$a');\">&laquo; sebelumnya</a>";
				
			}
		else{
			$keluaran =  $keluaran . " <span class=\"disabled\">&laquo; sebelumnya</span>";
		}	
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span class=\"current\">&nbsp;$a&nbsp;</span>";
				else
				{
					$keluaran =  $keluaran . "<a href=\"#\"  onClick=\"javascript:nextPage('$modul','$a');\">&nbsp;$a&nbsp;</a>";
				}
			}
			if($currentPage ==$totalPages){
				$keluaran =  $keluaran . "<span class=\"disabled\">berikutnya &raquo;</span>  ";
			}
			else{
				$a = $a -1;
				$keluaran =  $keluaran . " <a href=\"#\" onClick=\"javascript:nextPage('$modul','$a');\">berikutnya &raquo;</a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
				
					$keluaran =  $keluaran . "<span class=\"current\">&nbsp;$a&nbsp;</span>";
				else
				{

				$keluaran =  $keluaran . "<a href=\"#\" onClick=\"javascript:nextPage('$modul','$a');\">&nbsp;$a&nbsp;</a>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . " <a href=\"#\" onClick=\"javascript:nextPage('$modul','$a');\">berikutnya &raquo;</a>";
			}
		}	
	
		$keluaran = $keluaran."      </div>";
	
		return $keluaran;
	}
	
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
}
?>
<script>
function nextPage(url,currentPage)
{	
	var opt = {currentPage:currentPage};
	jQuery.get(url,opt,function(data) {
		$("#middle").html(data);
		});
}

function nextTo(url,totalPages){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, paging :'paging'};

		jQuery.get(url,opt,function(data) {
			jQuery("#middle").html(data);	
		});
	}
	else
	{		
		alert("halamanTerakhir : "+totalPages);
	}
	
}
</script>