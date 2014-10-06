<?php
class Aplikasi_Pengumuman_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }

	//======================================================================
	// List Pengumuman
	//======================================================================
	public function cariPengumumanList(array $dataMasukan) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$pageNumber 	= $dataMasukan['pageNumber'];
		$itemPerPage 	= $dataMasukan['itemPerPage'];
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	   
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			$whereBase = "where c_statusdelete != 'Y' or c_statusdelete is null";
			$whereOpt = "$kategoriCari like '%$katakunciCari%' ";
			if($kategoriCari) { $where = $whereBase." and ".$whereOpt;} 
			else { $where = $whereBase;}
			$order = "order by $sortBy $sort ";
			$sqlProses = "select id, e_pengumuman, c_statusaktif, i_entry, d_entry from tm_pengumuman ";	

			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses.$where) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses.$where.$order;//." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);	
			}
			
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  	=>(string)$result[$j]->id,
									   "e_pengumuman"  	=>(string)$result[$j]->e_pengumuman,
									   "c_statusaktif" 		=>(string)$result[$j]->c_statusaktif,
									   "i_entry"      		=>(string)$result[$j]->i_entry,
									   "d_entry"      		=>(string)$result[$j]->d_entry
										);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function pengumumanInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("e_pengumuman"	=>$dataMasukan['e_pengumuman'],
							   "c_statusaktif"  =>$dataMasukan['c_statusaktif'],
							   "i_entry"       	=>$dataMasukan['i_entry'],
							   "d_entry"       	=>date("Y-m-d"));		
			$db->insert('tm_pengumuman',$paramInput);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function detailPengumumanById($iPengumuman) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = "where i_pengumuman = '$iPengumuman' ";
			$sqlProses = "select i_pengumuman,e_pengumuman, c_statusaktif, i_entry, d_entry from tm_pengumuman ";	

			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  	=>(string)$result->id,
								   "e_pengumuman"  	=>(string)$result->e_pengumuman,
								   "c_statusaktif" 		=>(string)$result->c_statusaktif,
								   "i_entry"      		=>(string)$result->i_entry,
								   "d_entry"      		=>(string)$result->d_entry
									);
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function pengumumanUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("e_pengumuman"	=>$dataMasukan['e_pengumuman'],
							   "c_statusaktif"  =>$dataMasukan['c_statusaktif'],
							   "i_entry"       	=>$dataMasukan['i_entry'],
							   "d_entry"       	=>date("Y-m-d"));	
								
			$where[] = "id = '".$dataMasukan['id']."'";
			
			$db->update('tm_pengumuman',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function pengumumanHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y',
							   "i_entry"       	=>$dataMasukan['i_entry'],
							   "d_entry"       	=>date("Y-m-d"));	
								
			$where[] = "id = '".$dataMasukan['id']."'";
			
			$db->update('tm_pengumuman',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}













	
