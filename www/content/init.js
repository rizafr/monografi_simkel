var baseUrl = '';
tinyMCE.init({
	mode : "exact",
	elements : "content1,description",
	theme : "advanced",
	skin : "o2k7",
	skin_variant : "silver",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",
	
	theme_advanced_buttons1: "copy,paste,pastetext,pasteword,|,search,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,code,|,preview,fullscreen,imagemanager,|,bold,italic,underline,strikethrough,|justifyleft,justifycenter,justifyright,justifyfull,image",
	theme_advanced_buttons2 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	file_browser_callback : "ajaxfilemanager",
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : true,
	apply_source_formatting : true,
	force_br_newlines : true,
	force_p_newlines : true,	
	relative_urls : true
});

;
	
/*            return false;			
	var fileBrowserWindow = new Array();
	fileBrowserWindow["file"] = ajaxfilemanagerurl;
	fileBrowserWindow["title"] = "Ajax File Manager";
	fileBrowserWindow["width"] = "782";
	fileBrowserWindow["height"] = "440";
	fileBrowserWindow["close_previous"] = "no";
	tinyMCE.openWindow(fileBrowserWindow, {
	  window : win,
	  input : field_name,
	  resizable : "yes",
	  inline : "yes",
	  editor_id : tinyMCE.getWindowArg("editor_id")
	});
	
	return false;*/
