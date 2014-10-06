<?
//include "condb.php";

class oaservice{
	private static $instance;
   
    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
	function showResult($status, $msg, $result){
		$s = 'header("Content-type: text/xml\n")';
			$s = '<'.'?'.'xml version="1.0"'.'?'.'>';
			$s .= "<reply>\n";
			$s .= "<status>$status</status>\n";
			$s .= "<msg>$msg</msg>\n";
			$s .= "<result>$result</result>";
			$s .= "</reply>\n";
		
		echo $s;
	}

	function getNorg($lvlnorg){	
	    if ($lvlnorg=='1') { $likeby =" c_orgb_level='$lvlnorg'";}
		else { $likeby =" c_orgb_level='$lvlnorg' and (c_unit_kerja like 'DP%' or c_unit_kerja like 'SK%')";}
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$j = 0;
						
		if($lvlnorg=='1')
		{
			 $result = $db->fetchAll("select i_orgb,i_orgb_parent,c_orgb_level,n_orgb,
                                                     b.i_peg_nip, b.n_peg, b.c_eselon
                                              from e_org_0_0_tm a
                                              left join(select i_peg_nip, n_peg, c_eselon, c_unit_kerja from e_sdm_pegawai_0_tm) b on (a.i_orgb = b.c_unit_kerja)
                                              where c_unit_kerja = 'MN0000' and c_eselon = 'MT'
 ");

		}
		else
		{
		      $result = $db->fetchAll("select i_orgb,i_orgb_parent,c_orgb_level,n_orgb,
                                                     b.i_peg_nip, b.n_peg, b.c_eselon
                                              from e_org_0_0_tm a
                                              left join(select i_peg_nip, n_peg, c_eselon, c_unit_kerja from e_sdm_pegawai_0_tm  where c_peg_status = '3PN') b on (a.i_orgb = b.c_unit_kerja)
                                              where  $likeby and c_eselon in ('I.a','I.b','II.a','III.a','IV.a')
union select i_orgb,i_orgb_parent,c_orgb_level,n_orgb,
                                                     b.i_peg_nip, b.n_peg, b.c_eselon
                                              from e_org_0_0_tm a
                                              left join(select i_peg_nip, n_peg, c_eselon, c_unit_kerja from e_sdm_pegawai_0_tm  where c_peg_status = '3PN') b on (a.i_orgb = b.c_unit_kerja)
                                              where  $likeby and c_eselon is null
 ");					
		}


			$jmlResult = count($result);		
			if ($jmlResult==0) {$msg 	= "Daftar Organisasi Tidak Ada";}
			
                        
			for ($j = 0; $j < $jmlResult; $j++) 
			{
				//echo str_replace($result[$j]->n_orgb,"&amp","Hello world!");
				$par=$result[$j]->n_orgb;
				$par=str_replace("&","&amp;",$par);				
				$namaPejabat = $result[$j]->n_peg;
				if(!$namaPejabat)
				{
					$namaPejabat = "---";
				}
				$eselonArr = explode(".",$result[1]->c_eselon);

				$eselon = "Eselon ".$eselonArr[0];
				
				if ($lvlnorg=='1') {$eselon = "Menteri";}
				
		/*		if($j == 0)
				{
					$html = '<font style="color: rgb(106, 106, 106); font-family: Verdana; font-weight: bold; font-size: 9pt;">'.$eselon.'</font>';
					$html .= '<br><br><br>';
					$html .= '<ol>';
				}
		
				if ($lvlnorg=='1') {
					$html .= '<div style="margin-left: 40px;">';
					$html .= $namaPejabat.' : '.$par.'<br>';
					$html .= '</div>';
					
				}
				else
				{
					$html .= '<li>'.$namaPejabat.' : '.$par.'</li>';
				}
				if($j == $jmlResult-1)
				{
					$html .= '</ol>';
				}
*/

				if($j == 0)
				{
					 $html .= '<tr>';
					 $html .= '<th colspan="3" class="clcenter">'.$eselon.'</th>';
					 $html .= '<th  class="clcenter">&nbsp;</th>';
					 $html .= '</tr>';
				}

				 if($j%2 == 0)
                                { $even = "even";}
                                else
                                { $even = "even2";}

                                $html .= '<tr  class="'.$even.'">';

				$noUrut = $j+1;
			 if ($lvlnorg=='1') {
                                $html .= '<td class="clleft">&nbsp;</td>';
			 }
			 else
			 {
                                $html .= '<td class="clleft">&nbsp;'.$noUrut.'</td>';
			 }
                                $html .= '<td class="clleft">&nbsp;'.$namaPejabat.'</td>';
                                $html .= '<td class="clleft">&nbsp;:</td>';
                                $html .= '<td class="clleft">&nbsp;'.$par.'</td>';
                                $html .= '</tr>';

			}
			
			if($j%2 == 0)
			{ $even = "even2";}
			else
			{ $even = "even";}
			$html .= '<tr class="'.$even.'"><td colspan="4">&nbsp;</td></tr>';

			return $html;
		    
		}	   catch (Exception $e) {
         echo $e->getMessage();
	     $msg 	= $e;
		 $status = "ERROR";
	   }	
	}
	
	function list_pejabat()
	{
		$i = new oaservice();
		//$html = "";
		$out = '<div class="hdtitle" style="color: rgb(119, 0, 0);">';
		$out .= '<div class="tl">PEJABAT KEMENTERIAN NEGARA BUMN</div>';
		$out .= '<div class="tr"/>';
		$out .= '</div>';
		$out .= '<table class="tbl" width="100%" cellspacing="0" cellpadding="0" border="0">';
		$out .= $i->getNorg('1');
		$out .= $i->getNorg('2');
		$out .= $i->getNorg('3');
		//$out .= $i->getNorg('4');
		//$out .= $i->getNorg('5');
		$out .= '</table>';
		
		echo "$out";
	}

	function getNorgSub($lvlnorgsub){	
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$j = 0;
						
		     $result = $db->fetchAll("select i_orgb,i_orgb_parent,c_orgb_level,n_orgb,i_entry,d_entry 
									from e_org_0_0_tm where i_orgb_parent='$lvlnorgsub' and i_orgb like 'DP%' order by i_orgb ");
			$jmlResult = count($result);		
			if ($jmlResult==0) {$msg 	= "Daftar Organisasi Tidak Ada";}
			
			for ($j = 0; $j < $jmlResult; $j++) {
			{
				$content .= "<list>";
				$content .= "<corg>".$result[$j]->i_orgb."</corg>\n";
				$content .= "<norg>".$result[$j]->n_orgb."</norg>\n";
				$content .= "</list>";		
				$status = "OK";
				
			}

						$msg 	= "Daftar Organisasi";
		    
		}
		}	   catch (Exception $e) {
         echo $e->getMessage();
	     $msg 	= $e;
		 $status = "ERROR";
	   }
		$this->showResult($status,$msg,$content);	
	
}

// untuk mengeluakan usia
	function listPegUsia(){
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<td class="clleft">Komposisi Pegawai Berdasarkan Kelompok Usia</th>';
			$html .= '</tr>';
			$html .= '</table>';
			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<th rowspan="2">Kelompok Usia</th>';
			$html .= '<th colspan="3">Jumlah Pegawai</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th>Laki-laki</th>';
			$html .= '<th>Perempuan</th>';
			$html .= '<th>Total</th>';
			$html .= '</tr>';
			
			$j = 0;
			for($s=16; $s<=60; $s=$s+5)
			{
				$usiaAwal = $s;
				$usiaAkhir = $s+4;
				$usiaAkhirQuery = $s+5;

				if($usiaAwal == '16')
				{
					$kelUsia = "< 21";
				}
				else
				{
					$kelUsia = "$usiaAwal - $usiaAkhir";
				}
				
				$result1 = $db->fetchAll("
										select '$kelUsia' as kelompok, 
											(select count(*) 
												from e_sdm_pegawai_0_tm 
												where age(d_peg_lahir) between $usiaAwal and $usiaAkhirQuery and 
													c_peg_jeniskelamin='P' and
													c_peg_status = '3PN') as perempuan,
											(select count(*) 
												from e_sdm_pegawai_0_tm 
												where age(d_peg_lahir) between $usiaAwal and $usiaAkhirQuery and 
													c_peg_jeniskelamin='L' and
													c_peg_status = '3PN') as laki,
											(select count(*) 
												from e_sdm_pegawai_0_tm 
												where age(d_peg_lahir) between $usiaAwal and $usiaAkhirQuery and 
													c_peg_jeniskelamin in('L', 'P') and
													c_peg_status = '3PN') as total										");
				if($j%2 == 0)
				{ $even = "even";}
				else
				{ $even = "even2";}
				
				$html .= '<tr  class="'.$even.'">';
				$html .= '<td class="clcenter">&nbsp;'.$result1[0]->kelompok.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$result1[0]->laki.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$result1[0]->perempuan.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$result1[0]->total.'</td>';
				$html .= '</tr>';
				
				$totPegL = $totPegL + $result1[0]->laki;
				$totPegP = $totPegP + $result1[0]->perempuan;
				$totPeg = $totPeg + $result1[0]->total;
				$j++;
			}

			$html .= '<tr>';
			$html .= '<th class="clcenter">&nbsp;<b>Total</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegL.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegP.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPeg.'</b></th>';
			$html .= '</tr>';

			$html .= '</table>';

			echo $html;	
		    
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     $msg 	= "List Pegawai Berdasarkan Usia Tidak Ada";
	   }
	}


	function listPegPangkat(){
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
				$result = $db->fetchAll("
					select c_peg_golongan, a.jmlpegl, b.jmlpegp, c.jmlpeg
					from e_sdm_golongan_pangkat_tr m 
					left join (select b.max_gol, count(*) as jmlpegl 
								from e_sdm_pegawai_0_tm a , e_sdm_peg_golonganterakhir_vm b 
								where a.c_peg_jeniskelamin = 'L' and 
										b.i_peg_nip=a.i_peg_nip and
										a.c_peg_status = '3PN' group by b.max_gol) a on (m.c_peg_golongan = a.max_gol)
					left join (select b.max_gol, count(*) as jmlpegp 
								from e_sdm_pegawai_0_tm a , e_sdm_peg_golonganterakhir_vm b 
								where a.c_peg_jeniskelamin = 'P' and 
										b.i_peg_nip=a.i_peg_nip and
										a.c_peg_status = '3PN' group by b.max_gol ) b on (m.c_peg_golongan = b.max_gol)
					left join (select b.max_gol, count(*) as jmlpeg 
								from e_sdm_pegawai_0_tm a , e_sdm_peg_golonganterakhir_vm b 
								where a.c_peg_jeniskelamin in ('L','P') and 
										b.i_peg_nip=a.i_peg_nip and
										a.c_peg_status = '3PN' group by b.max_gol ) c on (m.c_peg_golongan = c.max_gol)
				");
										
			$jmlresult = count($result);

			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<td class="clleft">Komposisi Pegawai Berdasarkan Pangkat</th>';
			$html .= '</tr>';
			$html .= '</table>';
			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<th rowspan="2">Pangkat</th>';
			$html .= '<th colspan="3">Jumlah Pegawai</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th>Laki-laki</th>';
			$html .= '<th>Perempuan</th>';
			$html .= '<th>Total</th>';
			$html .= '</tr>';
			
			for ($j = 0; $j < $jmlresult; $j++) {
				if($j%2 == 0)
				{ $even = "even";}
				else
				{ $even = "even2";}
				
				if($result[$j]->jmlpegl)
				{ $jmlpegl = $result[$j]->jmlpegl; }
				else {$jmlpegl = 0;}
				
				if($result[$j]->jmlpegp)
				{ $jmlpegp = $result[$j]->jmlpegp; }
				else {$jmlpegp = 0;}
				
				if($result[$j]->jmlpeg)
				{ $jmlpeg = $result[$j]->jmlpeg; }
				else {$jmlpeg = 0;}
				
				$totPegL = $totPegL + $jmlpegl;
				$totPegP = $totPegP + $jmlpegp;
				$totPeg = $totPeg + $jmlpeg;

				$html .= '<tr  class="'.$even.'">';
				$html .= '<td class="clcenter">&nbsp;'.$result[$j]->c_peg_golongan.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpegl.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpegp.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpeg.'</td>';
				$html .= '</tr>';
				
			}
			$html .= '<tr>';
			$html .= '<th class="clcenter">&nbsp;<b>Total</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegL.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegP.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPeg.'</b></th>';
			$html .= '</tr>';

			$html .= '</table>';

			echo $html;	
		    
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     $msg 	= "List Pegawai Berdasarkan Pangkat Tidak Ada";
	   }
	}
//eselon pendidikan

	function listPegEselon(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select c_eselon, a.jmlpegl, b.jmlpegp, c.jmlpeg    
									from e_sdm_eselon_0_tr m
									left join (select c_eselon, count(*) as jmlpegl 
												from e_sdm_pegawai_0_tm 
												where c_peg_jeniskelamin = 'L' and 
													  c_peg_status = '3PN' 
												group by c_eselon) a using (c_eselon)
									left join (select c_eselon, count(*) as jmlpegp 
												from e_sdm_pegawai_0_tm 
												where c_peg_jeniskelamin = 'P' and 
													  c_peg_status = '3PN'
												group by c_eselon) b using (c_eselon)
									left join (select c_eselon, count(*) as jmlpeg 
												from e_sdm_pegawai_0_tm 
												where c_peg_jeniskelamin in ('P', 'L') and
													  c_peg_status = '3PN'
												group by c_eselon) c using (c_eselon) where c_eselon not in('NE')
									order by m.c_eselon
									");
										
				$jmlresult = count($result);	

				$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
				$html .= '<tr>';
				$html .= '<td class="clleft">Komposisi Pegawai Berdasarkan Eselon</th>';
				$html .= '</tr>';
				$html .= '</table>';
				$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
				$html .= '<tr>';
				$html .= '<th rowspan="2">Eselon</th>';
				$html .= '<th colspan="3">Jumlah Pegawai</th>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<th>Laki-laki</th>';
				$html .= '<th>Perempuan</th>';
				$html .= '<th>Total</th>';
				$html .= '</tr>';
				for ($j = 0; $j < $jmlresult; $j++) {
					if($j%2 == 0)
					{ $even = "even";}
					else
					{ $even = "even2";}
					
					if($result[$j]->jmlpegl)
					{ $jmlpegl = $result[$j]->jmlpegl; }
					else {$jmlpegl = 0;}
					
					if($result[$j]->jmlpegp)
					{ $jmlpegp = $result[$j]->jmlpegp; }
					else {$jmlpegp = 0;}
					
					if($result[$j]->jmlpeg)
					{ $jmlpeg = $result[$j]->jmlpeg; }
					else {$jmlpeg = 0;}
					
					$html .= '<tr  class="'.$even.'">';
					$html .= '<td class="clcenter">&nbsp;'.$result[$j]->c_eselon.'</td>';
					$html .= '<td class="clcenter">&nbsp;'.$jmlpegl.'</td>';
					$html .= '<td class="clcenter">&nbsp;'.$jmlpegp.'</td>';
					$html .= '<td class="clcenter">&nbsp;'.$jmlpeg.'</td>';
					$html .= '</tr>';

					
					$totPegL = $totPegL + $jmlpegl;
					$totPegP = $totPegP + $jmlpegp;
					$totPeg = $totPeg + $jmlpeg;
				}

				$html .= '<tr>';
				$html .= '<th class="clcenter">&nbsp;<b>Total</b></th>';
				$html .= '<th class="clcenter">&nbsp;<b>'.$totPegL.'</b></th>';
				$html .= '<th class="clcenter">&nbsp;<b>'.$totPegP.'</b></th>';
				$html .= '<th class="clcenter">&nbsp;<b>'.$totPeg.'</b></th>';
				$html .= '</tr>';

				$html .= '</table>';

				echo $html;		
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     $msg 	= "List Pegawai Eselon Tidak Ada";
	   }
	}
	
