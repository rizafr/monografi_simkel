<?
class OA_View_Helper_Pager{
    
  public function pager($total,$rowsPerPage,$pageNum,$awal,$akhir){
    $numRows = $total;//total data db
	$rowsPerPage = $rowsPerPage;//jumlah per halaman
	$maxPage = ceil($numRows/$rowsPerPage);//jumlah halaman		
		$pageNum = $pageNum;//halaman
		$nav  = '';

		for($page = 1; $page <= $maxPage; $page++)
		{
		   if ($page == $pageNum)
		   {
		      $nav .= " $page "; // no need to create a link to current page
		   }
		   else
		   {
		      //$nav .= " <a href=\"$self?page=$page\">$page</a> ";
		      $nav .= " <a  class= 'newPage' href='#' onClick='getPage($page)'><span>
<strong>$page</strong><span class='page-sep'>, </span>
</span></a>";
		   } 
		}
		$dari = $awal+1;
		$sampai = $akhir-1;
		 
		echo "<div class='left'>Data dari ".$awal." - ".$akhir." dari total ".$numRows." data</div><br/>";
		echo "<div class='pagination'>
						<a title='Klik untuk langsung ke halaman ... ' onclick='jumpto(); return false;' href='#'>Halaman</a> :";
		if ($pageNum > 1)
		{
		   $page  = $pageNum - 1;
		   $prev  = " <a href='#' onClick='getPage($page)'><</a> ";
		   $first = " <a href='#' onClick='getPage(1)'><<</a> ";
		} 
		else
		{
		   $prev  = '&nbsp;'; // we're on page one, don't print previous link
		   $first = '&nbsp;'; // nor the first page link
		}

		if ($pageNum < $maxPage)
		{
		   $page = $pageNum + 1;
		   $next = " <a href='#' onClick='getPage($page)'>></a> ";

		   $last = " <a href='#' onClick='getPage($maxPage)'>>></a> ";
		} 
		else
		{
		   $next = '&nbsp;'; 
		   $last = '&nbsp;'; 
		}

		// print the navigation link

		$tampilan = $first . $prev . $nav . $next . $last;
		//echo $first . $prev . $nav . $next . $last;
		return $tampilan;
  
   }
  }
?>