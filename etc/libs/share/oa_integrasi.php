<?
require_once "share/oa.service.lib.php";

//include "oa.service.lib.php";
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
	$retval = trim(curl_exec($ch));        
	if (curl_errno($ch)) {
		print "error curl: " . curl_error($ch) . "\n";
	} else {
		curl_close($ch);
		if ($retval == 'OK') {
			echo '';
		} else {
			echo 'Ada kesalahan, pesan dari penerima="'.$retval.'"';
		}
	}
}

function struktur_organisasi_kn($server_oa, $server_client)
{
	$oaservice_serv = new oaservice();

	//$serverName = 'http://oa.bumn.go.id/ext/';
	$content= "";
	$a = "";
	
	//$content = 'header("Content-type: text/xml\n")';
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content."<strukturorganisasi_knbumn>\n";
	$content = $content."<arrays>\n";
	
	$namaKN = urlencode('Kementerian Negara BUMN');
	$urlKN = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=menteri');

	$content = $content.'<unit nama="'.$namaKN.'" url="'.$urlKN.'"></unit>'."\n";

	
	$namaSK = urlencode('Sekretariat Kementerian Negara BUMN');
	$urlSK = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=deputi&kodeOrg=SK0000');

	$content = $content.'<unit nama="'.$namaSK.'" url="'.$urlSK.'"></unit>'."\n";
	
	$kodeOrg = '';
	$dataDeputi = $oaservice_serv->getDeputi($kodeOrg);
	for($x=0;$x<count($dataDeputi); $x++)
	{
		$kodeDeputi = $dataDeputi[$x]->i_orgb;
		$namaDeputi = urlencode($dataDeputi[$x]->n_orgb);
		
		//echo "namaDeputi=$namaDeputi";
		$url = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=deputi&kodeOrg='.$kodeDeputi);
		$content = $content.'<unit nama="'.$namaDeputi.'" url="'.$url.'"></unit>'."\n";
	}

	
	$content = $content."</arrays>\n";
	$content = $content."</strukturorganisasi_knbumn>\n";
	
	return $content;
}

function struktur_organisasi_kn_xxx($server_oa, $server_client)
{
    $oaservice_serv = new oaservice();

	//$serverName = 'http://oa.bumn.go.id/ext/';
	$content= "";
	$a = "";
	
	//$content = htmlentities('header("Content-type: text/xml\n")';
	
	$content = $content.htmlentities("<?xml version=\"1.0\" ?>")."<br>";
	$content = $content.htmlentities("<strukturorganisasi_knbumn>")."<br>";
	
	$content = $content.htmlentities("<arrays>")."<br>";
	
	$namaKN = urlencode('Kementerian Negara BUMN');
	$urlKN = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=menteri');

	$content = $content.htmlentities('<unit nama="'.$namaKN.'" url="'.$urlKN.'"></unit>')."<br>";

	
	$namaSK = urlencode('Sekretariat Kementerian Negara BUMN');
	$urlSK = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=deputi&kodeOrg=SK0000');

	//echo "test = $urlSK";
	$content = $content.htmlentities('<unit nama="'.$namaSK.'" url="'.$urlSK.'"></unit>')."<br>";
	
	$kodeOrg = '';
	$dataDeputi = $oaservice_serv->getDeputi($kodeOrg);
	for($x=0;$x<count($dataDeputi); $x++)
	{
		$kodeDeputi = $dataDeputi[$x]->i_orgb;
		$namaDeputi = urlencode($dataDeputi[$x]->n_orgb);
		
		//echo "namaDeputi=$namaDeputi";
		$url = urlencode($server_oa.'/service.php?ref=strukturorganisasikn&level=deputi&kodeOrg='.$kodeDeputi);
		$content = $content.htmlentities('<unit nama="'.$namaDeputi.'" url="'.$url.'"></unit>')."<br>";
	}

	
	$content = $content.htmlentities("</arrays>")."<br>";
	$content = $content.htmlentities("</strukturorganisasi_knbumn>")."<br>";
	//echo $content;
	
	
	return $content;
	
	
}

