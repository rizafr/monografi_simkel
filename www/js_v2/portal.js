jQuery(document).ready(function () { 
	var loaderParm = document.getElementById('loading');
	if (loaderParm == null) {
		//location.href="#top";
	  var loader = jQuery('<div id="loading"><div class="image"><img  src="images/globe32.gif" width="32" height="32"/></div><div class="load">Loading...</div></div></div>')
		  //.css({position: "relative", top: "1em", left: "1em"})
		  .hide()
		  .appendTo("body");
		jQuery().ajaxStart(function() {
			loader.show();
		}).ajaxStop(function() {
			loader.hide();			
		});		  
	}
});  

var please_wait = null;
var xmlHttp = null;

try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
} catch (e) {
    // Internet Explorer
    try {
        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
}

function open_url(url, target) {
 	if ( ! document.getElementById) {
  		return false;
 	}

 	if (please_wait != null) {
  		document.getElementById(target).innerHTML = please_wait;
 	}

 	if (window.ActiveXObject) {
  		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
 	} else if (window.XMLHttpRequest) {
  		xmlHttp = new XMLHttpRequest();
 	}

 	if (xmlHttp == undefined) {
  		return false;
 	}
 	xmlHttp.onreadystatechange = function() { response(url, target); }
 	xmlHttp.open("GET", url, true);
 	xmlHttp.send(null);
}

function response(url, target) {
 	if (xmlHttp.readyState == 4) {
	 	document.getElementById(target).innerHTML = (xmlHttp.status == 200) ? xmlHttp.responseText : "Ooops!! A broken link! Please contact the webmaster of this website ASAP and give him the fallowing errorcode: " + xmlHttp.status;
	}
}

function set_loading_message(msg) {
 	please_wait = msg;
}

function validateForm(idform, actionform, target) {
    var form = document.getElementById(idform);
	form.action = actionform;
	form.submit();
	open_url(actionform, target);
}
function open_url_to_div(pageAction, scriptAction) {
   jQuery.get(pageAction,function(data) {
		jQuery("#contentview").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	  
}
function showDetil(pageAction,id,mod){
//alert ('detil')
	var url=" <strong>&#8250;</strong> "+mod;
	var param = {id:id};
	jQuery.get(pageAction, param, function(data) {
		jQuery("#middle").html(data);
		jQuery("#breadcrumbs").html(url);
		});
}
function showDetilmed(pageAction,id,pageAction2,scriptAction,mod){
	var url1=" <strong>&#8250;</strong> <a href=# onclick=goExec_Script('"+pageAction2+"','"+scriptAction+"','"+mod+"')>"+mod+"</a>";
	var url2 = " <strong>&#8250;</strong> Detail "+mod;	
	var url=url1+url2
	var param = {id:id};
		jQuery.get(pageAction, param, function(data) {
		scroll(0,0);
		//jQuery("#breadcrumbs").html(url);
			jQuery("#middle").html(data);
		});
}
function goExec_Script(pageAction, scriptAction,mod) {
//alert(mod);
	var url=" <strong>&#8250;</strong> "+mod;
	jQuery.get(pageAction,function(data) {
		scroll(0,0);
		jQuery("#middle").html(data);
		jQuery("#breadcrumbs").html(url);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	  
}
function goExec_ScriptId(pageAction,idkategori,scriptAction,mod) {
//alert(mod);
	var url=" <strong>&#8250;</strong> "+mod;
	var param = {idkategori:idkategori};
	jQuery.get(pageAction,param,function(data) {
		scroll(0,0);
		jQuery("#middle").html(data);
		jQuery("#breadcrumbs").html(url);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	  
}
function goExec(pageAction){

	//var url = '<?php echo $this->basePath;?>/sdmmodule/datapegawai/pegawai';	
		jQuery.get(pageAction, function(data) {
		scroll(0,0);
			
			jQuery("#middle").html(data);
		});
}
function open_url_to_right(pageAction, scriptAction) {
   jQuery.get(pageAction,function(data) {
		jQuery("#contentview").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	  
}

function doCount(countdown) {
	document.getElementById("confirm").style.display="block";
	
	if (countdown > 0) {
        countdown=countdown-1;
		window.status=countdown + " seconds left to view this page.";
		setTimeout('doCount()',5000); 
    }
    else {
        document.getElementById("confirm").style.display="none";
    } 


}

function ConfirmDelete(par1, par2) {     
    var keterangan = par1;
	var isiKeterangan = par2;
	
    answer = confirm('Anda akan menghapus '+keterangan+ ' ' +isiKeterangan+ '.\n\'OK\' untuk menghapus, \'Cancel\' untuk membatalkan.' );
    if(answer !=0) { 
       return true;
    } 
}

function isNumber(id){
	var my_string=document.getElementById(id).value;
	
	if(isNaN(my_string)){
		alert('Isikan dengan angka.');
		document.getElementById(id).focus();
		document.getElementById(id).value='';
	}
}

function gantinewPage(divId, modul,currentPage,param1,param2,param3,param4)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4};
	jQuery.get(modul,opt,function(data) {
		jQuery("#"+divId).html(data);
	});
}



function windowOpens(url){var w=0;var h=0;w=screen.availWidth;h=screen.availHeight;var popW=800,popH=500;var leftc=(w-popW)/2;var topc=(h-popH)/2;var selectWindow=window.open(url,'Selection','left='+leftc+',top='+topc+', width='+popW+',height='+popH+',resizable=0,scrollbars=yes')}
function doCounter(countdown){document.getElementById("confirm").style.display="block";if(countdown>0){countdown=countdown-1;window.status=countdown+" seconds left to view this page.";setTimeout('doCounter()',5000)}else{document.getElementById("confirm").style.display="none"}}
function orderBy(orderBy,order,url){var param={orderBy:orderBy,order:order};jQuery.get(url,param,function(data){jQuery("#contentview").html(data)})}
function sdmValidasiData(xstr,FocusField,Message){alert(Message);eval(xstr+"."+FocusField+".focus();");return false;}
function setvalueupl(v,n,f,t) {if (f!="") {var pass=false;var af=f.split("/");var nval=eval("document.forms[0]."+n);var ext=v.substring(v.lastIndexOf(".")+1,v.length);for(var n in af) {if (af[n].toLowerCase()==ext.toLowerCase()) {pass=true;break;}}if (!pass) {alert ("Hanya untuk file berekstensi '"+f+"'");nval.value="";return;}}var nt=eval("document.forms[0]."+t);nt.value=v;}
function compareDate(dstrt,dfins){dstrt=dstrt.replace("/","");dstrt=dstrt.replace("-","");dfins=dfins.replace("/","");dfins=dfins.replace("-","");if (dstrt>dfins){alert ("Tanggal Mulai lebih besar dari tanggal Selesai....");return false;}else{return true;}}