/* $(document).ready(function() {
	getNotifikasiScript();
});
 */
/* function getNotifikasiScript() {
	 handler = "notifikasi";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bd").html(data);
			$("#bd").css("display","block");			
		 });
	getHlmdpAbsenScript();
//	getHlmdpSuratScript(); 
//	getHlmdpBuletinScript(); 	
//	getHlmdpKlipingScript(); 
//	getHlmdpAgendaScript(); 
//	getHlmdpUltahScript();
//	getHlmdpPejabatScript();
//	getHlmdpSOPScript();
	//getHlmdpUmumScript();
}

function getHlmdpPejabatScript() {
	 handler = "hlmdppejabat";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdpejabat").html(data);
			$("#bdpejabat").css("display","block");			
		 });
		 
}

function getHlmdpSOPScript() {
	 handler = "hlmdpsop";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdsop").html(data);
			$("#bdsop").css("display","block");			
		 });
		 
}

function getHlmdpUltahScript() {
	 handler = "hlmdpultah";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdultah").html(data);
			$("#bdultah").css("display","block");			
		 });
		 
}

function getHlmdpAbsenScript()
{
	 handler = "absensiresult";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			dataawal = document.getElementById("lsb1").innerHTML;
			datanya=dataawal+data;
			//alert('data : '+datanya);
			$("#lsb1").html(datanya);
			$("#lsb1").css("display","block");			
		 });
}

function getHlmdpSuratScript() {
	 handler = "hlmdpsurat";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdsurat").html(data);
			$("#bdsurat").css("display","block");			
		 });
		 
}

function getHlmdpBuletinScript() {
	 handler = "hlmdpbuletin";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdbuletin").html(data);
			$("#bdbuletin").css("display","block");			
		 });
}		 

function getHlmdpKlipingScript() {
	 handler = "hlmdpkliping";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdkliping").html(data);
			$("#bdkliping").css("display","block");			
		 });
}
function getHlmdpAgendaScript() {
	 handler = "hlmdpagenda";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdagenda").html(data);
			$("#bdagenda").css("display","block");	
				
		 });		 
} */
function getHlmdpUmumScript() {
	 handler = "hlmdpumum";
	 var opt = {};
	 jQuery.get(handler,opt,function(data) {       
			$("#bdumum").html(data);
			$("#bdumum").css("display","block");			
		 });
}

function buletinPDF(basePath,nmfile,fold){
	  var url = basePath+'/hms/buletin/getpdf';
	  //var url = "getbuletinpdf";
      var pos1 = (screen.width - 750) / 2;
      var pos2 = (screen.height - 400) / 2;
	  var nmfile = nmfile ;
      panel = 'width=750,height=450,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
      winlist = window.open(url+'?jns='+fold+'&pdf='+nmfile,'pagelist',panel);
	}

function goBuletinIndex(basePath) {
	var urlDepan = basePath+'/hms/buletin/buletin';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/hms/buletin/buletinjs',function (data) {}
	  );
	  //function () {}
      });
	}

function klipingPDF(basePath,nmfile,fold){
//alert (fold+' :: '+nmfile);
	  var url = basePath+'/hms/kliping/getpdf';
      var pos1 = (screen.width - 750) / 2;
      var pos2 = (screen.height - 400) / 2;
	  var nmfile = nmfile ;
      panel = 'width=750,height=450,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
      winlist = window.open(url+'?jns='+fold+'&pdf='+nmfile,'pagelist',panel);

}
	
function goKlipingIndex(basePath) {
    var urlDepan = basePath+'/hms/kliping/kliping';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/hms/kliping/klipingjs',function (data) {});
	  //function () {}
      });
	}	

function goKonsepSrtIndex(basePath) {
    var urlDepan = basePath+'/srt/suratkonsep/suratkonsep';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratkonsep/suratkonsepjs',function (data) {});
	  //function () {}
      });
	}

function goSrtMasukIndex(basePath) {
    var urlDepan = basePath+'/srt/suratmasuk/suratmasuk';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratmasuk/suratmasukjs',function (data) {});
	  //function () {}
      });
	}

function goSrtKeluarIndex(basePath) {
    var urlDepan = basePath+'/srt/suratkeluar/suratkeluar';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratkeluar/suratkeluarjs',function (data) {});
	  //function () {}
      });
	}