//=======================SEARCH PEGAWAI=============================================
		
	//  fungsi untuk menampilkan data personal dari tabel personal_pokok_tm
	//=============================================================
	
	public function getPersonalListByUser($nip, $nama, $stat, $pageNumber, $itemPerPage, $cSatminkal) {
	//echo "masuk getPegawaiListByUser"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   if (($nip == 'undefined') || ($nip == null)) {
			$nip ='';
	   }
	   if (($nama == 'undefined') || ($nama == null)) {
			$nama ='';
	   }
	   
	    if (($stat == 'undefined') || ($stat == null)|| ($stat == '-')) {
			 $stat ='';
	   }
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			//echo "Stat : ".$stat;
			
						 /* if($stat == '')
						 {
							$sql1 = "select count(*) 
									FROM
									(SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
									a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
									a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
									a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
									a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
									FROM personal_pokok_tm a
									LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
									WHERE upper(a.n_peg) like upper('%$nama%') AND
									a.i_peg_nip like upper('%$nip%') AND
									(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)
									) x";		

							$sql2 = "SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
									a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
									a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
									a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
									a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
									FROM personal_pokok_tm a
									LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
									WHERE upper(a.n_peg) like upper('%$nama%') AND
									a.i_peg_nip like upper('%$nip%') AND
									(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)
									ORDER BY b.i_peg_nip limit $xLimit offset $xOffset";	
						 }
						 else
						 {
							$sql1 = "select count(*) 
									FROM
									(SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
									a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
									a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
									a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
									a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
									FROM personal_pokok_tm a
									LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
									WHERE upper(a.n_peg) like upper('%$nama%') AND
									a.i_peg_nip like upper('%$nip%') AND
									(b.c_peg_status like '%$stat%')
									) x";		

							$sql2 = "SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
									a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
									a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
									a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
									a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
									FROM personal_pokok_tm a
									LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
									WHERE upper(a.n_peg) like upper('%$nama%') AND
									a.i_peg_nip like upper('%$nip%') AND
									(b.c_peg_status like '%$stat%')
									ORDER BY b.i_peg_nip limit $xLimit offset $xOffset";	
						  }	 */	
				
			if($stat == '')
			{//echo "TES";
				/*$sql1 = "select count(*) 
					FROM
					(SELECT a.i_peg_nip, a.n_peg, c.c_peg_golongan, a.n_peg_gelardepan, a.n_peg_gelarblkg,
					a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
					a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
					a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
					a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1, c.d_tmt_golongan
					FROM personal_pokok_tm a
					left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip)
					WHERE upper(a.n_peg) like upper('%$nama%') AND
					a.i_peg_nip like upper('%$nip%')

					) x";*/
				/*$sql1="
				select count(distinct a.i_peg_nip)
				from personal_jabatan_terakhir_vm a 
				left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), personal_pokok_tm b
				where a.i_peg_nip = b.i_peg_nip AND 
				(a.c_peg_status='ADT'
				OR a.c_peg_status='TAH'
				OR a.c_peg_status='SKT'
				OR a.c_peg_status='LAN') AND 
				upper(b.n_peg) like upper('%$nama%') AND 
				a.i_peg_nip like upper('%$nip%')				
				";*/
				$sql1="
			SELECT count(distinct(a.i_peg_nip))
			FROM personal_pokok_tm a
			INNER JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan 
			from personal_jabatan_terakhir_vm where c_peg_status <> 'SKR') b 
			on (a.i_peg_nip = b.i_peg_nip)
			left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip)
			WHERE upper(a.n_peg) like upper('%$nama%') AND
			a.i_peg_nip like upper('%$nip%') AND
			(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)				
				";

				/*$sql2 = "SELECT a.i_peg_nip, a.n_peg, c.c_peg_golongan, a.n_peg_gelardepan, a.n_peg_gelarblkg,
						a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
						a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
						a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
						a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1, c.d_tmt_golongan
						FROM personal_pokok_tm a
						left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip)
						WHERE upper(a.n_peg) like upper('%$nama%') AND
						a.i_peg_nip like upper('%$nip%')
						AND (a.c_peg_status='ADT'
						OR a.c_peg_status='TAH'
						OR a.c_peg_status='SKT'
						OR a.c_peg_status='LAN')						
						ORDER BY c.c_peg_golongan desc, c.d_tmt_golongan desc  limit $xLimit offset  $xOffset";	*/
				/*$sql2="
				select distinct a.i_peg_nip, b.n_peg, 
				c.c_peg_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, 
				b.c_peg_jeniskelamin,  b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir,
				b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, b.a_peg_kecamatan,
				b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah,
				b.i_peg_telponhp, b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
				from personal_jabatan_terakhir_vm a 
				left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), 
				personal_pokok_tm b
				where a.i_peg_nip = b.i_peg_nip AND 
				(a.c_peg_status='ADT'
				OR a.c_peg_status='TAH'
				OR a.c_peg_status='SKT'
				OR a.c_peg_status='LAN') AND 
				upper(b.n_peg) like upper('%$nama%') AND 
				a.i_peg_nip like upper('%$nip%')
				ORDER BY c.c_peg_golongan desc, c.d_tmt_golongan desc 
				limit $xLimit offset  $xOffset";*/
				$sql2="
				SELECT a.i_peg_nip, a.n_peg,c.c_peg_golongan,c.d_tmt_golongan, a.n_peg_gelardepan, 
				a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
				a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
				a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
				a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
				a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
				FROM personal_pokok_tm a
				INNER JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan 
				from personal_jabatan_terakhir_vm where c_peg_status <> 'SKR') b 
				on (a.i_peg_nip = b.i_peg_nip)
				left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip)
				WHERE upper(a.n_peg) like upper('%$nama%') AND
				a.i_peg_nip like upper('%$nip%') AND
				(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)
			    ORDER BY c.c_peg_golongan DESC,c.d_tmt_golongan
				limit $xLimit offset  $xOffset";
					
					//echo $sql2;

			}
			else if($stat == 'SKR')
			{
				$sql1 = "select count(*) 
						FROM
						(select distinct d.c_satminkal, a.i_peg_nip, b.n_peg, c.c_peg_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, b.c_peg_jeniskelamin, 
							b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir, b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, 
							b.a_peg_kecamatan, b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah, b.i_peg_telponhp, 
							b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
							from personal_jabatan_terakhir_vm a 
							left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip) , personal_pokok_tm b, satker_tm d
							where a.i_peg_nip = b.i_peg_nip AND 
								a.i_peg_nip = d.i_peg_nip AND
								d.c_satminkal like '%$cSatminkal%' AND
								a.c_peg_status like '%$stat%' AND 
								upper(b.n_peg) like upper('%$nama%') AND
								a.i_peg_nip like upper('%$nip%')
						)x";
				
				$sql2 = "select distinct d.c_satminkal, a.i_peg_nip, b.n_peg, c.c_peg_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, b.c_peg_jeniskelamin, 
							b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir, b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, 
							b.a_peg_kecamatan, b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah, b.i_peg_telponhp, 
							b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
							from personal_jabatan_terakhir_vm a 
							left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), personal_pokok_tm b, satker_tm d
							where a.i_peg_nip = b.i_peg_nip AND 
								a.i_peg_nip = d.i_peg_nip AND
								d.c_satminkal like '%$cSatminkal%' AND
								a.c_peg_status like '%$stat%' AND 
								upper(b.n_peg) like upper('%$nama%') AND
								a.i_peg_nip like upper('%$nip%')
							ORDER BY c.c_peg_golongan desc, c.d_tmt_golongan desc  limit $xLimit offset $xOffset";	
							
							//echo $sql2;
			}
			else
			{//echo "TESting";
				$sql1 = "select count(*) 
						FROM
						(select distinct a.i_peg_nip, b.n_peg, c.c_peg_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, 
								b.c_peg_jeniskelamin,  b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir,
								b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, b.a_peg_kecamatan,
								b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah,
								b.i_peg_telponhp, b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
							from personal_jabatan_terakhir_vm a
							left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), personal_pokok_tm b 
							where a.i_peg_nip = b.i_peg_nip AND 
								a.c_peg_status like '%$stat%' AND 
								upper(b.n_peg) like upper('%$nama%') AND 
								a.i_peg_nip like upper('%$nip%')
						)x";
				
				$sql2 = "select distinct a.i_peg_nip, b.n_peg, c.c_peg_golongan,c.d_tmt_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, 
								b.c_peg_jeniskelamin,  b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir,
								b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, b.a_peg_kecamatan,
								b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah,
								b.i_peg_telponhp, b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
							from personal_jabatan_terakhir_vm a 
							left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), personal_pokok_tm b
							where a.i_peg_nip = b.i_peg_nip AND 
								a.c_peg_status like '%$stat%' AND 
								upper(b.n_peg) like upper('%$nama%') AND 
								a.i_peg_nip like upper('%$nip%')
							ORDER BY c.c_peg_golongan desc, c.d_tmt_golongan limit $xLimit offset $xOffset";	
			}
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
						$hasilAkhir = $db->fetchOne($sql1);	
			}
			else
			{
						$result = $db->fetchAll($sql2);	
			}
			
			//echo $sql2;
			
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult."<br>";
			for ($j = 0; $j < $jmlResult; $j++) {
				$statusPegawai = $db->fetchAll("SELECT c_peg_status, n_jabatan 
												FROM personal_jabatan_terakhir_vm 
												WHERE i_peg_nip = '".$result[$j]->i_peg_nip."' AND
														c_peg_status like '%".$stat."%'");
																									
				for($x=0; $x<count($statusPegawai); $x++)
				{	
					$statPegArr[$x] = array("cPegStatus" => (string)$statusPegawai[$x]->c_peg_status,
										"nJabatan" => (string)$statusPegawai[$x]->n_jabatan);
				}
				
				$pangkat = $db->fetchOne("SELECT n_peg_pangkat 
												FROM golongan_tr 
												WHERE c_peg_golongan = '".$result[$j]->c_peg_golongan."'");
				$hasilAkhir[$j] = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,
									   "maxGol"      		=>(string)$result[$j]->c_peg_golongan,
									   "d_tmt_golongan"      		=>(string)$result[$j]->d_tmt_golongan,
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => $statPegArr,
									   "n_peg_pangkat" => $pangkat,
									   "i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp
									);
				
					
				// echo "<br>".$result[$j]->i_peg_nip;
				// var_dump($statPegArr);	
				// echo "<br>";
				unset($statPegArr);
			}	
			
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
	 public function getPL($nip, $nama, $stat,$stker){
	  $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	 $sql2 = "select distinct a.i_peg_nip, b.n_peg, c.c_peg_golongan,c.d_tmt_golongan, b.n_peg_gelardepan, b.n_peg_gelarblkg, 
								b.c_peg_jeniskelamin,  b.c_peg_statusnikah, b.d_peg_lahir, b.a_peg_lahir,
								b.a_peg_rumah, b.a_peg_rt, b.a_peg_rw, b.a_peg_kelurahan, b.a_peg_kecamatan,
								b.a_peg_kota, b.a_peg_propinsi, b.a_peg_kodepos, b.i_peg_telponrumah,
								b.i_peg_telponhp, b.c_agama, b.i_peg_email, b.i_peg_email1, c.d_tmt_golongan
							from personal_jabatan_terakhir_vm a 
							left join golongan_terakhir_vm c on (a.i_peg_nip = c.i_peg_nip), personal_pokok_tm b
							where a.i_peg_nip = b.i_peg_nip AND 
								a.c_peg_status like '%$stat%' AND 
								upper(b.n_peg) like upper('%$nama%') AND 
								a.i_peg_nip like upper('%$nip%')
							ORDER BY c.c_peg_golongan desc, c.d_tmt_golongan";
$result = $db->fetchAll($sql2);		
$jmlResult = count($result);		
for ($j = 0; $j < $jmlResult; $j++) {
				$statusPegawai = $db->fetchAll("SELECT c_peg_status, n_jabatan 
												FROM personal_jabatan_terakhir_vm 
												WHERE i_peg_nip = '".$result[$j]->i_peg_nip."' AND
														c_peg_status like '%".$stat."%'");
																									
				for($x=0; $x<count($statusPegawai); $x++)
				{	
					$statPegArr[$x] = array("cPegStatus" => (string)$statusPegawai[$x]->c_peg_status,
										"nJabatan" => (string)$statusPegawai[$x]->n_jabatan);
				}
				
				$pangkat = $db->fetchOne("SELECT n_peg_pangkat 
												FROM golongan_tr 
												WHERE c_peg_golongan = '".$result[$j]->c_peg_golongan."'");
				$hasilAkhir[$j] = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,
									   "maxGol"      		=>(string)$result[$j]->c_peg_golongan,
									   "d_tmt_golongan"      		=>(string)$result[$j]->d_tmt_golongan,
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => $statPegArr,
									   "n_peg_pangkat" => $pangkat,
									   "i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp
									);
									};	
										
										
										return $hasilAkhir;	
							
	 }
	 
	public function getPersonalList($nip, $nama, $stat, $pageNumber, $itemPerPage) {
	//echo "masuk getPegawaiListByUser"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	
	   if (($nip == 'undefined') || ($nip == null)) {
			$nip ='';
	   }
	   if (($nama == 'undefined') || ($nama == null)) {
			$nama ='';
	   }
	   
	    if (($stat == 'undefined') || ($stat == null)|| ($stat == '-')) {
			 $stat ='';
	   }
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			if($stat == '')
			{
				$sql1 = "select count(*) 
					FROM
					(SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
					a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
					a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
					a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
					a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
					FROM personal_pokok_tm a
					LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
					WHERE upper(a.n_peg) like upper('%$nama%') AND
					a.i_peg_nip like upper('%$nip%') AND
					(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)
					) x";		

				$sql2 = "SELECT a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
					a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
					a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
					a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
					a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
					FROM personal_pokok_tm a
					LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
					WHERE upper(a.n_peg) like upper('%$nama%') AND
					a.i_peg_nip like upper('%$nip%') AND
					(b.c_peg_status like '%$stat%' OR b.c_peg_status IS NULL)
					ORDER BY b.i_peg_nip limit $xLimit offset $xOffset";	
			}
			else
			{
				$sql1 = "select count(*) 
					FROM
					(SELECT distinct a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
					a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
					a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
					a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
					a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
					FROM personal_pokok_tm a
					LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
					WHERE upper(a.n_peg) like upper('%$nama%') AND
					a.i_peg_nip like upper('%$nip%') AND
					(b.c_peg_status like '%$stat%')
					) x";		

				$sql2 = "SELECT distinct a.i_peg_nip, a.n_peg, a.n_peg_gelardepan, a.n_peg_gelarblkg, b.c_peg_status,b.n_jabatan,
					a.c_peg_jeniskelamin,  a.c_peg_statusnikah, a.d_peg_lahir, a.a_peg_lahir,
					a.a_peg_rumah, a.a_peg_rt, a.a_peg_rw, a.a_peg_kelurahan, a.a_peg_kecamatan,
					a.a_peg_kota, a.a_peg_propinsi, a.a_peg_kodepos, a.i_peg_telponrumah,
					a.i_peg_telponhp, a.c_agama, a.i_peg_email, a.i_peg_email1
					FROM personal_pokok_tm a
					LEFT JOIN (SELECT i_peg_nip, c_peg_status, n_jabatan from personal_jabatan_terakhir_vm ) b on (a.i_peg_nip = b.i_peg_nip)
					WHERE upper(a.n_peg) like upper('%$nama%') AND
					a.i_peg_nip like upper('%$nip%') AND
					(b.c_peg_status like '%$stat%')
					ORDER BY b.i_peg_nip limit $xLimit offset $xOffset";	
			}			

			if(($pageNumber==0) && ($itemPerPage==0))
			{	
						$hasilAkhir = $db->fetchOne($sql1);	
			}
			else
			{
						$result = $db->fetchAll($sql2);	
			}
			
			//echo $sql2;
			
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult."<br>";
			for ($j = 0; $j < $jmlResult; $j++) {
				$statusPegawai = $db->FetchCol('SELECT n_peg_status FROM personal_status_tr WHERE c_peg_status = ?',$result[$j]->c_peg_status);
											
				$hasilAkhir[$j] = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => (string)$result[$j]->c_peg_status,
										"statusPeg"  		=>(string)$result[$j]->n_jabatan,
										"i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp
									);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	// fungsi untuk menampilkan list status personal
	public function getPersonalStatusList() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$result = $db->fetchAll("SELECT *
									FROM personal_status_tr 
									ORDER BY c_status_level");	
									
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_peg_status"  	=>(string)$result[$j]->c_peg_status,
									   "n_peg_status"      		=>(string)$result[$j]->n_peg_status,
									   "c_status_level"      		=>(string)$result[$j]->c_status_level
									);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getListKabupatenPropinsi($data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			$n_kabupaten = strtoupper($data['n_kabupaten']);
			$n_propinsi = strtoupper($data['n_propinsi']);
			
			if($n_kabupaten)
			{
				$where = "upper(n_kabupaten) like '%$n_kabupaten%'";
			}
			else
			{
				if($n_propinsi)
				{
					$where = "upper(b.n_propinsi) like '%$n_propinsi%'";
				}
				else
				{
					$where = "upper(a.n_kabupaten) like '%$n_kabupaten%' AND upper(b.n_propinsi) like '%$n_propinsi%'";
				}				
			}
			
			$result = $db->fetchAll("SELECT a.c_kabupaten, a.n_kabupaten, a.c_propinsi, b.n_propinsi
									FROM kabupaten_propinsi_tr a, propinsi_tr b
									WHERE a.c_propinsi = b.c_propinsi AND
										($where)");	
												
// echo "SELECT a.c_kabupaten, a.n_kabupaten, a.c_propinsi, b.n_propinsi
									// FROM kabupaten_propinsi_tr a, propinsi_tr b
									// WHERE a.c_propinsi = b.c_propinsi AND
										// ($where)";												
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_kabupaten"  	=>(string)$result[$j]->c_kabupaten,
									   "n_kabupaten"    =>trim((string)$result[$j]->n_kabupaten),
									   "c_propinsi"    =>(string)$result[$j]->c_propinsi,
									   "n_propinsi"    =>trim((string)$result[$j]->n_propinsi)
									);
			}	
			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	 
	//fungsi untuk insert data pokok ke personal_pokok
	//==========================================
	public function insertPersonalDataPokok(array $data) {
	//echo "masuk insert pegawai"."<br>";	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$db->beginTransaction();
			$pegawai_mod_prm = array("i_peg_nip"  				=>$data['nip'],
		                               "n_peg"  				=>$data['namaPegawai'],
		                               "n_peg_gelardepan"  		=>$data['gelarDpn'],
		                               "n_peg_gelarblkg"  		=>$data['gelarBlk'],
								       "c_peg_status"   		=>$data['statusPeg'],
									   "c_peg_jeniskelamin"   	=>$data['jenisKelamin'],
								       "c_peg_statusnikah"   	=>$data['stsNikah'],
								       "d_peg_lahir"   			=>$data['tanggalLahir'],
								       "a_peg_lahir"   			=>$data['tmpLahir'],
									   "a_peg_rumah"   			=>$data['alamat'],
									   "a_peg_rt"   			=>$data['rt'],
								       "a_peg_rw"   			=>$data['rw'],
								       "a_peg_kelurahan"   		=>$data['kelurahan'],
								       "a_peg_kecamatan"   		=>$data['kecamatan'],
								       "a_peg_kota"   			=>$data['kabupaten'],
								       "a_peg_propinsi"   		=>$data['propinsi'],
								       "a_peg_kodepos"   		=>$data['kodePos'],
								       "i_peg_telponrumah"   	=>$data['teleponRumah'],
								       "i_peg_telponhp"   		=>$data['tlpGenggam'],
								       "c_agama"   				=>$data['agama'],
								       "i_peg_email"   			=>$data['email'],
								       "i_peg_email1"   		=>$data['email2'],
								       "i_entry"       			=>$data['userid'],
									   "d_entry"       			=>date("Y-m-d"));		
			$db->insert('personal_pokok_tm',$pegawai_mod_prm);
			$db->commit();
			
			return 'sukses';
	   } catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	// fungsi untuk menampilkan data Ref Propinsi 
	 
	public function getPropinsiListAll() {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT c_propinsi, n_propinsi FROM propinsi_tr order by cast(c_propinsi as signed integer)  asc');
						 
		 
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function getKelurahan() {
	//echo "cProp = $cPropinsi";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('select * from kelurahan_kecamatan_tr');
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 //echo "jmlResult= $jmlResult";
		   //echo "PANJANG ".strlen($result[$j]->c_pgm);
		   
           $data[$j] = array("n_kelurahan"  =>(string)$result[$j]->n_kelurahan);
		 }		
//var_dump($result);		 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'tidak ada data';
	   }
	}   	
	
	public function getKecamatan($kelurahan) {
	//echo "cProp = $cPropinsi";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll("select * from kelurahan_kecamatan_tr where n_kelurahan like '$kelurahan%'");
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
           $data[$j] = array("n_kecamatan"  =>(string)$result[$j]->n_kecamatan,
							"n_kota"  =>(string)$result[$j]->n_kota,
							"n_propinsi"  =>(string)$result[$j]->n_propinsi,
							"i_pos"  =>(string)$result[$j]->i_pos);
		 }		
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'tidak ada data';
	   }
	}   	
	 
	public function getKabupatenByProp($cPropinsi) {
	//echo "cProp = $cPropinsi";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
	     $result = $db->fetchAll('select * from kabupaten_propinsi_tr where c_propinsi = ? order by n_kabupaten', $cPropinsi);
         $jmlResult = count($result);
		 for ($j = 0; $j < $jmlResult; $j++) {
		 //echo "jmlResult= $jmlResult";
		   //echo "PANJANG ".strlen($result[$j]->c_pgm);
		   $nPropinsi = $db->fetchCol('SELECT n_propinsi FROM propinsi_tr WHERE c_propinsi = ?',$result[$j]->c_propinsi);
           $data[$j] = array("c_kabupaten"  =>(string)$result[$j]->c_kabupaten,
	                         "n_kabupaten"  =>(string)$result[$j]->n_kabupaten);
		 }		
//var_dump($result);		 
	     return $data;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'ga ono data rek <br>';
	   }
	}   
	 
	 
	// fungsi untuk merubah data Pegawai ke tabel 'personal_pokok_tm'
	 
	public function updatePersonalDataPokok(array $data) {
	//echo "masuk updatepegawai";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	
		//$user = '930554';	   
	   try {
	     $db->beginTransaction();
			$pegawai_mod_prm = array("i_peg_nip"  		=>$data['nip'],
									"n_peg"  		=>$data['namaPegawai'],
									"n_peg_gelardepan"  		=>$data['gelarDpn'],
									"n_peg_gelarblkg"  		=>$data['gelarBlk'],
									//"c_peg_status"   		=>$data['statusPeg'],
									"c_peg_jeniskelamin"   	=>$data['jenisKelamin'],
									"c_peg_statusnikah"   	=>$data['stsNikah'],
									"d_peg_lahir"   			=>$data['tanggalLahir'],
									"a_peg_lahir"   			=>$data['tmpLahir'],
									"a_peg_rumah"   			=>$data['alamat'],
									"a_peg_rt"   			=>$data['rt'],
									"a_peg_rw"   			=>$data['rw'],
									"a_peg_kelurahan"   		=>$data['kelurahan'],
									"a_peg_kecamatan"   		=>$data['kecamatan'],
									"a_peg_kota"   			=>$data['kabupaten'],
									"a_peg_propinsi"   		=>$data['propinsi'],
									"a_peg_kodepos"   		=>$data['kodePos'],
									"i_peg_telponrumah"   	=>$data['teleponRumah'],
									"i_peg_telponhp"   		=>$data['tlpGenggam'],
									"c_agama"   				=>$data['agama'],
									"i_peg_email"   			=>$data['email'],
									"i_peg_email1"   		=>$data['email2'],
									"i_entry"       			=>$data['userid'],
									"d_entry"       			=>date("Y-m-d"));		
		
  		 $where[] = "i_peg_nip = '".$data['nipH']."'";
		 
		 //var_dump($pegawai_mod_prm);
	     $db->update('personal_pokok_tm',$pegawai_mod_prm, $where);
		 $db->commit();		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }	   
	} 
	 
	public function deletePersonalDataPokok(array $data) {
	//echo "masuk updatepegawai";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');	
		//$user = '930554';	   
	   try {
	     $db->beginTransaction();	
		
  		 $where[] = "i_peg_nip = '".$data['nip']."'";
	     $db->delete('personal_pokok_tm', $where);
		 $db->commit();		 
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }	   
	}
	// fungsi untuk mengambil data detail personal
	public function getPersonalDetail($nip) {
	//echo "masuk getPegawaiListByUser"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 

			$result = $db->fetchAll("SELECT *
									FROM personal_pokok_tm 
									WHERE i_peg_nip = '$nip'");	
				
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult."<br>";
			for ($j = 0; $j < $jmlResult; $j++) {
				$cTmpLahir = $result[$j]->a_peg_lahir;
				
				/* $hasilStatusJabatan = $db->fetchAll("SELECT a.c_peg_status, b.n_peg_status, n_jabatan
									FROM personal_jabatan_terakhir_vm a,  personal_status_tr b
									WHERE a.c_peg_status = b.c_peg_status AND
										a.i_peg_nip = '$nip'"); */
									
				// $c_peg_status = $hasilStatusJabatan[0]->c_peg_status;
				// $n_peg_status = $hasilStatusJabatan[0]->n_peg_status;				
				// $n_jabatan = $hasilStatusJabatan[0]->n_jabatan;				
				
				$maxGolongan = $db->fetchOne("SELECT max(c_peg_golongan) FROM personal_golongan_tm 
												WHERE i_peg_nip = '$nip'");

				$n_peg_status = $db->fetchOne("SELECT n_peg_status FROM personal_status_tr WHERE c_peg_status = '".$result[$j]->c_peg_status."'");								
				
				
				// $maxStatusJabatDate = $db->fetchOne("select max(d_jabatan_mulai) from personal_jabatan_tm where i_peg_nip = '$nip'");
				// $maxStatus = $db->fetchOne("select c_peg_status from personal_jabatan_tm where i_peg_nip = '$nip' AND d_jabatan_mulai = '$maxStatusJabatDate'");
				
				// $maxNjabatan = $db->fetchOne("select n_jabatan 
												// from personal_jabatan_tm 
												// where i_peg_nip = '$nip' AND 
												// d_jabatan_mulai = '$maxStatusJabatDate' AND
												// c_peg_status = '$maxStatus'");
				
				$statusPegawai = $db->fetchAll("SELECT c_peg_status, n_jabatan 
												FROM personal_jabatan_terakhir_vm 
												WHERE i_peg_nip = '$nip'");
				if(count($statusPegawai) > 0)
				{
					for($x=0; $x<count($statusPegawai); $x++)
					{	
						$statPegArr[$x] = array("cPegStatus" => (string)$statusPegawai[$x]->c_peg_status,
											"nJabatan" => (string)$statusPegawai[$x]->n_jabatan);
					}
				}
				else
				{
					$statPegArr = array();
				}
				
				$hasilAkhir = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => (string)$result[$j]->c_peg_status,
									   "n_peg_status" => $n_peg_status,
									   "n_jabatan" => $statPegArr,
									   "maxGolongan" => $maxGolongan,
									   "c_peg_jeniskelamin" => (string)$result[$j]->c_peg_jeniskelamin,
									   "c_peg_statusnikah" => (string)$result[$j]->c_peg_statusnikah,
									   "d_peg_lahir" => (string)$result[$j]->d_peg_lahir,
									   "a_peg_lahir" => (string)$result[$j]->a_peg_lahir,
									   "a_peg_rumah" => (string)$result[$j]->a_peg_rumah,
									   "a_peg_rt" => (string)$result[$j]->a_peg_rt,
									   "a_peg_rw" => (string)$result[$j]->a_peg_rw,
									   "a_peg_kelurahan" => (string)$result[$j]->a_peg_kelurahan,
									   "a_peg_kecamatan" => (string)$result[$j]->a_peg_kecamatan,
									   "a_peg_kota" => (string)$result[$j]->a_peg_kota,
									   "a_peg_propinsi" => (string)$result[$j]->a_peg_propinsi,
									   "a_peg_kodepos" => (string)$result[$j]->a_peg_kodepos,
									   "i_peg_telponrumah" => (string)$result[$j]->i_peg_telponrumah,
									   "i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp,
									   "c_agama" => (string)$result[$j]->c_agama,
									   "i_peg_email" => (string)$result[$j]->i_peg_email,
									   "i_peg_email1" => (string)$result[$j]->i_peg_email1
									);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
public function getPendidikanFPDF($nip) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		// echo "(nip) ".$nip;
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT a.*,b.n_pend
				  FROM personal_pendidikan_tm a				  
				  INNER JOIN pendidikan_tr b on b.c_pend=a.c_pend
				  WHERE a.i_peg_nip = '$nip'
				  ORDER BY CAST(a.c_pend AS UNSIGNED), a.d_ijasah");	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								   "c_pend"  		=>(string)$result[$j]->c_pend,
	                               "n_pend"     		=>(string)$result[$j]->n_pend,
							       "n_pend_lembaga"  		=>(string)$result[$j]->n_pend_lembaga,
								   "n_pend_jurusan"  		=>(string)$result[$j]->n_pend_jurusan,
								   "n_pend_kota"  			=>(string)$result[$j]->n_pend_kota,
								   "i_ijasah"  			=>(string)$result[$j]->i_ijasah,
								   "d_ijasah"  		=>(string)$result[$j]->d_ijasah
								   );
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}		
	public function getRiwayatPangkatGolonganFPDF($nip) {
	//echo "masuk getPegawaiListByUser"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 

			$result = $db->fetchAll("SELECT a.*,max( b.c_peg_golongan) as c_peg_golongan, c.n_peg_pangkat,
									b.i_sk,b.d_sk,b.d_tmt_golongan,b.n_pejabat_sk,b.c_pns_status
									FROM personal_pokok_tm a
									INNER JOIN personal_golongan_tm b ON b.i_peg_nip = a.i_peg_nip
									INNER JOIN golongan_tr c ON c.c_peg_golongan = b.c_peg_golongan
									WHERE a.i_peg_nip = '$nip'
									GROUP BY b.c_peg_golongan, c.n_peg_pangkat,b.i_sk,b.d_sk,b.d_tmt_golongan
									,b.n_pejabat_sk
									ORDER BY b.d_tmt_golongan ASC");	
				
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult."<br>";
			for ($j = 0; $j < $jmlResult; $j++) {

				$hasilAkhir[$j] = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,									   
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => (string)$result[$j]->c_peg_status,
									   "n_peg_pangkat" => (string)$result[$j]->n_peg_pangkat,
									   "d_tmt_golongan" => (string)$result[$j]->d_tmt_golongan,
									   "i_sk" => (string)$result[$j]->i_sk,
									   "d_sk" => (string)$result[$j]->d_sk,
									   "n_peg_status" => $n_peg_status,
									   "c_pns_status" => (string)$result[$j]->c_pns_status,
									   "n_jabatan" => $statPegArr,
									   "maxGolongan" => (string)$result[$j]->c_peg_golongan,
									   ",n_pejabat_sk" => (string)$result[$j]->n_pejabat_sk,
									   "c_peg_jeniskelamin" => (string)$result[$j]->c_peg_jeniskelamin,
									   "c_peg_statusnikah" => (string)$result[$j]->c_peg_statusnikah,
									   "d_peg_lahir" => (string)$result[$j]->d_peg_lahir,
									   "a_peg_lahir" => (string)$result[$j]->a_peg_lahir,
									   "a_peg_rumah" => (string)$result[$j]->a_peg_rumah,
									   "a_peg_rt" => (string)$result[$j]->a_peg_rt,
									   "a_peg_rw" => (string)$result[$j]->a_peg_rw,
									   "a_peg_kelurahan" => (string)$result[$j]->a_peg_kelurahan,
									   "a_peg_kecamatan" => (string)$result[$j]->a_peg_kecamatan,
									   "a_peg_kota" => (string)$result[$j]->a_peg_kota,
									   "a_peg_propinsi" => (string)$result[$j]->a_peg_propinsi,
									   "a_peg_kodepos" => (string)$result[$j]->a_peg_kodepos,
									   "i_peg_telponrumah" => (string)$result[$j]->i_peg_telponrumah,
									   "i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp,
									   "c_agama" => (string)$result[$j]->c_agama,
									   "i_peg_email" => (string)$result[$j]->i_peg_email,
									   "i_peg_email1" => (string)$result[$j]->i_peg_email1
									);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	public function getJabatListByNipFPDF($nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_jabatan_tm 
								WHERE i_peg_nip = '$nip'
								ORDER BY d_jabatan_mulai asc");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {			
			$hasilAkhir[$j] = array("i_peg_nip" =>(string)$result[$j]->i_peg_nip,
									"c_peg_status" =>(string)$result[$j]->c_peg_status,
									"c_peg_jabatan" =>(string)$result[$j]->c_peg_jabatan,
									"n_jabatan" => (string)$result[$j]->n_jabatan,
									"n_pejabat_sk" => (string)$result[$j]->n_pejabat_sk,
									"n_instansi" => (string)$result[$j]->n_instansi,
									"a_lokasi" => (string)$result[$j]->a_lokasi,
									"e_jabatan" => (string)$result[$j]->e_jabatan,
									"c_eselon" => (string)$result[$j]->c_eselon,
									"d_eselon" => (string)$result[$j]->d_eselon,
									"n_bidang" =>(string)$result[$j]->n_bidang,
									"c_satminkal" =>(string)$result[$j]->c_satminkal,
									"c_satker" =>(string)$result[$j]->c_satker,
									"d_jabatan_mulai" =>(string)$result[$j]->d_jabatan_mulai,
									"d_jabatan_akhir" =>(string)$result[$j]->d_jabatan_akhir,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_sk" =>(string)$result[$j]->d_sk);								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	public function getPersonalDetailFPDF($nip) {
	//echo "masuk getPegawaiListByUser"."<br>";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');

	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 

			$result = $db->fetchAll("SELECT a.*,max( b.c_peg_golongan ), c.n_peg_pangkat,
									b.i_sk,b.d_sk,b.d_tmt_golongan
									FROM personal_pokok_tm a
									LEFT JOIN personal_golongan_tm b ON b.i_peg_nip = a.i_peg_nip
									LEFT JOIN golongan_tr c ON c.c_peg_golongan = b.c_peg_golongan
									WHERE a.i_peg_nip = '$nip'
									GROUP BY b.c_peg_golongan, c.n_peg_pangkat,b.i_sk,b.d_sk,b.d_tmt_golongan");	
				
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult."<br>";
			for ($j = 0; $j < $jmlResult; $j++) {
				$cTmpLahir = $result[$j]->a_peg_lahir;
				
				/* $hasilStatusJabatan = $db->fetchAll("SELECT a.c_peg_status, b.n_peg_status, n_jabatan
									FROM personal_jabatan_terakhir_vm a,  personal_status_tr b
									WHERE a.c_peg_status = b.c_peg_status AND
										a.i_peg_nip = '$nip'"); */
									
				// $c_peg_status = $hasilStatusJabatan[0]->c_peg_status;
				// $n_peg_status = $hasilStatusJabatan[0]->n_peg_status;				
				// $n_jabatan = $hasilStatusJabatan[0]->n_jabatan;				
				
				$maxGolongan = $db->fetchOne("SELECT max(c_peg_golongan) FROM personal_golongan_tm 
												WHERE i_peg_nip = '$nip'");

				$n_peg_status = $db->fetchOne("SELECT n_peg_status FROM personal_status_tr WHERE c_peg_status = '".$result[$j]->c_peg_status."'");								
				
				
				// $maxStatusJabatDate = $db->fetchOne("select max(d_jabatan_mulai) from personal_jabatan_tm where i_peg_nip = '$nip'");
				// $maxStatus = $db->fetchOne("select c_peg_status from personal_jabatan_tm where i_peg_nip = '$nip' AND d_jabatan_mulai = '$maxStatusJabatDate'");
				
				// $maxNjabatan = $db->fetchOne("select n_jabatan 
												// from personal_jabatan_tm 
												// where i_peg_nip = '$nip' AND 
												// d_jabatan_mulai = '$maxStatusJabatDate' AND
												// c_peg_status = '$maxStatus'");
				
				$statusPegawai = $db->fetchAll("SELECT c_peg_status, n_jabatan 
												FROM personal_jabatan_terakhir_vm 
												WHERE i_peg_nip = '$nip'");
				if(count($statusPegawai) > 0)
				{
					for($x=0; $x<count($statusPegawai); $x++)
					{	
						$statPegArr[$x] = array("cPegStatus" => (string)$statusPegawai[$x]->c_peg_status,
											"nJabatan" => (string)$statusPegawai[$x]->n_jabatan);
					}
				}
				else
				{
					$statPegArr = array();
				}
				
				$hasilAkhir = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
									   "n_peg"      		=>(string)$result[$j]->n_peg,									   
									   "n_peg_gelardepan"      		=>(string)$result[$j]->n_peg_gelardepan,
									   "n_peg_gelarblkg"      		=>(string)$result[$j]->n_peg_gelarblkg,
									   "c_peg_status" => (string)$result[$j]->c_peg_status,
									   "n_peg_pangkat" => (string)$result[$j]->n_peg_pangkat,
									   "d_tmt_golongan" => (string)$result[$j]->d_tmt_golongan,
									   "i_sk" => (string)$result[$j]->i_sk,
									   "d_sk" => (string)$result[$j]->d_sk,
									   "n_peg_status" => $n_peg_status,
									   "n_jabatan" => $statPegArr,
									   "maxGolongan" => $maxGolongan,
									   "c_peg_jeniskelamin" => (string)$result[$j]->c_peg_jeniskelamin,
									   "c_peg_statusnikah" => (string)$result[$j]->c_peg_statusnikah,
									   "d_peg_lahir" => (string)$result[$j]->d_peg_lahir,
									   "a_peg_lahir" => (string)$result[$j]->a_peg_lahir,
									   "a_peg_rumah" => (string)$result[$j]->a_peg_rumah,
									   "a_peg_rt" => (string)$result[$j]->a_peg_rt,
									   "a_peg_rw" => (string)$result[$j]->a_peg_rw,
									   "a_peg_kelurahan" => (string)$result[$j]->a_peg_kelurahan,
									   "a_peg_kecamatan" => (string)$result[$j]->a_peg_kecamatan,
									   "a_peg_kota" => (string)$result[$j]->a_peg_kota,
									   "a_peg_propinsi" => (string)$result[$j]->a_peg_propinsi,
									   "a_peg_kodepos" => (string)$result[$j]->a_peg_kodepos,
									   "i_peg_telponrumah" => (string)$result[$j]->i_peg_telponrumah,
									   "i_peg_telponhp" => (string)$result[$j]->i_peg_telponhp,
									   "c_agama" => (string)$result[$j]->c_agama,
									   "i_peg_email" => (string)$result[$j]->i_peg_email,
									   "i_peg_email1" => (string)$result[$j]->i_peg_email1
									);
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	 
	/**
	 * fungsi untuk menampilkan data Ref Pendidikan 
	 */
	public function getPendidikanListAll() {
	//echo "masuk getPendidikanListAll";
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT c_pend, n_pend
				  FROM pendidikan_tr order by CAST(c_pend AS UNSIGNED)");				
		 
		// $result = $db->fetchAll("SELECT c_pend, n_pend
				  // FROM e_sdm_pend_0_tr order by to_number(c_pend,'999')"); 
         $jmlResult = count($result);
	     return $result;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function insertPendidikan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   //echo "tglIjazah =".$data['tglIjazah'];
	   //$user='930554';
	   try {
	     $db->beginTransaction();
	     $pendidikan_mod_prm = array("i_peg_nip"  			=>$data['nip'],
	                               "c_pend"  				=>$data['kdjenjang'],
								   "n_pend_jurusan"         =>$data['jurusan'],							   
	                               "n_pend_lembaga"  		=>$data['pendidikan'],
								   "n_pend_kota"         	=>$data['tempat'],
							       "i_ijasah"   		=>$data['noIjazah'],
							       "d_ijasah"   		=>$data['tglIjazah'],
								   "i_entry"       			=>$data['user'],
								   "d_entry"       			=>date("Y-m-d"));

         $db->insert('personal_pendidikan_tm',$pendidikan_mod_prm);
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         $errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}	

	public function getPendidikan($nip) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		// echo "(nip) ".$nip;
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT * FROM personal_pendidikan_tm				  
				  WHERE i_peg_nip = '$nip'
				  ORDER BY CAST(c_pend AS UNSIGNED), d_ijasah");	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$namaPendidikan = $db->fetchCol('SELECT n_pend FROM pendidikan_tr WHERE c_pend = ?',$result[$j]->c_pend);
			
			$hasilAkhir[$j] = array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								   "c_pend"  		=>(string)$result[$j]->c_pend,
	                               "n_pend"     		=>$namaPendidikan[0],
							       "n_pend_lembaga"  		=>(string)$result[$j]->n_pend_lembaga,
								   "n_pend_jurusan"  		=>(string)$result[$j]->n_pend_jurusan,
								   "n_pend_kota"  			=>(string)$result[$j]->n_pend_kota,
								   "i_ijasah"  			=>(string)$result[$j]->i_ijasah,
								   "d_ijasah"  		=>(string)$result[$j]->d_ijasah
								   );
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function getPT(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		// echo "(nip) ".$nip;
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$nama = strtoupper($data['nama']);
		$kota = strtoupper($data['kota']);
		
		if($nama)
		{
			if($kota)
			{
				$where = "where UPPER(n_perguruan_tinggi) like '%$nama%' and UPPER(a_kota) like '%$kota%'";
			}
			else
			{
				$where = "where UPPER(n_perguruan_tinggi) like '%$nama%'";
			}
		}
		else
		{
			if($kota)
			{
				$where = "where UPPER(a_kota) like '%$kota%'";
			}
			else
			{
				$where = "";
			}
		}
		$result = $db->fetchAll("SELECT * FROM perguruan_tinggi_tr $where ");	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("c_perguruan_tinggi"  		=>(string)$result[$j]->c_perguruan_tinggi,
								   "n_perguruan_tinggi"  		=>(string)$result[$j]->n_perguruan_tinggi,
	                               "a_kota"  		=>(string)$result[$j]->a_kota
								   );
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	 
	public function getPendidikanByNipJenjang($nip,$kdjenjang, $lembaga, $jurusan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   //echo "nip== ".$nip;
	   //echo "kdjenjang== ".$kdjenjang;
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 $where[] = $nip;
		 $where[] = $kdjenjang;
		 $where[] = $lembaga;
		 $where[] = $jurusan;
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT * FROM personal_pendidikan_tm				  
				  WHERE i_peg_nip = ? and c_pend = ? and n_pend_lembaga = ? and n_pend_jurusan = ?",$where);	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$namaPendidikan = $db->fetchCol('SELECT n_pend FROM pendidikan_tr WHERE c_pend = ?',$result[$j]->c_pend);
			$hasilAkhir = array("i_peg_nip"  			=>(string)$result[$j]->i_peg_nip,
								   "c_pend"		=>$result[$j]->c_pend,
	                               "n_pend"     		=>$namaPendidikan[0],
							       "n_pend_lembaga"  		=>(string)$result[$j]->n_pend_lembaga,
								   "n_pend_jurusan"  		=>(string)$result[$j]->n_pend_jurusan,
								   "n_pend_kota"  		=>(string)$result[$j]->n_pend_kota,
								   "i_ijasah"  			=>(string)$result[$j]->i_ijasah,
								   "d_ijasah"  		=>(string)$result[$j]->d_ijasah
								   );
								    
		 }					 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	} 

	public function updatePendidikan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $pendidikan_mod_prm = array("c_pend"  		=>$data['kdjenjang'],
	                               "n_pend_lembaga"  		=>$data['pendidikan'],
								   "n_pend_jurusan"         =>$data['jurusan'],
								   "n_pend_kota"	=> $data['tempat'],
							       "i_ijasah"   		=>$data['noIjazah'],
							       "d_ijasah"   		=>$data['tglIjazah'],
								   "i_entry"       			=>$data['user'],
								   "d_entry"       			=>date("Y-m-d"));
		 $where[] = "i_peg_nip = '".$data['nip']."'";
	     $where[] = "c_pend = '".$data['kdjenjangH']."'";
		 $where[] = "n_pend_lembaga = '".$data['pendidikanH']."'";
		 $where[] = "n_pend_jurusan = '".$data['jurusanH']."'";
		 
	     $db->update('personal_pendidikan_tm',$pendidikan_mod_prm, $where);
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	/**
	 * fungsi untuk menghapus data Pendidikan Pegawai ke tabel 'e_sdm_pend_0_tm'
	 */
	public function deletePendidikan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     //echo "TEST CONN ".$db->getConnection()."<br>";
		 
		 $where[] = "i_peg_nip = '".$data['nip']."'";
		 $where[] = "c_pend = '".$data['kdjenjang']."'";
		 $where[] = "n_pend_lembaga = '".$data['lembaga']."'";
		 $where[] = "n_pend_jurusan = '".$data['jurusan']."'";
		 
	     $db->delete('personal_pendidikan_tm', $where);
		 $db->commit();
		 unset($where);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	 
//==============================================PELATIHAN================================================
	public function getPelatihan($nip) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("
					SELECT a.i_peg_nip, a.id_pelatihan, 
					a.n_pend_latih, a.c_peg_latih, a.i_peny_diklat, 
					a.d_pend_tahunlatih, a.v_jamlat, a.n_satuan_jamlat, a.i_sertifikat, 
					a.d_sertifikat,a.a_pend_tempatlatih, a.e_keterangan, 
					b.n_peny_diklat, b.a_alamat, b.c_kabupaten
					FROM personal_diklat_tm a
					left join (select i_peny_diklat, n_peny_diklat, 
					a_alamat, c_kabupaten
					from penyelenggara_diklat_tr )b on (b.i_peny_diklat =  a.i_peny_diklat)
					WHERE a.i_peg_nip = '$nip'
					ORDER BY a.d_pend_tahunlatih ASC");	

		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$n_kabupaten = $db->fetchOne("SELECT n_kabupaten from kabupaten_propinsi_tr where c_kabupaten = '".$result[$j]->c_kabupaten."'");
			$hasilAkhir[$j] = array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								   "id_pelatihan"  		=>(string)$result[$j]->id_pelatihan,
								   "n_pend_latih"  		=>(string)$result[$j]->n_pend_latih,
								   "a_pend_tempatlatih"  		=>(string)$result[$j]->a_pend_tempatlatih,
								   "i_peny_diklat"  		=>(string)$result[$j]->i_peny_diklat,
								   "d_pend_tahunlatih"  		=>(string)$result[$j]->d_pend_tahunlatih,
								   "v_jamlat"  		=>(string)$result[$j]->v_jamlat,
								   "n_satuan_jamlat"  		=>(string)$result[$j]->n_satuan_jamlat,
							       "i_sertifikat"  			=>(string)$result[$j]->i_sertifikat,
								   "d_sertifikat"  			=>(string)$result[$j]->d_sertifikat,
								   "e_keterangan"  			=>(string)$result[$j]->e_keterangan,
								   "c_peg_latih"  			=>(string)$result[$j]->c_peg_latih,
								   "c_teknis"  			=>(string)$result[$j]->c_teknis,
								   "n_peny_diklat"			=>(string)$result[$j]->n_peny_diklat,
								   "a_alamat"		=>(string)$result[$j]->a_alamat,
								   "c_kabupaten"		=>(string)$result[$j]->c_kabupaten,
								   "n_kabupaten"  		=>$n_kabupaten);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	 
	
	public function listNamaPelatihan($jenisLatih) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT * FROM pelatihan_tr				  
				  WHERE c_pelatihan = '$jenisLatih' order by id_pelatihan");	
		
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("id_pelatihan"  		=>(string)$result[$j]->id_pelatihan,
									"n_pelatihan"  		=>(string)$result[$j]->n_pelatihan);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function listPenyelenggaraDiklat(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$nama = strtoupper($data['nama']);
		if($nama)
		{
			$sql = "SELECT a.i_peny_diklat, a.n_peny_diklat, a.a_alamat
					FROM penyelenggara_diklat_tr a
					where UPPER(n_peny_diklat) like '%$nama%'";
			
		}
		else
		{
			
			$sql = "SELECT a.i_peny_diklat, a.n_peny_diklat, a.a_alamat
					FROM penyelenggara_diklat_tr a";
		}
		$result = $db->fetchAll($sql);	
		
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("i_peny_diklat"  	=>(string)$result[$j]->i_peny_diklat,
									"n_peny_diklat"  	=>(string)$result[$j]->n_peny_diklat,
									"a_alamat"  		=>(string)$result[$j]->a_alamat);
		 }			
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function insertPelatihan(array $data) {
	//echo "masuk insert insertPelatihan";
		//$user = "930554";
		//echo "pendJenis =".$pendJenis;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	   
	     $db->beginTransaction(); 
			 
	     $pelatihan_mod_prm = array("i_peg_nip"  			=>$data['nip'],
									"id_pelatihan"			=>$data['idPelatihan'],
								   "n_pend_latih"			=>$data['nmPelatihan'],
								   "d_pend_tahunlatih"   		=>$data['tahunPelatihan'],
							       "v_jamlat"   		=>$data['lamaJamlat'],
								   "n_satuan_jamlat"   		=>$data['satuanJamlat'],
								   "a_pend_tempatlatih"   		=>$data['tempat'],
								   "e_keterangan"   		=>$data['keterangan'],
								   "c_peg_latih"   		=>$data['jenisLatih'],
								   "c_teknis"   		=>$data['kategoriLatih'],
								   "i_peny_diklat"  	=>$data['kodePenyelenggara'],
								   "i_sertifikat"   		=>$data['noSertifikat'],
							       "d_sertifikat"   		=>$data['tglSertifikat'],
								   "i_entry"       			=>$data['user'],
								   "d_entry"       			=>date("Y-m-d")
								   );

	     $db->insert('personal_diklat_tm',$pelatihan_mod_prm);
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukses");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
		 echo $e->getMessage();
         $errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	} 
	
	public function getPelatihanDetailByNip(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		$nip = $data['nip'];
		$c_peg_latih = $data['c_peg_latih'];
		$n_pend_latih = $data['n_pend_latih'];
		$i_peny_diklat = $data['i_peny_diklat'];
		$d_pend_tahunlatih = $data['d_pend_tahunlatih'];
		
		$result = $db->fetchAll("SELECT i_peg_nip, c_teknis, c_peg_latih, a.id_pelatihan, a.n_pend_latih, a.i_peny_diklat, d_pend_tahunlatih, 
										v_jamlat, n_satuan_jamlat, i_sertifikat, 
										d_sertifikat, e_keterangan, b.n_peny_diklat, b.a_alamat, b.c_kabupaten
								FROM personal_diklat_tm a
								left join (select i_peny_diklat, n_peny_diklat, a_alamat, c_kabupaten
								from penyelenggara_diklat_tr )b on (b.i_peny_diklat =  a.i_peny_diklat)
								WHERE a.i_peg_nip = '$nip' and 	
									c_peg_latih= '$c_peg_latih' and 
									n_pend_latih = '$n_pend_latih' and
									(a.i_peny_diklat = '$i_peny_diklat' or a.i_peny_diklat is null)and
									a.d_pend_tahunlatih = '$d_pend_tahunlatih'");	
									
								
				  									
         $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$n_kabupaten = $db->fetchOne("select n_kabupaten from kabupaten_propinsi_tr where c_kabupaten = '".$result[$j]->c_kabupaten."'");
			$hasilAkhir = array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								   "c_teknis"  		=>(string)$result[$j]->c_teknis,
								   "c_peg_latih"  		=>(string)$result[$j]->c_peg_latih,
								   "id_pelatihan"  		=>(string)$result[$j]->id_pelatihan,
								   "n_pend_latih"  		=>(string)$result[$j]->n_pend_latih,
								   "i_peny_diklat"  		=>(string)$result[$j]->i_peny_diklat,
								   "d_pend_tahunlatih"  		=>(string)$result[$j]->d_pend_tahunlatih,
								   "v_jamlat"  		=>(string)$result[$j]->v_jamlat,
								   "n_satuan_jamlat"  		=>(string)$result[$j]->n_satuan_jamlat,
							       "i_sertifikat"  			=>(string)$result[$j]->i_sertifikat,
								   "d_sertifikat"  			=>(string)$result[$j]->d_sertifikat,
								   "e_keterangan"  			=>(string)$result[$j]->e_keterangan,
								   "n_peny_diklat"			=>(string)$result[$j]->n_peny_diklat,
								   "a_alamat"		=>(string)$result[$j]->a_alamat,
								   "c_kabupaten"		=>(string)$result[$j]->c_kabupaten,
								   "n_kabupaten"  		=>$n_kabupaten);
			//echo $result[0]->d_pend_mulailatih;			
		 }					 
		 
	     return $hasilAkhir;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function updatePelatihan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   //$user = '930554';
	   try {
	     $db->beginTransaction();
		 	 
	     $pelatihan_mod_prm = array("id_pelatihan"			=>$data['idPelatihan'],
									"n_pend_latih"			=>$data['nmPelatihan'],
									"d_pend_tahunlatih"   		=>$data['tahunPelatihan'],
							       "v_jamlat"   		=>$data['lamaJamlat'],
								   "n_satuan_jamlat"   		=>$data['satuanJamlat'],
								   "a_pend_tempatlatih"     =>$data['tempat'],
							       "e_keterangan"   		=>$data['keterangan'],
								   "c_peg_latih"   			=>$data['jenisLatih'],
								   "c_teknis"   		=>$data['kategoriLatih'],
							       "i_peny_diklat"  	=>$data['kodePenyelenggara'],
							       "i_sertifikat"   		=>$data['noSertifikat'],
							       "d_sertifikat"   		=>$data['tglSertifikat'],
								   "i_entry"       			=>$data['user'],
								   "d_entry"       			=>date("Y-m-d"));
			//var_dump($pelatihan_mod_prm);				   
		 $where[] = "i_peg_nip = '".$data['nip']."'";
		 $where[] = "n_pend_latih = '".$data['nmPelatihanH']."'";
	     $where[] = "d_pend_tahunlatih = '".$data['tahunPelatihanH']."'";
		 
		 $db->update('personal_diklat_tm',$pelatihan_mod_prm, $where);
		 $db->commit();
		 $_hasil = array("rcNumber"=>"1",
		                 "rcDesc"  =>"Proses Sukse");
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	 
	/**
	 * fungsi untuk menghapus data Pelatihan Pegawai ke tabel 'e_sdm_peg_latih_tm'
	 */
	public function deletePelatihan(array $data) {
	//echo "masuk deletePegawai";
	//echo $data;
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     //echo "latih =>".$data['latih'];
		 $where[] = "i_peg_nip = '".$data['nip']."'";
		 $where[] = "n_pend_latih = '".$data['nmLatih']."'";
		 $where[] = "d_pend_tahunlatih = '".$data['thnlatih']."'";
		 
	     $db->delete('personal_diklat_tm', $where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	 
	//============================JABATAN============================= 
	public function getJabatanListByStatus($status)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		if($status == "SKT")
		{
			$result1 = $db->fetchAll("SELECT i_orgb, n_orgb, c_orgb_level
								FROM jabatan_struktural_tr
								ORDER BY i_orgb");

									
			$jmlResult1 = count($result1);
			//echo "TEST JML".$jmlResult;
			for ($i = 0; $i < $jmlResult1; $i++) {
				$hasilAkhir[$i] = array("c_peg_jabatan" =>(string)$result1[$i]->i_orgb,
										"n_peg_jabatan" =>(string)$result1[$i]->n_orgb,
										"c_orgb_level" =>(string)$result1[$i]->c_orgb_level);								    
			}	
			//var_dump($hasilAkhir);
		}
		else
		{
			$result = $db->fetchAll("SELECT c_peg_jabatan 
								FROM jabatan_tr
								where c_peg_status = '$status'
								ORDER BY  c_jab_level_auditor asc ");

								
			$jmlResult = count($result);
			//echo "TEST JML".$jmlResult;
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("c_peg_jabatan" =>(string)$result[$j]->c_peg_jabatan,
										"n_peg_jabatan" =>(string)$result[$j]->c_peg_jabatan);								    
			}					 
		 }
		 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	 
	public function getPejabatListByOrg($org)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		
		$result = $db->fetchAll("SELECT n_jabatan 
							FROM jabatan_struktural_tr
							where i_orgb = '$org'");

		$jmlResult = count($result);
		//echo "TEST JML".$jmlResult;
		for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("n_jabatan" =>(string)$result[$j]->n_jabatan);								    
		}	
		
		$nOrgb = $db->fetchOne("select n_orgb from jabatan_struktural_tr where i_orgb = '$org'");
		//echo "nOrgb = $nOrgb";
		$hasilAkhir[$jmlResult] = array("n_jabatan" =>"Staf $nOrgb");
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	} 
	 
	public function getEselonByOrg($org)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		
		$cOrgbLevel = $db->fetchOne("SELECT c_orgb_level 
							FROM jabatan_struktural_tr
							where i_orgb = '$org'");

		$jmlResult = count($result);
		//echo "TEST JML".$jmlResult;
		if($cOrgbLevel == '1')
		 {
			$eselon = "I.a";
		 }
		 else if($cOrgbLevel == '2')
		 {
			$eselon = "II.a";
		 }
		 else if($cOrgbLevel == '3')
		 {
			$eselon = "III.a";
		 }
		 else if($cOrgbLevel == '4')
		 {
			$eselon = "IV.a";
		 }
		 else
		 {
			$eselon = "staf";
		 }
			
		 return $eselon;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function getJabatanSekretariat($c_jabatan)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		$n_orgb = $db->fetchOne("SELECT n_orgb 
								FROM jabatan_struktural_tr
								WHERE i_orgb = '$c_jabatan'");
		
		 return $n_orgb;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function getSatminkalList()
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT c_satminkal, n_satminkal 
								FROM satminkal_tr order by CAST(c_satminkal AS UNSIGNED)");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("c_satminkal" =>(string)$result[$j]->c_satminkal,
									"n_satminkal" =>(string)$result[$j]->n_satminkal);								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	} 
	 
	public function getSatkerList($satminkal)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT c_satker, n_satker 
								FROM satker_tm 
								WHERE c_satminkal = '$satminkal'
								ORDER BY c_satker");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("c_satker" =>(string)$result[$j]->c_satker,
									"n_satker" =>(string)$result[$j]->n_satker);								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}  
	
	public function getSatkerByCode($c_satker)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$n_satker = $db->fetchOne("SELECT n_satker 
								FROM satker_tm 
								WHERE c_satker = '$c_satker'");

		 
		 return $n_satker;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal';
		}				
	}  
	
	public function getStatusByCode($status)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$n_peg_status = $db->fetchOne("SELECT n_peg_status 
								FROM personal_status_tr 
								WHERE c_peg_status = '$status'");

		 
		 return $n_peg_status;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}  
	 
	public function insertJabatan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     //echo "latih =>".$data['latih'];
		 $paramJabatInsert = array("i_peg_nip" => $data['i_peg_nip'],
								"c_peg_status" => $data['c_peg_status'],
								"c_peg_jabatan" => $data['c_peg_jabatan'],
								"n_jabatan" => $data['n_jabatan'],
								"n_instansi" => $data['n_instansi'],
								"a_lokasi" => $data['a_lokasi'],
								"e_jabatan" => $data['e_jabatan'],
								"n_bidang" => $data['n_bidang'],
								"c_satminkal" => $data['c_satminkal'],
								"c_satker" => $data['c_satker'],
								"d_jabatan_mulai" => $data['d_jabatan_mulai'],
								"d_jabatan_akhir" => $data['d_jabatan_akhir'],
								"i_sk" => $data['i_sk'],
								"d_sk" => $data['d_sk'],
								"c_eselon" => $data['c_eselon'],
								"d_eselon" => $data['d_eselon'],
								"i_entry" =>$data['userid'],
								"d_entry" => date('Y-m-d'));

			//var_dump($paramJabatInsert);						
  		 $db->insert('personal_jabatan_tm', $paramJabatInsert);
		 
		 $maxStatusDate = $db->fetchOne("select max(d_jabatan_mulai) from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."'");
		 $maxStatus = $db->fetchOne("select c_peg_status from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."' AND d_jabatan_mulai = '$maxStatusDate'");
		 $paramStatusJabatanUpdate = array("c_peg_status" => $maxStatus);
		 $whereStatusJabatanUpdate[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 
		 $db->update('personal_pokok_tm', $paramStatusJabatanUpdate, $whereStatusJabatanUpdate);
		 $db->commit();
		 unset($paramJabatInsert);
		 unset($paramStatusJabatanUpdate);
		 unset($whereStatusJabatanUpdate);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
		 
		 echo $e->getMessage().'<br>';
         $errMsg = $errmsgArr[0];
		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	} 
	
	public function getJabatListByNip($nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_jabatan_tm 
								WHERE i_peg_nip = '$nip'
								ORDER BY d_jabatan_mulai asc");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$nStatus = $db->fetchOne("SELECT n_peg_status
								FROM personal_status_tr 
								WHERE c_peg_status = '".$result[$j]->c_peg_status."'");
			
			
			$hasilAkhir[$j] = array("i_peg_nip" =>(string)$result[$j]->i_peg_nip,
									"c_peg_status" =>(string)$result[$j]->c_peg_status,
									"n_peg_status" =>$nStatus,
									"c_peg_jabatan" =>(string)$result[$j]->c_peg_jabatan,
									"n_jabatan" => (string)$result[$j]->n_jabatan,
									"n_instansi" => (string)$result[$j]->n_instansi,
									"a_lokasi" => (string)$result[$j]->a_lokasi,
									"e_jabatan" => (string)$result[$j]->e_jabatan,
									"n_bidang" =>(string)$result[$j]->n_bidang,
									"c_satminkal" =>(string)$result[$j]->c_satminkal,
									"c_satker" =>(string)$result[$j]->c_satker,
									"d_jabatan_mulai" =>(string)$result[$j]->d_jabatan_mulai,
									"d_jabatan_akhir" =>(string)$result[$j]->d_jabatan_akhir,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_sk" =>(string)$result[$j]->d_sk);								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}  

	public function getNamaStatusPegawai($cStatus)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$nStatus = $db->fetchOne("SELECT n_peg_status
								FROM personal_status_tr 
								WHERE c_peg_status = '$cStatus'");
								
		 					 
			return $nStatus;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}		
	}
	
	public function getPersonalJabatDetail($data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		$nip = $data['nip'];
		$status = $data['status'];
		$jabatan = $data['jabatan'];
		$tglMulai = $data['tglMulai'];
		
		$result = $db->fetchAll("SELECT *
								FROM personal_jabatan_tm 
								WHERE i_peg_nip = '$nip' AND
									  c_peg_status = '$status' AND
									  c_peg_jabatan = '$jabatan' AND
									  d_jabatan_mulai = '$tglMulai'");

									  
		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$cStatus = $result[$j]->c_peg_status;
			$nStatus = $this->getNamaStatusPegawai($cStatus);
			$hasilAkhir = array("i_peg_nip" =>(string)$result[$j]->i_peg_nip,
									"c_peg_status" =>(string)$result[$j]->c_peg_status,
									"n_peg_status" =>$nStatus,
									"c_peg_jabatan" =>(string)$result[$j]->c_peg_jabatan,
									"n_jabatan" => (string)$result[$j]->n_jabatan,
									"n_instansi" => (string)$result[$j]->n_instansi,
									"a_lokasi" => (string)$result[$j]->a_lokasi,
									"e_jabatan" => (string)$result[$j]->e_jabatan,
									"n_bidang" =>(string)$result[$j]->n_bidang,
									"c_satminkal" =>(string)$result[$j]->c_satminkal,
									"c_satker" =>(string)$result[$j]->c_satker,
									"d_jabatan_mulai" =>(string)$result[$j]->d_jabatan_mulai,
									"d_jabatan_akhir" =>(string)$result[$j]->d_jabatan_akhir,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_sk" =>(string)$result[$j]->d_sk,
									"c_eselon" =>(string)$result[$j]->c_eselon,
									"d_eselon" =>(string)$result[$j]->d_eselon);								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}		
	}
	
	public function updateJabatan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     //echo "latih =>".$data['latih'];
		 $paramJabatUpdate = array("c_peg_status" => $data['c_peg_status'],
									"c_peg_jabatan" => $data['c_peg_jabatan'],
									"n_jabatan" => $data['n_jabatan'],
									"n_instansi" => $data['n_instansi'],
									"a_lokasi" => $data['a_lokasi'],
									"e_jabatan" => $data['e_jabatan'],
									"n_bidang" => $data['n_bidang'],
									"c_satminkal" => $data['c_satminkal'],
									"c_satker" => $data['c_satker'],
									"d_jabatan_mulai" => $data['d_jabatan_mulai'],
									"d_jabatan_akhir" => $data['d_jabatan_akhir'],
									"i_sk" => $data['i_sk'],
									"d_sk" => $data['d_sk'],
									"c_eselon" => $data['c_eselon'],
									"d_eselon" => $data['d_eselon'],
									"i_entry" =>$data['userid'],
									"d_entry" => date('Y-m-d'));
		 //var_dump($paramJabatUpdate);
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $statusH = $data['c_peg_statusH'];
		 $where[] = "c_peg_status = '".$statusH."'";
		 $where[] = "n_jabatan = '".$data['n_jabatanH']."'";
		 $where[] = "d_jabatan_mulai = '".$data['d_jabatan_mulaiH']."'";
		 
		 $db->update('personal_jabatan_tm', $paramJabatUpdate, $where);
		 
		 $maxStatusDate = $db->fetchOne("select max(d_jabatan_mulai) from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."'");
		 $maxStatus = $db->fetchOne("select c_peg_status from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."' AND d_jabatan_mulai = '$maxStatusDate'");
		 
		 $paramStatusJabatanUpdate = array("c_peg_status" => $maxStatus);
		 $whereStatusJabatanUpdate[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 
		 $db->update('personal_pokok_tm', $paramStatusJabatanUpdate, $whereStatusJabatanUpdate);
		 $db->commit();
		 unset($paramJabatInsert);
		 unset($paramStatusJabatanUpdate);
		 unset($whereStatusJabatanUpdate);
		 
	     return 'sukses';
		 unset($where);
		 unset($paramJabatUpdate);
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}  
	
	public function deleteJabatan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "c_peg_status = '".$data['c_peg_status']."'";
		 $where[] = "c_peg_jabatan = '".$data['c_peg_jabatan']."'";
		 $where[] = "d_jabatan_mulai = '".$data['d_jabatan_mulai']."'";
		 
	     $db->delete('personal_jabatan_tm', $where);
		 
		 $maxStatusDate = $db->fetchOne("select max(d_jabatan_mulai) from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."'");
		 $maxStatus = $db->fetchOne("select c_peg_status from personal_jabatan_tm where i_peg_nip = '".$data['i_peg_nip']."' AND d_jabatan_mulai = '$maxStatusDate'");
		 
		 $paramStatusJabatanUpdate = array("c_peg_status" => $maxStatus);
		 $whereStatusJabatanUpdate[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 
		 $db->update('personal_pokok_tm', $paramStatusJabatanUpdate, $whereStatusJabatanUpdate);
		 $db->commit();
		 unset($paramJabatInsert);
		 unset($paramStatusJabatanUpdate);
		 unset($whereStatusJabatanUpdate);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getGolonganList()
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM golongan_tr
								ORDER BY c_peg_golongan");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("c_peg_golongan"=>(string)$result[$j]->c_peg_golongan,
								"n_peg_pangkat"			=>(string)$result[$j]->n_peg_pangkat
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function insertGolongan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 //var_dump($data);
		 $paramInsertGol = array("i_peg_nip" => $data['i_peg_nip'],
								"c_peg_golongan" => $data['c_peg_golongan'],
								"c_pns_status" => $data['c_pns_status'],
								"i_sk" => $data['i_sk'],
								"d_sk" => $data['d_sk'],
								"d_tmt_golongan" => $data['d_tmt_golongan'],
								"q_kerja_bulan" => $data['q_kerja_bulan'],
								"q_kerja_tahun" => $data['q_kerja_tahun'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
		 
	     $db->insert('personal_golongan_tm', $paramInsertGol);
		 $db->commit();
		 unset($paramInsertGol);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	public function getGolonganListByNip($nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_golongan_tm
								WHERE i_peg_nip = '$nip'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$n_peg_pangkat = $db->fetchOne("SELECT n_peg_pangkat
								FROM golongan_tr
								WHERE c_peg_golongan = '".$result[$j]->c_peg_golongan."'");
								
			$hasilAkhir[$j]	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"c_peg_golongan"	=>(string)$result[$j]->c_peg_golongan,
								"n_peg_pangkat"		=>$n_peg_pangkat,
								"c_pns_status"		=>(string)$result[$j]->c_pns_status,
								"i_sk"				=>(string)$result[$j]->i_sk,
								"d_sk"				=>(string)$result[$j]->d_sk,
								"d_tmt_golongan"	=>(string)$result[$j]->d_tmt_golongan,
								"c_peg_golongan"	=>(string)$result[$j]->c_peg_golongan,
								"q_kerja_bulan"		=>(string)$result[$j]->q_kerja_bulan,
								"q_kerja_tahun"		=>(string)$result[$j]->q_kerja_tahun
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function getPersonalGolonganDetail($data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_golongan_tm
								WHERE i_peg_nip = '".$data['nip']."'
									AND c_peg_golongan = '".$data['golongan']."'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$n_peg_pangkat = $db->fetchOne("SELECT n_peg_pangkat
								FROM golongan_tr
								WHERE c_peg_golongan = '".$result[$j]->c_peg_golongan."'");
								
			$hasilAkhir	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"c_peg_golongan"	=>(string)$result[$j]->c_peg_golongan,
								"n_peg_pangkat"		=>$n_peg_pangkat,
								"c_pns_status"		=>(string)$result[$j]->c_pns_status,
								"i_sk"				=>(string)$result[$j]->i_sk,
								"d_sk"				=>(string)$result[$j]->d_sk,
								"d_tmt_golongan"	=>(string)$result[$j]->d_tmt_golongan,
								"c_peg_golongan"	=>(string)$result[$j]->c_peg_golongan,
								"q_kerja_bulan"		=>(string)$result[$j]->q_kerja_bulan,
								"q_kerja_tahun"		=>(string)$result[$j]->q_kerja_tahun
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function updateGolongan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $paramUpdateGol = array("c_peg_golongan" => $data['c_peg_golongan'],
								"c_pns_status" => $data['c_pns_status'],
								"i_sk" => $data['i_sk'],
								"d_sk" => $data['d_sk'],
								"d_tmt_golongan" => $data['d_tmt_golongan'],
								"q_kerja_bulan" => $data['q_kerja_bulan'],
								"q_kerja_tahun" => $data['q_kerja_tahun'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
		 
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "c_peg_golongan = '".$data['c_peg_golonganH']."'";
		 $where[] = "c_pns_status = '".$data['c_pns_statusH']."'";
		 
	     $db->update('personal_golongan_tm', $paramUpdateGol, $where);
		 $db->commit();
		 unset($paramUpdateGol);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function hapusPersonalGolongan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "c_peg_golongan = '".$data['c_peg_golongan']."'";
		 
	     $db->delete('personal_golongan_tm', $where);
		 $db->commit();
		 unset($where);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	//Sertfikasi
	//------------
	public function getSertifikasiListByNip($i_peg_nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_sertifikasi_tm
								WHERE i_peg_nip = '$i_peg_nip'
								ORDER BY d_sertifikat desc");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir[$j] = array("i_peg_nip"			=>(string)$result[$j]->i_peg_nip,
									"i_sertifikat"		=>(string)$result[$j]->i_sertifikat,
									"n_sertifikat"		=>(string)$result[$j]->n_sertifikat,
									"n_lembaga"			=>(string)$result[$j]->n_lembaga,
									"d_sertifikat"		=>(string)$result[$j]->d_sertifikat,
									"d_mulaiberlaku"	=>(string)$result[$j]->d_mulaiberlaku,
									"d_akhirberlaku"	=>(string)$result[$j]->d_akhirberlaku,
									"e_keterangan"		=>(string)$result[$j]->e_keterangan
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function insertSertifikasi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 //var_dump($data);
		 $param = array("i_peg_nip" => $data['i_peg_nip'],
						"i_sertifikat" => $data['i_sertifikat'],
						"d_sertifikat" => $data['d_sertifikat'],
						"n_sertifikat" => $data['n_sertifikat'],
						"n_lembaga" => $data['n_lembaga'],
						"d_sertifikat" => $data['d_sertifikat'],
						"d_mulaiberlaku" => $data['d_mulaiberlaku'],
						"d_akhirberlaku" => $data['d_akhirberlaku'],
						"e_keterangan" => $data['e_keterangan'],
						"i_entry" => $data['i_entry'],
						"d_entry" => date('Y-m-d')
						);
		 
	     $db->insert('personal_sertifikasi_tm', $param);
		 $db->commit();
		 unset($param);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	
	public function getPersonalSertifikasiDetail($param)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
		$nip = $param['nip'];
		$iSertifikat = $param['iSertifikat'];
		
		$result = $db->fetchRow("SELECT *
								FROM personal_sertifikasi_tm
								WHERE i_peg_nip = '$nip' AND
										i_sertifikat = '$iSertifikat'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
			$hasilAkhir = array("i_peg_nip"			=>(string)$result->i_peg_nip,
								"i_sertifikat"		=>(string)$result->i_sertifikat,
								"n_sertifikat"		=>(string)$result->n_sertifikat,
								"n_lembaga"			=>(string)$result->n_lembaga,
								"d_sertifikat"		=>(string)$result->d_sertifikat,
								"d_mulaiberlaku"	=>(string)$result->d_mulaiberlaku,
								"d_akhirberlaku"	=>(string)$result->d_akhirberlaku,
								"e_keterangan"		=>(string)$result->e_keterangan
							    );								    
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function updateSertifikasi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 //var_dump($data);
		 $param = array("i_peg_nip" => $data['i_peg_nip'],
						"i_sertifikat" => $data['i_sertifikat'],
						"d_sertifikat" => $data['d_sertifikat'],
						"n_sertifikat" => $data['n_sertifikat'],
						"n_lembaga" => $data['n_lembaga'],
						"d_sertifikat" => $data['d_sertifikat'],
						"d_mulaiberlaku" => $data['d_mulaiberlaku'],
						"d_akhirberlaku" => $data['d_akhirberlaku'],
						"e_keterangan" => $data['e_keterangan'],
						"i_entry" => $data['i_entry'],
						"d_entry" => date('Y-m-d')
						);
		 
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "i_sertifikat = '".$data['i_sertifikatH']."'";
		 
	     $db->update('personal_sertifikasi_tm', $param, $where);
		 $db->commit();
		 unset($param);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	public function hapusSertifikasi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 //var_dump($data);
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "i_sertifikat = '".$data['i_sertifikat']."'";
		 
	     $db->delete('personal_sertifikasi_tm', $where);
		 $db->commit();
		 unset($param);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	//PENGHARGAAN
	public function getPenghargaanListByNip($nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_penghargaan_tm
								WHERE i_peg_nip = '$nip'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			
			$hasilAkhir[$j]	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"i_srt_penghargaan"	=>(string)$result[$j]->i_srt_penghargaan,
								"n_penghargaan"		=>(string)$result[$j]->n_penghargaan,
								"d_penghargaan"		=>(string)$result[$j]->d_penghargaan,
								"n_pejabat"		=>(string)$result[$j]->n_pejabat,
								"e_penghargaan"		=>(string)$result[$j]->e_penghargaan
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function insertPenghargaan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $paramInsert = array("i_peg_nip" => $data['i_peg_nip'],
								"i_srt_penghargaan" => $data['i_srt_penghargaan'],
								"n_penghargaan" => $data['n_penghargaan'],
								"d_penghargaan" => $data['d_penghargaan'],
								"n_pejabat" => $data['n_pejabat'],
								"e_penghargaan" => $data['e_penghargaan'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
		 //var_dump($paramInsert);
	     $db->insert('personal_penghargaan_tm', $paramInsert);
		 $db->commit();
		 unset($paramInsert);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
		 $errmsgArr = explode(":",$e->getMessage());
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	public function getPersonalPenghargaanDetail($data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_penghargaan_tm
								WHERE i_peg_nip = '".$data['nip']."'
									AND n_penghargaan = '".$data['nmHarga']."'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"i_srt_penghargaan"	=>(string)$result[$j]->i_srt_penghargaan,
								"n_penghargaan"		=>(string)$result[$j]->n_penghargaan,
								"d_penghargaan"		=>(string)$result[$j]->d_penghargaan,
								"n_pejabat"		=>(string)$result[$j]->n_pejabat,
								"e_penghargaan"		=>(string)$result[$j]->e_penghargaan
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function updatePenghargaan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $paramInsert = array("i_srt_penghargaan" => $data['i_srt_penghargaan'],
								"n_penghargaan" => $data['n_penghargaan'],
								"d_penghargaan" => $data['d_penghargaan'],
								"n_pejabat"		=>$data['n_pejabat'],
								"e_penghargaan" => $data['e_penghargaan'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "n_penghargaan = '".$data['n_penghargaanH']."'";
	     $db->update('personal_penghargaan_tm', $paramInsert, $where);
		 $db->commit();
		 unset($paramInsert);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function hapusPersonalPenghargaan(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "n_penghargaan = '".$data['n_penghargaan']."'";
	     $db->delete('personal_penghargaan_tm',$where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	//HUKUMAN
	public function getHukumanListByNip($nip)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_hukuman_tm
								WHERE i_peg_nip = '$nip'");

		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			
			$hasilAkhir[$j]	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"i_hukuman"	=>(string)$result[$j]->i_hukuman,
								"n_hukuman"		=>(string)$result[$j]->n_hukuman,
								"d_hukuman"		=>(string)$result[$j]->d_hukuman,
								"n_pejabat"		=>(string)$result[$j]->n_pejabat,
								"e_hukuman"		=>(string)$result[$j]->e_hukuman
							    );								    
		 }					 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function insertHukuman(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $paramInsert = array("i_peg_nip" => $data['i_peg_nip'],
								"i_hukuman" => $data['i_hukuman'],
								"n_hukuman" => $data['n_hukuman'],
								"c_hukuman" => $data['c_hukuman'],
								"d_hukuman" => $data['d_hukuman'],
								"n_pejabat" => $data['n_pejabat'],
								"e_hukuman" => $data['e_hukuman'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
	     $db->insert('personal_hukuman_tm', $paramInsert);
		 $db->commit();
		 unset($paramInsert);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
		 $errmsgArr = explode(":",$e->getMessage());
         $errMsg = $errmsgArr[0];

		if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	public function getPersonalHukumanDetail($data)
	{
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $db->fetchAll("SELECT *
								FROM personal_hukuman_tm
								WHERE i_peg_nip = '".$data['nip']."'
									AND n_hukuman = '".$data['nmSurat']."'");

								
		 $jmlResult = count($result);
		 //echo "TEST JML".$jmlResult;
		 for ($j = 0; $j < $jmlResult; $j++) {
			$hasilAkhir	= array("i_peg_nip"  		=>(string)$result[$j]->i_peg_nip,
								"i_hukuman"	=>(string)$result[$j]->i_hukuman,
								"n_hukuman"		=>(string)$result[$j]->n_hukuman,
								"c_hukuman"		=>(string)$result[$j]->c_hukuman,
								"d_hukuman"		=>(string)$result[$j]->d_hukuman,
								"n_pejabat"		=>(string)$result[$j]->n_pejabat,
								"e_hukuman"		=>(string)$result[$j]->e_hukuman
							    );								    
		 }				
	 
		 return $hasilAkhir;
		} catch (Exception $e) {
		 echo $e->getMessage().'<br>';
		 return 'gagal <br>';
		}				
	}
	
	public function updateHukuman(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     
		 $paramInsert = array("i_hukuman" => $data['i_hukuman'],
								"n_hukuman" => $data['n_hukuman'],
								"d_hukuman" => $data['d_hukuman'],
								"c_hukuman" => $data['c_hukuman'],
								"e_hukuman" => $data['e_hukuman'],
								"i_entry" => $data['i_entry'],
								"d_entry" => date('Y-m-d')
								);
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "n_hukuman = '".$data['n_hukumanH']."'";
	     $db->update('personal_hukuman_tm', $paramInsert, $where);
		 $db->commit();
		 unset($paramInsert);
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
		 $errmsgArr = explode(":",$e->getMessage());
		 $errMsg = $errmsgArr[0];
	     if($errMsg == "SQLSTATE[23000]")
		{
			return "gagal.Data Sudah Ada.";
		}
		else
		{
			return "gagal.";
		}
	   }
	}
	
	public function hapusPersonalHukuman(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_peg_nip = '".$data['i_peg_nip']."'";
		 $where[] = "n_hukuman = '".$data['n_hukuman']."'";
	     $db->delete('personal_hukuman_tm',$where);
		 $db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
///////////////////////////////////////////////////////////////////////////////////////////////////	 end of ipu
}
?>