	function listPegPend(){
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
				$result = $db->fetchAll("select n_pend , c.jmlpegl, d.jmlpegp, e.jmlpeg
							FROM e_sdm_pend_0_tr
							left join (select z.c_pend,  count(*) as jmlpegl 
										from e_sdm_peg_pendterakhir_vm z, e_sdm_pegawai_0_tm y 
										where z.i_peg_nip = y.i_peg_nip and 
												y.c_peg_jeniskelamin = 'L' and
												y.c_peg_status = '3PN'
										group by z.c_pend) c using (c_pend)
							left join (select z.c_pend,  count(*) as jmlpegp 
										from e_sdm_peg_pendterakhir_vm z, e_sdm_pegawai_0_tm y 
										where z.i_peg_nip = y.i_peg_nip and 
												y.c_peg_jeniskelamin = 'P' and
												y.c_peg_status = '3PN'
										group by z.c_pend) d using (c_pend)
							left join(select z.c_pend,  count(*) as jmlpeg 
										from e_sdm_peg_pendterakhir_vm z, e_sdm_pegawai_0_tm y 
										where z.i_peg_nip = y.i_peg_nip and 
												y.c_peg_jeniskelamin in ('P', 'L') and
												y.c_peg_status = '3PN'
										group by z.c_pend) e using (c_pend)
							order by to_number(c_pend, '999')");
										
			$jmlresult = count($result);	
			
			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<td class="clleft">Komposisi Pegawai Berdasarkan Pendidikan</th>';
			$html .= '</tr>';
			$html .= '</table>';
			$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
			$html .= '<tr>';
			$html .= '<th rowspan="2">Pendidikan</th>';
			$html .= '<th colspan="3">Jumlah Pegawai</th>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<th>Laki-laki</th>';
			$html .= '<th>Perempuan</th>';
			$html .= '<th>Total</th>';
			$html .= '</tr>';
			
			for ($j = 0; $j < $jmlresult; $j++) {
				if($j%2 == 0)
				{ $even = "even";}
				else
				{ $even = "even2";}
				
				if($result[$j]->jmlpegl)
				{ $jmlpegl = $result[$j]->jmlpegl; }
				else {$jmlpegl = 0;}
				
				if($result[$j]->jmlpegp)
				{ $jmlpegp = $result[$j]->jmlpegp; }
				else {$jmlpegp = 0;}
				
				if($result[$j]->jmlpeg)
				{ $jmlpeg = $result[$j]->jmlpeg; }
				else {$jmlpeg = 0;}
				
				
				$html .= '<tr  class="'.$even.'">';
				$html .= '<td class="clcenter">&nbsp;'.$result[$j]->n_pend.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpegl.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpegp.'</td>';
				$html .= '<td class="clcenter">&nbsp;'.$jmlpeg.'</td>';
				$html .= '</tr>';

				$totPegL = $totPegL + $jmlpegl;
				$totPegP = $totPegP + $jmlpegp;
				$totPeg = $totPeg + $jmlpeg;		
			}
			$html .= '<tr>';
			$html .= '<th class="clcenter">&nbsp;<b>Total</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegL.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPegP.'</b></th>';
			$html .= '<th class="clcenter">&nbsp;<b>'.$totPeg.'</b></th>';
			$html .= '</tr>';
			$html .= '</table>';
		    echo $html;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     $msg 	= "List Pegawai Berdasarkan Pendidikan Tidak Ada";
	   }
	}
	
