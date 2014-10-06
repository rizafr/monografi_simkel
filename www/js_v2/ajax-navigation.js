 jQuery(document).ready(function () {
	var loaderParm = document.getElementById('loading');
	if (loaderParm == null) {
	  var loader = jQuery('<div id="loading"><div class="image"><img alt="" src="images/globe32.gif"/></div><div class="load">Loading .....</div></div>')
		 // .css({position: "relative", top: "1em", left: "1em"})
		  .hide()
		  .appendTo("body");

		$().ajaxStart(function() {
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
		$("#tableview").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	  
}

function open_url_to_div1(pageAction) {
   jQuery.get(pageAction,function(data) {
		$("#tableview").html(data);
   });	  
}

function open_url_to_div2(pageAction, scriptAction, scriptAction2) {
   var opt = { md:scriptAction2 }
   jQuery.get(pageAction, opt, function(data) {
		$("#tableview").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	   

}

function OpenLogin(pageAction, scriptAction) {
   var opt = { md:scriptAction }
   jQuery.get(pageAction, opt, function(data) {
		$("#tableview").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	   

}

function open_url_to_divindex(pageAction) {
   jQuery.get(pageAction,function(data) {
		$("#main").html(data);
   });	  
}

function open_url_to_home(pageAction, scriptAction) {
   var opt = { md:scriptAction}
   jQuery.get(pageAction, opt, function(data) {
		$("#halutama").html(data);
        jQuery.getScript(scriptAction,function (data) {
        });
   });	   

}
