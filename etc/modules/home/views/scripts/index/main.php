          <div class="blank"></div>
     	<div class="navlink2">Halaman Utama</div>
       
       <div class="leftmenu"  id="leftmenu"><!--start leftmenu-->
         <div class="ct-menu">
           <div class="hd">Tata Persuratan</div>
	   <div class="bd">
             <ul id="lsn">
               <li class="icon-inbox" >Surat Masuk
                 <ul id="lsn2">
                   <li><a class="icon-file" href="#">Surat</a>(1/20)</li>
                   <li><a class="icon-file" href="">Nota Dinas</a>(0/20)</li>
                   <li><a class="icon-file" href="memo.html">Memo</a>(1/10)</li>
                   <li><a class="icon-file" href="surat_dalam_proses.html">Surat Dalam Proses</a>(1/10)</li>
                 </ul>  
               </li>
               <li class="icon-sent" href="">Surat Keluar
                 <ul id="lsn2">
                   <li><a class="icon-file" href="">Surat</a>(1/20)</li>
                   <li><a class="icon-file" href="">Nota Dinas</a>(0/20)</li>
                 </ul>  
               </li>
               <li class="icon-inbox"><a href="?p=konsep_surat">Konsep Surat</a></li>
             </ul>
	   </div>
       <div class="ft"></div>		   
<div id="featureBalloonPos">
		<div id="featureBalloonTop"></div>
		<div id="featureBalloonContent"></div>
		
</div>
<script type="text/javascript">
//<![CDATA[
//IE PNG bug workarround

