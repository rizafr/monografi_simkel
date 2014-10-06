// JavaScript Document
jQuery(document).ready(function () {
                         //var perintah4 = document.getElementById('perintah4');
						 //perintah4.click = ListKat();
                       });
					   
function openList(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('listpersonal.phtml','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function openList2(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('ast_databarang_view.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function ListRekanan(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('AST_ListRekanan_View.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function PemberiList(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('listpersonal.phtml','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function PenerimaList(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('listpersonal2.phtml','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function FasilitasList(){
    var KdRuang = document.getElementById('kdRuang');
    alert('fasilitas'+KdRuang.value);
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listfasilitas/prm/'+KdRuang.value,'pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function inforuangrapat(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('ast_informasiruangrapat_view.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function inforuangan(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/inforuanganlist','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function refBarangList(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('ASTDataBarangReferensi.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function dataPenyerahan(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('ASTDataPenyerahan.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function dataPenerima(){
	//alert("testst");
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listpenerima','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function dataPemberi(){
	//alert("testst");
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listpemberi','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function dataUnitKerja(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/ASTDataUnitKerja.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function dataSupplier(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listsupplier','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function ListKat(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/atkproses/listcatagory','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }

}
function ListRuangan(){
    alert("testst"); 
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listruangan','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }

}
function ListATK(){
    var KatBarang = document.getElementById('KatBarang');
     
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/atkproses/listatk/prm/'+KatBarang.value,'pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function ListBarang(){
    var nopeng = document.getElementById('noajuan');
    var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/penerimaanatk/listbarang/prm/'+nopeng.value,'pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function ListSatuan(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/listsatuan','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function daftarPenerimaanBarang(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/ast_daftarpenerimaanbarang.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function pengajuanSewaMasuk(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/reffer/ast_databarang_pengajuanlist.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}

function dataRuang(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('ast_informasiruanglist.php','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}
function penyerahanKdBrgList(){
	var pos1 = (screen.width - 600) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('/ast/penyerahan/penyerahanbaranglist','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}