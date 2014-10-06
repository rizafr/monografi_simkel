// JavaScript Document
function browseFile(){
	var pos1 = (screen.width - 400) / 2;
	var pos2 = (screen.height - 400) / 2;
	panel = 'width=650,height=300,status=no,resizable=no,scrollBars=yes,top='+pos2+',left='+pos1;
	winlist = window.open('uploadfile.phtml','pagelist',panel);
	if (parseInt(navigator.appVersion) >= 4) { winlist.window.focus(); }
}