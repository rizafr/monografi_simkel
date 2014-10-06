<?
class halamanCari{
    function halamanCari() {
       //echo 'I am constructed';
    }
  public function navPagerCari($total,$rowsPerPage,$pageNum,$awal,$akhir,$kategori,$key){
   $numRows = $total;//total data db
$rowsPerPage = $rowsPerPage;//jumlah per halaman
$maxPage = ceil($numRows/$rowsPerPage);//jumlah halaman
// print the link to access each page
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
	  
      $onklik = "getPageSearch('$page','$kategori','$key')";      	  
      $nav .= " <a href='#' onClick=$onklik>$page</a>";
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
   $prev  = " <a href='#' onClick=$onklik><</a> ";
   $first = " <a href='#' onClick=$onklik><<</a> ";
} 
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href='#' onClick=$onklik>></a> ";

   $last = " <a href='#' onClick=$onklik>>></a> ";
} 
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link

$tampilan = $first . $prev . $nav . $next . $last;
//echo $first . $prev . $nav . $next . $last;
return $tampilan;
  
  }
  public function navPagerPenelusuran($total,$rowsPerPage,$pageNum,$awal,$akhir,$jeniskoleksi,$kategori,$key){
   $numRows = $total;//total data db
$rowsPerPage = $rowsPerPage;//jumlah per halaman
$maxPage = ceil($numRows/$rowsPerPage);//jumlah halaman
// print the link to access each page
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
      $onklik = "getPageSearchPenelusuran('$page','$jeniskoleksi','$kategori','$key','$rowsPerPage')";      	  
      $nav .= " <a href='#' onClick=$onklik>$page</a>";
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
   $prev  = " <a href='#' onClick='$onklik'><</a> ";
   $first = " <a href='#' onClick='$onklik'><<</a> ";
} 
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href='#' onClick='$onklik'>></a> ";

   $last = " <a href='#' onClick='$onklik'>>></a> ";
} 
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link

$tampilan = $first . $prev . $nav . $next . $last;
//echo $first . $prev . $nav . $next . $last;
return $tampilan;
  
  } 
  public function navPagerPenelusuranBySubjek($total,$rowsPerPage,$pageNum,$awal,$akhir,$jeniskoleksi,$key1,$key2,$key3)  {
   $numRows = $total;//total data db
$rowsPerPage = $rowsPerPage;//jumlah per halaman
$maxPage = ceil($numRows/$rowsPerPage);//jumlah halaman
// print the link to access each page
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
      $onklik = "getPageSearchPenelusuranBySubjek($page,$jeniskoleksi,$key1,$key2,$key3,$rowsPerPage)";      	  
      $nav .= " <a href='#' onClick='$onklik'>$page</a>";
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
   $prev  = " <a href='#' onClick='$onklik'><</a> ";
   $first = " <a href='#' onClick='$onklik'><<</a> ";
} 
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href='#' onClick='$onklik'>></a> ";

   $last = " <a href='#' onClick='$onklik'>>></a> ";
} 
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link

$tampilan = $first . $prev . $nav . $next . $last;
//echo $first . $prev . $nav . $next . $last;
return $tampilan;
  
  }   

  }
?>