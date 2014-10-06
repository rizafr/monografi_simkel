<?
function pushData($table, $command, $id, $data) {
  pushData1($table, $command, $id, $data, 'http://eis.bumn.go.id/client.php');
  //pushData1($table, $command, $id, $data, 'http://pkbl.bumn.go.id/services/index/client');
}

function pushData1($table, $command, $id, $data, $tujuan) {

	global $portal_urls;
	
	$qs = "table=$table&command=$command&id=$id&";

	/*foreach ($id as $key=>$val)
		$qs .= "$key=$val&";
*/

	foreach ($data as $key=>$val)
		$qs .= "$key=". urlencode($val) . "&";
		

	//echo "$u?$qs";

echo $id . " | ".$qs;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qs);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1); 
		curl_setopt($ch, CURLOPT_URL,$tujuan);

		$reply = curl_exec($ch);        
		if (curl_errno($ch)) 
		{
			$s .= "$nama $u: error curl: ";
			$s .= curl_error($ch);
			$s .= "<br>";
		}
		else
		{
			curl_close($ch);
			if(trim($reply)== "OK")
			{
				$s="OK";
			}
			else
			{
				$s="$nama ($u): [".$reply . "]<br>";
			}
		}
		
	return $s;
}
?>
