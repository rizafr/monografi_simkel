<?php 

$_POST = array("kategori" => "Kebijakan Sektoral",
				"jprodukhukum" => "KEPMEN",
				"bumn_0" => "BMDR",
				"bumn_1" => "AABR",
				"tahunan" => "2005",
				"idNoProduk" => "KEP-44/MBU/2006",
				"tgl" => "01",
				"bln" => "01",
				"thn" => "2006",
				"idNamaProduk" => "test",
				"idFile" => "test.pdf");

function showResult($status, $msg, $result){
		header("Content-type: text/xml\n");
			$s = '<'.'?'.'xml version="1.0"'.'?'.'>';
			$s .= "<reply>\n";
			$s .= "<status>$status</status>\n";
			$s .= "<msg>$msg</msg>\n";
			$s .= "<result>$result</result>";
			$s .= "</reply>\n";
		
		echo $s;
}
	
function xml_hukum($dataPost)
{
	foreach($dataPost as $key =>$val)
			{	
				$temp = explode("_",$key);
				if ($temp[0] == "group")
				{
					$group = $_POST[$key];
					$param[$x] = array("i_user" => $username,
					   "n_user" => $nPeg,
					   "i_user_nip" => $iPegNip,
					   "n_user_dept" => $iOrgb,
					   "n_user_group" => $group);
				$x++;
				}
			}	
	/* $content = "<username>$username</username>\n
				    <bumn_id>$bumn_id</bumn_id>\n
				    <dp_no>$dpNo</dp_no>\n
				    <email>$email</email>\n
				    <phone>$phone</phone>\n
				    <level>$level</level>\n
			           ";
			
			// portal 
			if($info[0]["businesscategory"]["count"] > 0)
			$content .= "<portal>";
			for($i=0;$i<$info[0]["businesscategory"]["count"];$i++){
				$portal = $info[0][''][$i];
				$content .="<list>\n
						<nama>$portal</nama>\n
						<url>http://$portal.bumn.go.id/</url>\n
					    </list>
					   ";
			} */
}
				
function sendData($ref, $cmd, $dataxml, $kepada) {
	    if (!isset($cmd)) {
	        $cmd = '';
	    }
	    $param = array ('ref' => $ref, 'cmd' => $cmd, 'dataxml' => $dataxml);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $kepada);
	    curl_setopt($ch, CURLOPT_HEADER, 0); 
	    curl_setopt($ch, CURLOPT_POST, 1); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_VERBOSE, 1); 
	    $retval = curl_exec($ch);        
	    if (curl_errno($ch)) {
	        print "error curl: " . curl_error($ch) . "\n";
	    } else {
	        curl_close($ch);
	        if ($retval == 'OK') {
	            echo 'Sukses.';
	        } else {
	            echo 'Ada kesalahan, status="'.$retval.'"';
	        }
	    }
	}

?>