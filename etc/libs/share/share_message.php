<?
class share_message {

	/* keterangan : proses : 1=insert, 2=edit 
			     keterangan : keterangan data modul yg diproses
		              status : result dari db sukses / gagal
	===========================================================*/
	
	public function show_message($proses, $keterangan, $status ) //(1=Insert; 2=Edit
	{
		$html =  "<div id=\"confirm\" class=\"confirm\" style=\"display: none\">";
		
		if($proses == '1')
		{
			$proses = "Tambah";
		}
		else if($proses == '2')
		{
			$proses = "Ubah";
		}
		else if($proses == '3')
		{
			$proses = "Hapus";
		}
		
		if(trim($status) == "sukses")
		{
			$message = "$proses data $keterangan berhasil";
		}
		else if(trim($status) == "gagal")
		{
			$message = "$proses data $keterangan <span class=\"red\">gagal</span>";
		}
		else
		{
			$message = "$proses data $keterangan <span class=\"red\">gagal</span> <br> $status";
		}
			
		$html = $html.$message."</div>";
		echo $html;
	}
}	
?>	