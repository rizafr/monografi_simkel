<marquee scrollamount="3" width="954" direction="right" valign="bottom" onmouseout="this.start()" onmouseover="this.stop()" >
<?
foreach ($this->pengumumanList as $key => $val):
	$ePengumuman = $ePengumuman."		".$this->escape($val['e_pengumuman']);
	
endforeach;

echo $ePengumuman;
?>
</marquee>
