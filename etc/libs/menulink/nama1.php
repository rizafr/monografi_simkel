<?
	$vFoto1 = "http://".$_SERVER['SERVER_NAME'].$this->basePath.'/util/tampilfoto/tampilfoto?f='.$i_nik;
	$filename = trim($i_nik).".jpg"; 
	$a = "../etc/data/kp/photo/".$filename;
	$fullpath= "/util/tampilfoto/tampilfoto?f=$i_nik";
		
	if (!file_exists($a)) {
		$vFoto = $this->basePath."/images/nophoto.jpg";	
	} 
	else
	{
		$vFoto = $vFoto1;
	}

?>
<fieldset class="panel-form">
	<table width="100%" border="0" cellpadding="3" cellspacing="1" >
		<tr>
			<td width="90%">
			
				<table width="100%" border="0" cellpadding="3" cellspacing="1" >
					<tr>
						<td width="100"><font color="black" face="arial" size="2">NIK</font></td>
						<td width="5">:</td>
						<td><font color="black" face="arial" size="2"><?=$i_nik;?></font></td>
					</tr>	
					<tr>  
						<td><font color="black" face="arial" size="2">Nama</font></td>
						<td>:</td>
						<td><font color="black" face="arial" size="2"><?=$n_nama;?></font></td>
					</tr>
				</table>
			</td>
			
			<td width="10%">
				<img  style="border:1px solid #AAA;padding:0" src="<?=$vFoto?>" height="90px" width="80px"/>
			</td>			
			
		</tr>
	</table>		
</fieldset>	
		
		
		