function sdm_komposisi_pegawai($server_oa, $server_client)
{
        $serverSender = $server_oa;
        $serverClient = $server_client;

        $data_pegeselonunit = file_get_contents($serverSender.'/service.php?ref=esunit&cmd=eselon_unit');
        $data_pegeselon = file_get_contents($serverSender.'/service.php?ref=pegeselonpend&cmd=list_peg_eselon');
        $data_pegpendidikan = file_get_contents($serverSender.'/service.php?ref=pegeselonpend&cmd=list_peg_pend');
        $data_pegusia = file_get_contents($serverSender.'/service.php?ref=pegusia&cmd=list_peg_usia');
        $data_pegpangkat = file_get_contents($serverSender.'/service.php?ref=pegusia&cmd=list_peg_pangkat');
        $data_pejabat = file_get_contents($serverSender.'/service.php?ref=pejabat&cmd=list_pejabat');

        sendData('pegeselonunit', $cmd, $data_pegeselonunit, $serverClient.'/client.php');
        sendData('pegeselon', $cmd, $data_pegeselon,   $serverClient.'/client.php');
        sendData('pegpendidikan', $cmd, $data_pegpendidikan,   $serverClient.'/client.php');
        sendData('pegusia', $cmd, $data_pegusia,   $serverClient.'/client.php');
        sendData('pegpangkat', $cmd, $data_pegpangkat,   $serverClient.'/client.php');
        sendData('pejabat', $cmd, $data_pejabat,   $serverClient.'/client.php');
}