	function getJmlPegPerEselonPerUnit(){	
		
		$registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$j = 0;
			
			$dataTidakterkirim = $db->fetchAll("select * 
												from a_intgr_eselon_0_tm
												where c_intgr_status = 'tidak terkirim'
												");		
			$jmldataTidakterkirim = count($dataTidakterkirim);
			
			//if($jmldataTidakterkirim)
			//{
				$result = $db->fetchAll("select i_orgb,c_orgb_level,count (*) 
										from e_org_0_0_tm 
										where i_orgb not in ('KN0000') and c_orgb_level in('2')
									group by c_orgb_level,i_orgb order by c_orgb_level");
				$jmlResult = count($result);			
				
				if ($jmlResult==0) {$msg 	= "Daftar Eselon Unit Tidak Ada";}
				
				$total_es1 = 0;
				$total_es2 = 0;
				$total_es3 = 0;
				$total_es4 = 0;
				$total_staf = 0;
				/* $html = '<html>';
				$html .= '<body>'; */
				$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
				$html .= '<tr>';
				$html .= '<td class="clleft">Komposisi Pegawai Berdasarkan Unit Organisasi dan Eselon</th>';
				$html .= '</tr>';
				$html .= '<table class="tbl" width="100%" cellspacing="2" cellpadding="1" border="0">';
				$html .= '<tr>';
				$html .= '<th>No.</th>';
				$html .= '<th>Uraian</th>';
				$html .= '<th>Es-1</th>';
				$html .= '<th>Es-2</th>';
				$html .= '<th>Es-3</th>';
				$html .= '<th>Es-4</th>';
				$html .= '<th>Pelaksana</th>';
				$html .= '<th>Jml</th>';
				$html .= '</tr>';
				
				for ($j = 0; $j < $jmlResult; $j++) 
				{
					if($j%2 == 0)
					{ $even = "even";}
					else
					{ $even = "even2";}

					$dptorg = substr($result[$j]->i_orgb,0,2);
					if (($dptorg=='DP') || ($dptorg=='SK')) 
					{
						$c_eselon = 'I.a';
					}
					else 
					{
						$c_eselon = 'I.b';
					}
					
					if ($dptorg=='SK') 
					{
						$dptorg2 = substr($result[$j]->i_orgb,0,2);
					}
					else 
					{
						$dptorg2 = substr($result[$j]->i_orgb,0,3);
					}
					
					$norgb = $db->fetchOne('SELECT n_orgb FROM e_org_0_0_tm WHERE i_orgb = ?',$result[$j]->i_orgb);
					$es1 = $db->fetchOne("SELECT count(*) FROM e_sdm_pegawai_0_tm WHERE c_eselon ='$c_eselon' and c_unit_kerja like '$dptorg2%' and c_peg_status = '3PN'");
					$es2 = $db->fetchOne("SELECT count(*) FROM e_sdm_pegawai_0_tm WHERE c_eselon ='II.a' and c_unit_kerja like '$dptorg2%' and c_peg_status = '3PN'");
					$es3 = $db->fetchOne("SELECT count(*) FROM e_sdm_pegawai_0_tm WHERE c_eselon ='III.a' and c_unit_kerja like '$dptorg2%' and c_peg_status = '3PN'");
					$es4 = $db->fetchOne("SELECT count(*) FROM e_sdm_pegawai_0_tm WHERE c_eselon ='IV.a' and c_unit_kerja like '$dptorg2%' and c_peg_status = '3PN'");
					$staf = $db->fetchOne("SELECT count(*) FROM e_sdm_pegawai_0_tm WHERE c_eselon ='NE' and c_unit_kerja like '$dptorg2%' and c_peg_status = '3PN'");
					$total = $es1+$es2+$es3+$es4+$staf;
					$total_es1 = $total_es1 + $es1;
					$total_es2 = $total_es2 + $es2;
					$total_es3 = $total_es3 + $es3;
					$total_es4 = $total_es4 + $es4;
					$total_staf = $total_staf + $staf;
					$total_peg = $total_peg + $total;
					
					$par=$norgb;
					$par=str_replace("&","&amp;",$par);				
					
					$no = $j+1;
					
					$html .= '<tr class="'.$even.'">';
					$html .= '<td class="clcenter">&nbsp;'.$no.'</td>';
					$html .= '<td class="clleft">&nbsp;'.$par.'</td>';
					$html .= '<td class="clright">'.$es1.'&nbsp;</td>';
					$html .= '<td class="clright">'.$es2.'&nbsp;</td>';
					$html .= '<td class="clright">'.$es3.'&nbsp;</td>';
					$html .= '<td class="clright">'.$es4.'&nbsp;</td>';
					$html .= '<td class="clright">'.$staf.'&nbsp;</td>';
					$html .= '<td class="clright">'.$total.'&nbsp;</td>';
					$html .= '</tr>';
					
				}
				$persen_es1 = (round(($total_es1/$total_peg)*10000))/100;
				$persen_es2 = (round(($total_es2/$total_peg)*10000))/100;
				$persen_es3 = (round(($total_es3/$total_peg)*10000))/100;
				$persen_es4 = (round(($total_es4/$total_peg)*10000))/100;
				$persen_staf = (round(($total_staf/$total_peg)*10000))/100;
				$persen_peg = (round(($total_peg/$total_peg)*10000))/100;
				
				$html .= '<tr class="even2">';
				$html .= '<td class="clleft" colspan="2"><b>Total</b></td>';
				$html .= '<td class="clright">'.$total_es1.'&nbsp;</td>';
				$html .= '<td class="clright">'.$total_es2.'&nbsp;</td>';
				$html .= '<td class="clright">'.$total_es3.'&nbsp;</td>';
				$html .= '<td class="clright">'.$total_es4.'&nbsp;</td>';
				$html .= '<td class="clright">'.$total_staf.'&nbsp;</td>';
				$html .= '<td class="clright">'.$total_peg.'&nbsp;</td>';
				$html .= '</tr>';
				$html .= '<tr class="even2">';
				
				$html .= '<td class="clleft" colspan="2"><b>Persentase (%)</b></td>';
				$html .= '<td class="clright">'.$persen_es1.'&nbsp;</td>';
				$html .= '<td class="clright">'.$persen_es2.'&nbsp;</td>';
				$html .= '<td class="clright">'.$persen_es3.'&nbsp;</td>';
				$html .= '<td class="clright">'.$persen_es4.'&nbsp;</td>';
				$html .= '<td class="clright">'.$persen_staf.'&nbsp;</td>';
				$html .= '<td class="clright">'.$persen_peg.'&nbsp;</td>';
				$html .= '</tr>';
				$html .= '</table>';
				/* $html .= '</body>';
				$html .= '</html>';  */
			//}
		} catch (Exception $e) {
         echo $e->getMessage();
	     $msg 	= $e;
		 $status = "ERROR";
	   }
	  
	echo "$html";
}	
	


public function view_jabatan() {
		
/* 		$data = file_get_contents($this->_LDAP['server_integrasi_user'].'services.php?ref=kelola&pid=oa'.
							  '&ppwd=93563a4fdef66b26fe9386720e7389ba'.
							  '&userid='.$this->userid.
							  '&cmd=view_user'.
							  '&username='.$username); */
$url='10.1.99.162/oasso/webservice/service.php?ref=esunit&cmd=eselon_unit';
$data = file_get_contents($this->$url);						  
//echo $data;										
 		$hasil = simplexml_load_string($data);
		$status = $hasil->status;
		$hasilAkhir = array();
			$list = $hasil->result;			
			for($i=0;$i<count($list);$i++)
			{
				$es1 = $list[$i]->es1; 
				$es2 = $list[$i]->es2;
				$es3 = $list[$i]->es3;
				$es4 = $list[$i]->es4;
				$staft = $list[$i]->staft; 
				
				$hasilAkhir[$i] = array("es1" 	=> $es1,
										"es2" 	=> $es2,
										"es3" 	=> $es3,
										"es4" 	=> $es4,
										"staft"	=> $staft);
			}	
		echo $hasilAkhir[es1];  
		//echo $hasil;
	}	

//Fungsi untuk mengambil data Deputi
public function getDeputi($kodeOrg){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	try {
		if($kodeOrg)
		{
			$data = $db->fetchAll("select n_orgb,i_orgb
					from e_org_0_0_tm 
					where  c_orgb_level='2' and i_orgb = '$kodeOrg' and c_org_statsetuju='Y' order by i_orgb"
				 );
		}
		else
		{
			$data = $db->fetchAll("select n_orgb,i_orgb
					from e_org_0_0_tm 
					where  c_orgb_level='2' and i_orgb like 'DP%'  and c_org_statsetuju='Y' order by i_orgb"
				 );
		}
		//echo "jml di service = ".count($data);
		// for ($j = 0; $j < $data; $j++) {
			// $hasil[$j] = array("namaorg"	=>(string)$data[$j]->n_orgb); 
		// }
		return $data;
	}catch (Exception $e){
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	}
}

public function getAsdep($level, $parentOrg){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	
	try {
		$data = $db->fetchAll("select i_orgb,i_orgb_parent,c_orgb_level,n_orgb
								from e_org_0_0_tm
								where  c_orgb_level='$level' and i_orgb_parent = '$parentOrg'  and c_org_statsetuju='Y' ");
		return $data;
	}catch (Exception $e){
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	}
}

public function getSekmen($level){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	try {
		$data = $db->fetchAll("select n_orgb,i_orgb
					from e_org_0_0_tm 
					where  c_orgb_level='$level' and i_orgb like 'SK%'  and c_org_statsetuju='Y' order by i_orgb"
				 );
		return $data;
	}catch (Exception $e){
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	}
}

public function getInspektorat(){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	try {
		$data = $db->fetchAll("select n_orgb,i_orgb
					from e_org_0_0_tm 
					where i_orgb like 'IP%'  and c_org_statsetuju='Y' order by i_orgb"
				 );
		return $data;
	}catch (Exception $e){
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	}
}

public function getSA(){
	$registry = Zend_Registry::getInstance();
	$db = $registry->get('db');
	$db->setFetchMode(Zend_Db::FETCH_OBJ);
	try {
		$data = $db->fetchAll("select n_orgb,i_orgb
					from e_org_0_0_tm 
					where i_orgb like 'SA%'  and c_org_statsetuju='Y' order by i_orgb"
				 );
		return $data;
	}catch (Exception $e){
		echo $e->getMessage().'<br>';
		return 'Data tidak ada <br>';
	}
}	


}

?>