function goNotaMasukIndex(basePath) {
    var urlDepan = basePath+'/srt/suratnotamasuk/suratnotamasuk';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratnotamasuk/suratnotamasukjs',function (data) {});
	  //function () {}
      });
	}

function goNotaKeluarIndex(basePath) {
    var urlDepan = basePath+'/srt/suratnotakeluar/suratnotakeluar';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratnotakeluar/suratnotakeluarjs',function (data) {});
	  //function () {}
      });
	}

function goMemoMasukIndex(basePath) {
    var urlDepan = basePath+'/srt/suratmemomasuk/suratmemomasuk';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratmemomasuk/suratmemomasukjs',function (data) {});
	  //function () {}
      });
	}

function goMemoKeluarIndex(basePath) {
    var urlDepan = basePath+'/srt/suratmemokeluar/suratmemokeluar';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/srt/suratmemokeluar/suratmemokeluarjs',function (data) {});
	  //function () {}
      });
	}

function testAlert(basePath) {
	alert('Tes : '+basePath);
	
	}

function goSOPIndex(basePath) {
    var url = basePath+'/home/sop/sopindex';
    // jQuery.get(url, function(data) {
	  // $("#tableview").html(data);
	  // function () {}
      // });
      var pos1 = (screen.width - 750) / 2;
      var pos2 = (screen.height - 400) / 2;
	  var nmfile = nmfile ;
      panel = 'width=750,height=450,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
      winlist = window.open(url,'pagelist',panel);
	  
	}

 function getIdSearch(iSearch){
 	document.getElementById("strCari").value='';
 	//var xDispl='inline';
 	var xDispl='block';
 	switch (iSearch) {
	case '1':
		//document.getElementById("legend").style.display=xDispl;
		break;
	case '2':
		//document.getElementById("legend").style.display='none';
		break;
	case '3':
		//document.getElementById("legend").style.display='none';
		break;
	default:
		//document.getElementById("legend").style.display=xDispl;	
	}
 }	
	
  
 function cmdSOPSearch(basepath){
 
	var iSearch = document.getElementById('iSearch').value;
	var strSearch = document.getElementById('strCari').value;
	var urlDepan = basepath+'/home/sop/sopindex?iSearch='+iSearch+'&strSearch='+strSearch;
	//alert(iSearch +" | "+strSearch);
	jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/js/halamandepan.js', function (data) {});
	  //function () {}
      });
 } 

function sopPDF(basePath,nmfile){
	var url = basePath+'/home/sop/getpdf';
	var pos1 = (screen.width - 750) / 2;
	var pos2 = (screen.height - 400) / 2;
	var nmfile = nmfile ;
	var fold = 'sop';
      panel = 'width=750,height=450,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
      winlist = window.open(url+'?jns='+fold+'&pdf='+nmfile,'pagelist',panel);
	}

function goPenelusuranProdHkm(basePath) {
	var urlDepan = basePath+'/hum/produk/produkpenelusuran';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/hum/produk/penelusuranjs',function (data) {}
	  );
	  //function () {}
      });
	}	
function goPenelusuranKoleksi(basePath) {
	var urlDepan = basePath+'/dok/arsip/bukuhalutama';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/dok/arsip/datakoleksijs',function (data) {}
	  );
	  //function () {}
      });
	}

function goPenelusuranlaporan(basePath) {
	var urlDepan = basePath+'/dok/arsip/laphaldepan';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/dok/arsip/datakoleksijs',function (data) {}
	  );
	  //function () {}
      });
	}
	
function goArsipSrtMsk(basePath) {
	var urlDepan = basePath+'/dok/kearsipan/arsiphalutama';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/dok/kearsipan/arsipjs',function (data) {}
	  );
	  //function () {}
      });
	}

function goArsipSrtKeluar(basePath) {
	var urlDepan = basePath+'/dok/kearsipan/arsiphalutama2';
    jQuery.get(urlDepan, function(data) {
	  $("#tableview").html(data);
	  jQuery.getScript(basePath+'/dok/kearsipan/arsipjs',function (data) {}
	  );
	  //function () {}
      });
	}
function viewNamaPeg() {

}	