if(navigator.userAgent.toLowerCase().indexOf("msie")>0 && navigator.userAgent.toLowerCase().indexOf("msie 7.0")<=0){
	var imgArr = Array();
	imgArr[imgArr.length] = document.getElementById("featureBalloonTop");
	//imgArr[imgArr.length] = document.getElementById("featureBalloonLeft");
	imgArr[imgArr.length] = document.getElementById("featureBalloonContent");
	for(i=0;i<imgArr.length;i++)	{
		if(imgArr[i].currentStyle.backgroundImage.lastIndexOf(".png") != -1){
			var img = imgArr[i].currentStyle.backgroundImage.substring(5,imgArr[i].currentStyle.backgroundImage.length-2);
			//alert(img);
			imgArr[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+img+"', sizingMethod='scale')";
			imgArr[i].style.backgroundImage = "url(images/spacer.gif)";
		}
	}
}
ACEventArr = Array();
function Event(id, text, description, url) {
	this.id		= id;
	this.text	= text;
	this.description = description;
	this.url	= '';//url;
}

<?
  $sFile = file("event.txt");
  $s=sizeof($sFile);
    if ($s > 0) {
	while (list($c,$line) = each($sFile)) {
		list($TGL,$EVENT) = explode(";;",$line);
		$TGLID=ereg_replace("/","",$TGL);
		$MONTHARRAY = array('Januari','Februari','Maret','April','Mei','Juni','July','Agustus','September','Oktober','November','Desember');
		list($DAY,$MONTH,$YEAR) = explode("/",$TGL);
		
?>		
				   //<![CDATA[
				   ACEventArr[ACEventArr.length] = new Event("<?=$TGLID;?>","<?=$DAY." ".$MONTHARRAY[$MONTH-1]." ".$YEAR;?>","<?=$EVENT;?>");
				   //]]>
<?
	}	
   }
?>
function balloonText(featureId, aObj, e) {
	var posObj = document.getElementById("featureBalloonPos");
	var conObj = document.getElementById("featureBalloonContent");
	var accUrl = "";
	var accDesc = "";
	var accText = "";
	var lc = "id".toLowerCase();
	
	for(i = 0; i < ACEventArr.length; i++){
		if(ACEventArr[i].id == featureId){
			accDesc = ACEventArr[i].description;
			accText = ACEventArr[i].text;
			accUrl	= ACEventArr[i].url
			break;
		}
	}
	
	if(accUrl != ''){
		accDesc += "<br /><a href='spg.jsp?cc=id&amp;lc=id&amp;ver=4000&template=" + accUrl + "&zone=pp&lm=pp2&fid=" + featureId + "' class='link'><img  src='popup_files/CWS31AFW_9382high_56_0_4000.gif' border='0' alt='' />Baca selengkapnya</a>";
	}
	
	tmpObj = aObj;
	if (lc == 'he' || lc == 'ar' ){
		aObj.style.display = "block";
		aObj.style.width = "120px";
	}
	var curleft = 0;
	var curtop = 0;
	if (tmpObj.offsetParent){
		while (tmpObj.offsetParent)	{
			curtop += tmpObj.offsetTop;
			curleft += tmpObj.offsetLeft;
			tmpObj = tmpObj.offsetParent;
		}
	}
	else if (tmpObj.x){
		curtop += tmpObj.y;
		curleft += tmpObj.x;
	}
	
	
	posObj.style.display = "block";
	posObj.style.position = "absolute";
	
	if (lc == 'he' || lc == 'ar' ){
		posObj.style.left = (curleft+250) + "px";
	}
	else {
		posObj.style.left = (curleft-250) + "px";
	}
	
	posObj.style.top = (curtop) + "px";
	
	//headObj.innerHTML = accText;
	conObj.innerHTML = '<b>'+accText+'</b><br />'+accDesc+'<br /><br /><a href="javascript:closeBalloonText();void(0);" class="arrow" style="position:relative">Tutup jendela</a>';
	
	//IE bug workarround, stop for 350 milisecs, otherwise images are not shown.
	if(navigator.userAgent.toLowerCase().indexOf("msie")>0){
		d = new Date() //today's date
		while (1){
			mill=new Date() // Date Now
			diff = mill-d //difference in milliseconds
			if( diff > 350 ){
				break;
			}
		}
	}
}

function closeBalloonText() {
	document.getElementById("featureBalloonPos").style.display = "none";
}

//]]>
</script>

	   <div class="hd">Agenda</div>
	   <div class="bd">
<?
  $todayd=date("d",time())*1;
  $todaym=date("m",time())*1;
  $todayy=date("Y",time());
  $today="$todayd/$todaym/$todayy";
  echo "<DIV class=\"contentModule\" id='MYCAL'></DIV>\n";
  $sFile = file("event.txt");
  $sFile2 = file("event2.txt");
  $s=sizeof($sFile);
  $n=$s-1;
  $s2=sizeof($sFile2);
  $n2=$s2-1;
  echo "<script>\n";
  echo "var datedarray=new Array(";
  if ($s > 0) {
	while (list($c,$line) = each($sFile)) {
		list($TGL,$EVENT) = explode(";;",$line);
		if ($c==$n)  echo "'$TGL'";
		 else    echo "'$TGL', ";
		
	}	
   }
   echo ")\n";
 echo "var datedarray2=new Array(";
  if ($s2 > 0) {
	while (list($c2,$line2) = each($sFile2)) {
		list($TGL2,$EVENT2) = explode(";;",$line2);
		if ($c2==$n2)  echo "'$TGL2'";
		 else    echo "'$TGL2', ";
		
	}	
   }
   echo ")\n";
?>							
  var today="<?=$today?>";
</script>

<script type="text/javascript" src="js/kalender.js"></script>

           </div>
       <div class="ft"></div>		   
       <div class="hd">Ulang Tahun</div>
	   <div class="bd">
	     <div class="spacer"></div>
             <ul>
               <li>Irwan Setiawan</li>
               <li>Budi Suseno</li>
               <li>Arman Wicaksono</li>
             </ul>
	   </div>
       <div class="ft"></div>		   
 
         </div>  
       </div><!--end rightmenu-->
       
       