function xml_sdm_pribadi(array $data)
{
	$nip = $data['nip'];
	$nipH = $data['nipH'];
	$namaPegawai = trim($data['namaPegawai']);
	$gelarDpn = $data['gelarDpn'];
	$gelarBlk = $data['gelarBlk'];
	$karpeg = $data['karpeg'];
	$npwp = $data['npwp'];
	$jabatan = $data['jabatan'];
	$unitkerja = $data['pUnitKerja'];
	$unitOrg = $data['pUnitOrg'];
	$kdPenempatan = $data['kdPenempatan'];
	$tmtMasuk = $data['tmtMasuk'];
	$ukAsal = $data['ukAsal'];
	$jenisKelamin = $data['jenisKelamin'];
	$tmpLahir = $data['tmpLahir'];
	$tglLahir = $tglLahir;
	$pendidikan = $pendidikan;
	$agama = $data['agama'];
	$pangkat = $kGolPangkat;
	$alamat = $data['alamat'];
	$rt = $data['rt'];
	$rw = $data['rw'];
	$kelurahan = $data['kelurahan'];
	$kecamatan = $data['kecamatan'];
	$kabupaten = $data['nmKab'];
	$propinsi = $data['nmProp'];
	$kodePos = $data['kodePos'];
	$teleponRumah = $data['teleponRumah'];
	$tlpGenggam = $data['tlpGenggam'];
	$email = $data['email'];
	$email2 = $data['email2'];
	$stsNikah = $data['stsNikah'];
	$statusKerja = $data['statusKerja'];
	$jenisIdentitas = $data['jenisIdentitas'];
	$nomerIdentitas = $data['nomerIdentitas'];
	$kewarganegaraan = $data['kewarganegaraan'];
	$suku = $data['suku'];
	$hobi = $data['hobi'];
	$eselon = $data['eselon'];
	$tmtEselon = $data['tmtEselon'];
	$darah = $data['darah'];
	$anak = $data['anak'];
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<pribadi>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama"><![CDATA['.$namaPegawai.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Gelar Depan"><![CDATA['.$gelarDpn.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Gelar Belakang"><![CDATA['.$gelarBlk.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Karpeg"><![CDATA['.$karpeg.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="NPWP"><![CDATA['.$npwp.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Jabatan"><![CDATA['.$jabatan.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Organisasi Induk"><![CDATA['.$unitkerja.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Organisasi Skep"><![CDATA['.$unitOrg.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="Organisasi Penempatan"><![CDATA['.$kdPenempatan.']]></detail>'."\n";
	$content = $content.'<detail id="10" keterangan="TMT Masuk"><![CDATA['.$tmtMasuk.']]></detail>'."\n";
	$content = $content.'<detail id="11" keterangan="Organisasi Asal"><![CDATA['.$ukAsal.']]></detail>'."\n";
	$content = $content.'<detail id="12" keterangan="Jenis Kelamin"><![CDATA['.$jenisKelamin.']]></detail>'."\n";
	$content = $content.'<detail id="13" keterangan="Tempat Lahir"><![CDATA['.$tmpLahir.']]></detail>'."\n";
	$content = $content.'<detail id="14" keterangan="Tanggal Lahir"><![CDATA['.$tglLahir.']]></detail>'."\n";
	$content = $content.'<detail id="15" keterangan="Pendidikan"><![CDATA['.$pendidikan.']]></detail>'."\n";
	$content = $content.'<detail id="16" keterangan="Agama"><![CDATA['.$agama.']]></detail>'."\n";
	$content = $content.'<detail id="17" keterangan="Pangkat"><![CDATA['.$pangkat.']]></detail>'."\n";
	$content = $content.'<detail id="18" keterangan="Alamat"><![CDATA['.$alamat.']]></detail>'."\n";
	$content = $content.'<detail id="19" keterangan="RT"><![CDATA['.$rt.']]></detail>'."\n";
	$content = $content.'<detail id="20" keterangan="RW"><![CDATA['.$rw.']]></detail>'."\n";
	$content = $content.'<detail id="21" keterangan="Kelurahan"><![CDATA['.$kelurahan.']]></detail>'."\n";
	$content = $content.'<detail id="22" keterangan="Kecamatan"><![CDATA['.$kecamatan.']]></detail>'."\n";
	$content = $content.'<detail id="23" keterangan="Kabupaten"><![CDATA['.$kabupaten.']]></detail>'."\n";
	$content = $content.'<detail id="24" keterangan="Propinsi"><![CDATA['.$propinsi.']]></detail>'."\n";
	$content = $content.'<detail id="25" keterangan="Kodepos"><![CDATA['.$kodePos.']]></detail>'."\n";
	$content = $content.'<detail id="26" keterangan="Telepon Rumah"><![CDATA['.$teleponRumah.']]></detail>'."\n";
	$content = $content.'<detail id="27" keterangan="Telepon Genggam"><![CDATA['.$tlpGenggam.']]></detail>'."\n";
	$content = $content.'<detail id="28" keterangan="Email1"><![CDATA['.$email.']]></detail>'."\n";
	$content = $content.'<detail id="29 keterangan="Email2"><![CDATA['.$email2.']]></detail>'."\n";
	$content = $content.'<detail id="30" keterangan="Status Nikah"><![CDATA['.$stsNikah.']]></detail>'."\n";
	$content = $content.'<detail id="31" keterangan="Status Kerja"><![CDATA['.$statusKerja.']]></detail>'."\n";
	$content = $content.'<detail id="32" keterangan="Jenis Identitas"><![CDATA['.$jenisIdentitas.']]></detail>'."\n";
	$content = $content.'<detail id="33" keterangan="No Identitas"><![CDATA['.$nomerIdentitas.']]></detail>'."\n";
	$content = $content.'<detail id="34" keterangan="Kewarganegaraan"><![CDATA['.$kewarganegaraan.']]></detail>'."\n";
	$content = $content.'<detail id="35" keterangan="Suku"><![CDATA['.$suku.']]></detail>'."\n";
	$content = $content.'<detail id="36" keterangan="Eselon"><![CDATA['.$eselon.']]></detail>'."\n";
	$content = $content.'<detail id="37" keterangan="TMT Eselon"><![CDATA['.$tmtEselon.']]></detail>'."\n";
	$content = $content.'<detail id="38" keterangan="Darah"><![CDATA['.$darah.']]></detail>'."\n";
	$content = $content.'<detail id="39" keterangan="Anak"><![CDATA['.$anak.']]></detail>'."\n";
	$content = $content.'</pribadi>'."\n";
	$content = $content.'</pegawai>';
	
	echo $content;
	return $content;
}

function xml_sdm_pendidikan(array $data)
{
	$jenjangH = $data['jenjangH'];
	$nmjenjangH = $data['nmjenjangH'];
	$nip = $data['nip'];
	$kdjenjang = $data['kdjenjang'];
	$nmjenjang = $data['nmjenjang'];
	$pendidikan = $data['pendidikan'];
	$tempat = $data['tempat'];
	$jurusan = $data['jurusan'];
	$mulai = $data['mulai'];
	$akhir = $data['akhir'];
	$kepSek = $data['kepSek'];
	$ipk = $data['ipk'];
	$skripsi = $data['skripsi'];
	$biaya = $data['biaya'];
	$noIjazah = $data['noIjazah'];
	$tglIjazah = $data['tglIjazah'];
	$keterangan = $data['keterangan'];
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<pendidikan>'."\n";
	$content = $content.'<detail id="1" keterangan="Kode Jenjang Asal"><![CDATA['.$jenjangH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Nama Jenjang Asal"><![CDATA['.$nmjenjangH.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Kode Jenjang"><![CDATA['.$kdjenjang.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Nama Jenjang"><![CDATA['.$nmjenjang.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Nama Lembaga Pendidikan"><![CDATA['.$pendidikan.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tempat"><![CDATA['.$tempat.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Jurusan"><![CDATA['.$jurusan.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="Akhir"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="10" keterangan="Kepala Sekolah"><![CDATA['.$kepSek.']]></detail>'."\n";
	$content = $content.'<detail id="11" keterangan="ipk"><![CDATA['.$ipk.']]></detail>'."\n";
	$content = $content.'<detail id="12" keterangan="Judul Skripsi"><![CDATA['.$skripsi.']]></detail>'."\n";
	$content = $content.'<detail id="13" keterangan="Biaya"><![CDATA['.$biaya.']]></detail>'."\n";
	$content = $content.'<detail id="14" keterangan="No Ijazah"><![CDATA['.$noIjazah.']]></detail>'."\n";
	$content = $content.'<detail id="15" keterangan="Tanggal Ijazah"><![CDATA['.$tglIjazah.']]></detail>'."\n";
	$content = $content.'<detail id="16" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</pendidikan>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}

function xml_sdm_pelatihan(array $data)
{
	$pMulai = $data['pMulai'];
	$jenisLatih = $data['jenisLatih'];
	$nmPelatihan = $data['nmPelatihan'];
	$nip = $data['nip'];
	$penyelenggara = $data['penyelenggara'];
	$mulai = $data['mulai'];
	$akhir = $data['akhir'];
	$tempat = $data['tempat'];
	$noSertifikat = $data['noSertifikat'];
	$tglSertifikat = $data['tglSertifikat'];
	$keterangan = $data['keterangan'];
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<pelatihan>'."\n";
	$content = $content.'<detail id="1" keterangan="Tanggal Mulai Asal"><![CDATA['.$pMulai.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Jenis Pelatihan"><![CDATA['.$jenisLatih.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Nama Pelatihan"><![CDATA['.$nmPelatihan.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Penyelenggara"><![CDATA['.$penyelenggara.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tanggal Selesai"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Tempat"><![CDATA['.$tempat.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="No Sertifikat"><![CDATA['.$noSertifikat.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="Tanggal Sertifikat"><![CDATA['.$tglSertifikat.']]></detail>'."\n";
	$content = $content.'<detail id="10" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</pelatihan>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}

function xml_sdm_seminar(array $data)
{
	$nip = $data['nip'];
	$nmSem = $data['nmSem'];
	$nmSemH = $data['nmSemH'];
	$peran = $data['peran'];
	$mulai = $data['mulai'];
	$akhir = $data['akhir'];
	$penyelenggara = $data['penyelenggara'];
	$tempat = $data['tempat'];
	$keterangan = $data['keterangan'];
	
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<seminar>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama Seminar Asal"><![CDATA['.$nmSemH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Nama Seminar"><![CDATA['.$nmSem.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Peran"><![CDATA['.$peran.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tanggal Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Akhir"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Penyelenggara"><![CDATA['.$penyelenggara.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Tempat"><![CDATA['.$tempat.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</seminar>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}

$prmPangkatInsert = array("nip"			=>$_POST['nipH'],
									"gol"			=>$_POST['gol'],
									"tglTmt"		=>$tglTmt,
									"gaji"			=>$gajiTbl,
									"namaSK"		=>$_POST['namaSK'],
									"nomorSK"		=>$_POST['nomorSK'],
									"tglSK"			=>$tglSK,
									"jenis"			=>$_POST['jenis'],
									"keterangan"	=>$_POST['keterangan'],
									"kerjaThn"		=>$kerjaThn,
									"kerjaBln"		=>$kerjaBln,
									"user"			=>$user);
					
function xml_sdm_pangkat(array $data)
{
	$nip = $data['nip'];
	$gol = $data['gol'];
	$golH = $data['golH'];
	$tglTmt = $data['tglTmt'];
	$tmtH = $data['tmtH'];
	$gaji = $data['gaji'];
	$namaSK = $data['namaSK'];
	$nomorSK = $data['nomorSK'];
	$tglSK = $data['tglSK'];
	$jenis = $data['jenis'];
	$keterangan = $data['keterangan'];
	$kerjaThn = $data['kerjaThn'];
	$kerjaBln = $data['kerjaBln'];
	
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<jabatan>'."\n";
	$content = $content.'<detail id="1" keterangan="Golongan Asal"><![CDATA['.$golH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Tanggal TMT Asal"><![CDATA['.$tmtH.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Golongan"><![CDATA['.$gol.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tanggal TMT"><![CDATA['.$tglTmt.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Gaji Pokok"><![CDATA['.$gaji.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Nama SK"><![CDATA['.$namaSK.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="No SK"><![CDATA['.$nomorSK.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Tanggal SK"><![CDATA['.$tglSK.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="Jenis Kenaikan"><![CDATA['.$jenis.']]></detail>'."\n";
	$content = $content.'<detail id="10" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'<detail id="11" keterangan="Lama Kerja Tahun"><![CDATA['.$kerjaThn.']]></detail>'."\n";
	$content = $content.'<detail id="12" keterangan="Lama Kerja Bulan"><![CDATA['.$kerjaBln.']]></detail>'."\n";
	$content = $content.'</jabatan>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}
					
function xml_sdm_jabatan(array $data)
{
	$nip = $data['nip'];
	$nmJabat = $data['nmJabat'];
	$mulai = $data['mulai'];
	$nmJabatH = $data['nmJabatH'];
	$mulaiH = $data['mulaiH'];
	$akhir = $data['akhir'];
	$gol = $data['gol'];
	$gaji = $data['gaji'];
	$nmSK = $data['nmSK'];
	$noSK = $data['noSK'];
	$tglSK = $data['tglSK'];
	$eselon = $data['eselon'];
	$eselon1 = $data['eselon1'];
	$eselon2 = $data['eselon2'];
	$eselon3 = $data['eselon3'];
	$eselon4 = $data['eselon4'];
	$noLantik = $data['noLantik'];
	$tglLantik = $data['tglLantik'];
	$keterangan = $data['keterangan'];
	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<pangkat>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama Jabatan Asal"><![CDATA['.$nmJabatH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Tanggal Mulai Asal"><![CDATA['.$mulaiH.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Nama Jabatan"><![CDATA['.$nmJabat.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tanggal Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Akhir"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Golongan"><![CDATA['.$gol.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Gaji Pokok"><![CDATA['.$gaji.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Nama SK"><![CDATA['.$nmSK.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="No SK"><![CDATA['.$noSK.']]></detail>'."\n";
	$content = $content.'<detail id="10" keterangan="Eselon"><![CDATA['.$eselon.']]></detail>'."\n";
	$content = $content.'<detail id="11" keterangan="Eselon1"><![CDATA['.$eselon1.']]></detail>'."\n";
	$content = $content.'<detail id="12" keterangan="Eselon2"><![CDATA['.$eselon2.']]></detail>'."\n";
	$content = $content.'<detail id="13" keterangan="Eselon3"><![CDATA['.$eselon3.']]></detail>'."\n";
	$content = $content.'<detail id="14" keterangan="Eselon4"><![CDATA['.$eselon4.']]></detail>'."\n";
	$content = $content.'<detail id="15" keterangan="No Pelantikan"><![CDATA['.$noLantik.']]></detail>'."\n";
	$content = $content.'<detail id="16" keterangan="Tanggal Pelantikan"><![CDATA['.$tglLantik.']]></detail>'."\n";
	$content = $content.'<detail id="17" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</pangkat>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}
									
function xml_sdm_sertifikasi(array $data)
{
	$nip = $data['nip'];
	$nmSerH = $data['nmSerH'];
	$noSerH = $data['noSerH'];
	$nmSer = $data['nmSer'];
	$noSer = $data['noSer'];
	$tglSer = $data['tglSer'];
	$mulai = $data['mulai'];
	$akhir = $data['akhir'];
	$lembaga = $data['lembaga'];
	$keterangan = $data['keterangan'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<sertifikasi>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama Sertifikat Asal"><![CDATA['.$nmSerH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="No Sertidikat Asal"><![CDATA['.$noSerH.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Nama Sertifikat"><![CDATA['.$nmSer.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="No Sertidikat"><![CDATA['.$noSer.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Sertifikat"><![CDATA['.$tglSer.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tanggal Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Tanggal Akhir"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Lembaga"><![CDATA['.$lembaga.']]></detail>'."\n";
	$content = $content.'<detail id="9" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</pangkat>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}									
									
function xml_sdm_organisasi(array $data)
{
	$nip = $data['nip'];
	$nmOrgH = $data['nmOrgH'];
	$nmOrg = $data['nmOrg'];
	$jabatan = $data['jabatan'];
	$mulai = $data['mulai'];
	$akhir = $data['akhir'];
	$tempat = $data['tempat'];
	$pimpinan = $data['pimpinan'];
	$keterangan = $data['keterangan'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<organisasi>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama Organisasi Asal"><![CDATA['.$nmOrgH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Nama Organisasi"><![CDATA['.$nmOrg.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Jabatan"><![CDATA['.$jabatan.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tanggal Mulai"><![CDATA['.$mulai.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Akhir"><![CDATA['.$akhir.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tempat"><![CDATA['.$tempat.']]></detail>'."\n";
	$content = $content.'<detail id="7" keterangan="Pimpinan"><![CDATA['.$pimpinan.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</organisasi>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}	
						
function xml_sdm_penghargaan(array $data)
{
	$nip = $data['nip'];
	$nmHargaH = $data['nmHargaH'];
	$nmHarga = $data['nmHarga'];
	$tahun = $data['tahun'];
	$lembaga = $data['lembaga'];
	$noSurat = $data['noSurat'];
	$tglSurat = $data['tglSurat'];
	$keterangan = $data['keterangan'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<penghargaan>'."\n";
	$content = $content.'<detail id="1" keterangan="Nama Penghargaan Asal"><![CDATA['.$nmHargaH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Nama penghargaan"><![CDATA['.$nmHarga.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Tahun"><![CDATA['.$tahun.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Lembaga"><![CDATA['.$lembaga.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="No Surat"><![CDATA['.$noSurat.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tanggal Surat"><![CDATA['.$tglSurat.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</penghargaan>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}	

function xml_sdm_cirifisik(array $data)
{
	$nip = $data['nip'];
	$tinggi = $data['tinggi'];
	$berat = $data['berat'];
	$rambut = $data['rambut'];
	$bentukMuka = $data['bentukMuka'];
	$warnaKulit = $data['warnaKulit'];
	$ciriKhas = $data['ciriKhas'];
	$cacatTubuh = $data['cacatTubuh'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<cirifisik>'."\n";
	$content = $content.'<detail id="1" keterangan="Tinggi Badan"><![CDATA['.$tinggi.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Berat Badan"><![CDATA['.$berat.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Rambut"><![CDATA['.$rambut.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Bentuk Muka"><![CDATA['.$bentukMuka.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Warna Kulit"><![CDATA['.$warnaKulit.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Ciri Khas"><![CDATA['.$ciriKhas.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="Cacat Tubuh"><![CDATA['.$cacatTubuh.']]></detail>'."\n";
	$content = $content.'</cirifisik>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}	

function xml_sdm_kesehatan(array $data)
{
	$nip = $data['nip'];
	$tglSakitH = $data['tglSakitH'];
	$tglSakit = $data['tglSakit'];
	$namaPenyakit = $data['namaPenyakit'];
	$tglSembuh = $data['tglSembuh'];
	$namaRS = $data['namaRS'];
	$alamatRS = $data['alamatRS'];
	$keterangan = $data['keterangan'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<kesehatan>'."\n";
	$content = $content.'<detail id="1" keterangan="Tanggal Sakit Asal"><![CDATA['.$tglSakitH.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Tanggal Sakit"><![CDATA['.$tglSakit.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Nama Penyakit"><![CDATA['.$namaPenyakit.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tanggal Sembuh"><![CDATA['.$tglSembuh.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Nama RS"><![CDATA['.$namaRS.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Alamat RS"><![CDATA['.$alamatRS.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</kesehatan>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}	

function xml_sdm_keluarga(array $data)
{
	$nip = $data['nip'];
	$kdHub = $data['kdHub'];
	$prmHub = $data['prmHub'];
	$nama = $data['nama'];
	$tempatLahir = $data['tempatLahir'];
	$tglLahir = $data['tglLahir'];
	$jenisKelamin = $data['jenisKelamin'];
	$tglMenikah = $data['tglMenikah'];
	$pekerjaan = $data['pekerjaan'];
	$statusTanggungan = $data['statusTanggungan'];
	$karis = $data['karis'];
	$pendidikan = $data['pendidikan'];
	$nipSuami = $data['nipSuami'];
	$keterangan = $data['keterangan'];

	
	$content= "";
	$content = $content.'<'.'?'.'xml version="1.0"'.'?'.'>'."\n";
	$content = $content.'<pegawai nip="'.$nip.'">'."\n";
	$content = $content.'<keluarga>'."\n";
	$content = $content.'<detail id="1" keterangan="Hubungan Asal"><![CDATA['.$kdHub.']]></detail>'."\n";
	$content = $content.'<detail id="2" keterangan="Hubungan"><![CDATA['.$prmHub.']]></detail>'."\n";
	$content = $content.'<detail id="3" keterangan="Nama"><![CDATA['.$nama.']]></detail>'."\n";
	$content = $content.'<detail id="4" keterangan="Tempat Lahir"><![CDATA['.$tempatLahir.']]></detail>'."\n";
	$content = $content.'<detail id="5" keterangan="Tanggal Lahir"><![CDATA['.$tglLahir.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Jenis Kelamin"><![CDATA['.$jenisKelamin.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Tanggal Menikah"><![CDATA['.$tglMenikah.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Pekerjaan"><![CDATA['.$pekerjaan.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Status tanggungan"><![CDATA['.$statusTanggungan.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Nomor KARIS"><![CDATA['.$karis.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="Pendidikan"><![CDATA['.$pendidikan.']]></detail>'."\n";
	$content = $content.'<detail id="6" keterangan="NIP Suami"><![CDATA['.$nipSuami.']]></detail>'."\n";
	$content = $content.'<detail id="8" keterangan="keterangan"><![CDATA['.$keterangan.']]></detail>'."\n";
	$content = $content.'</keluarga>'."\n";
	$content = $content.'</pegawai>';
	
	return $content;
}	
									
?>
