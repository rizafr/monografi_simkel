/**
* phpBB3 forum functions
*/

/**
* Window popup
*/
function popup(url, width, height)
{
	window.open(url.replace(/&amp;/g, '&'), '_popup', 'height=' + height + ',resizable=yes,scrollbars=yes, width=' + width);
	return false;
}

/**
* Jump to page
*/
/* function jumpto()
{
	var page = prompt('{LA_JUMP_PAGE}:', '{ON_PAGE}');
	var perpage = '{PER_PAGE}';
	var base_url = '{BASE_URL}';

	if (page !== null && !isNaN(page) && page > 0)
	{
		document.location.href = base_url.replace(/&amp;/g, '&') + '&start=' + ((page - 1) * perpage);
	}
} */

// function jumpto()
// {
	// var page = prompt(jump_page, on_page);
	//var halamake = document.on_page.value;
	// var base_url='/sdm/dp3/listpegawai';
	// if (page !== null && !isNaN(page) && page > 0)
	// {
		// document.location.href = base_url.replace(/&amp;/g, '&') + '&currentPage=' + halamake;
		// document.location.href = base_url.replace(/&amp;/g, '&') + '&start=' + ((page - 1) * per_page);
		// document.location.href = base_url.replace(/&amp;/g, '&') + '&start=' + page;
	// }
// }

function jumpto(handler,totalPages,fungsi,param1,param2,param3,param4){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("div#tableview").html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			/*if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}*/
		});
	}
	else
	{		
		alert("halamanTerakhir : "+totalPages);
	}
	
}

function jumpto2(handler,totalPages,fungsi,param1,param2,param3,param4, divId){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("#"+divId).html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}
		});
	}
	else
	{		
		alert("halamanTerakhir : "+totalPages);
	}
	
}


function jumpto5(handler,totalPages,fungsi,param1,param2,param3,param4,param5,param6){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, param5 : param5, param6 : param6, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("#tableview").html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}
		});
	}
	else
	{		
		alert("halamanTerakhir : "+totalPages);
	}
	
}

function jumpto6(handler,totalPages,fungsi,param1,param2,param3,param4,param5,param6,param7){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, param5 : param5, param6 : param6, param7 : param7, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("#tableview").html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}
		});
	}
	else
	{		
		alert("halamanTerakhir : "+totalPages);
	}
	
}

function jumpto7(handler,totalPages,fungsi,param1,param2,param3,param4,param5,param6,param7,param8){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, param5 : param5, param6 : param6, param7 : param7, param8 : param8, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("#tableview").html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}
		});
	}
	else
	{		
		alert("Halaman Terakhir : "+jump_page);
	}
	
}
function jumpto10(handler,totalPages,fungsi,param1,param2,param3,param4,param5,param6,param7,param8,param9,param10){
	var jump_page = window.prompt("Masukkan no halaman yang ingin Anda tuju:");
	totalPages = totalPages*1;
	var currentPage = jump_page*1;
	
	if (jump_page !== null && !isNaN(jump_page) && jump_page > 0 && (currentPage <= totalPages))
	{
		var opt = {currentPage : jump_page , jumpPage : 1, param1 : param1, param2 : param2, param3 : param3 , param4 : param4, param5 : param5, param6 : param6, param7 : param7, param8 : param8, param9 : param9, param10 : param10, paging :'paging'};

		jQuery.get(handler,opt,function(data) {
			$("#tableview").html(data);	
			//$("a[@class=newPage]").unbind("click");			
			//$("a[@class=newPage]").bind("click",fungsi);
			if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}
		});
	}
	else
	{		
		alert("Halaman Terakhir : "+jump_page);
	}
	
}
function gantinewPage(modul,fungsi,currentPage,param1,param2,param3,param4)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4};
	jQuery.get(modul,opt,function(data) {
		$("#tableview").html(data);
//alert(data);
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}

function gantinewPage2(modul,fungsi,currentPage,param1,param2,param3,param4,divId)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4};
	jQuery.get(modul,opt,function(data) {
		$("#"+divId).html(data);	
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}

function gantinewPage5(modul,fungsi,currentPage,param1,param2,param3,param4,param5,param6)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4 , param5 : param5 , param6 : param6};
	jQuery.get(modul,opt,function(data) {
		$("#tableview").html(data);
//alert(data);
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}

function gantinewPage6(modul,fungsi,currentPage,param1,param2,param3,param4,param5,param6,param7)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4 , param5 : param5 , param6 : param6, param7 : param7};
	jQuery.get(modul,opt,function(data) {
		$("#tableview").html(data);
//alert(data);
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}

function gantinewPage7(modul,fungsi,currentPage,param1,param2,param3,param4,param5,param6,param7,param8)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4 , param5 : param5 , param6 : param6, param7 : param7, param8 : param8};
	jQuery.get(modul,opt,function(data) {
		$("#tableview").html(data);
//alert(data);
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}
function gantinewPage10(modul,fungsi,currentPage,param1,param2,param3,param4,param5,param6,param7,param8,param9,param10)
{
	var opt = {currentPage : currentPage, param1 : param1, param2 : param2, param3 : param3 , param4 : param4 , param5 : param5 , param6 : param6, param7 : param7, param8 : param8, param9 : param9, param10 : param10};
	jQuery.get(modul,opt,function(data) {
		$("#tableview").html(data);
//alert(data);
       if(fungsi)
			{
				//alert(fungsi);
				jQuery.getScript(fungsi);
			}		
	});
}
/**
* Mark/unmark checklist
* id = ID of parent container, name = name prefix, state = state [true/false]
*/
function marklist(id, name, state)
{
	var parent = document.getElementById(id);
	if (!parent)
	{
		eval('parent = document.' + id);
	}

	if (!parent)
	{
		return;
	}

	var rb = parent.getElementsByTagName('input');
	
	for (var r = 0; r < rb.length; r++)
	{
		if (rb[r].name.substr(0, name.length) == name)
		{
			rb[r].checked = state;
		}
	}
}

/**
* Resize viewable area for attached image or topic review panel (possibly others to come)
* e = element
*/
function viewableArea(e, itself)
{
	if (!e) return;
	if (!itself)
	{
		e = e.parentNode;
	}
	
	if (!e.vaHeight)
	{
		// Store viewable area height before changing style to auto
		e.vaHeight = e.offsetHeight;
		e.vaMaxHeight = e.style.maxHeight;
		e.style.height = 'auto';
		e.style.maxHeight = 'none';
		e.style.overflow = 'visible';
	}
	else
	{
		// Restore viewable area height to the default
		e.style.height = e.vaHeight + 'px';
		e.style.overflow = 'auto';
		e.style.maxHeight = e.vaMaxHeight;
		e.vaHeight = false;
	}
}

/**
* Set display of page element
* s[-1,0,1] = hide,toggle display,show
*/
function dE(n, s)
{
	var e = document.getElementById(n);

	if (!s)
	{
		s = (e.style.display == '' || e.style.display == 'block') ? -1 : 1;
	}
	e.style.display = (s == 1) ? 'block' : 'none';
}

/**
* Alternate display of subPanels
*/
function subPanels(p)
{
	var i, e, t;

	if (typeof(p) == 'string')
	{
		show_panel = p;
	}

	for (i = 0; i < panels.length; i++)
	{
		e = document.getElementById(panels[i]);
		t = document.getElementById(panels[i] + '-tab');

		if (e)
		{
			if (panels[i] == show_panel)
			{
				e.style.display = 'block';
				if (t)
				{
					t.className = 'activetab';
				}
			}
			else
			{
				e.style.display = 'none';
				if (t)
				{
					t.className = '';
				}
			}
		}
	}
}

/**
* Call print preview
*/
function printPage()
{
	if (is_ie)
	{
		printPreview();
	}
	else
	{
		window.print();
	}
}

/**
* Show/hide groups of blocks
* c = CSS style name
* e = checkbox element
* t = toggle dispay state (used to show 'grip-show' image in the profile block when hiding the profiles) 
*/
function displayBlocks(c, e, t)
{
	var s = (e.checked == true) ?  1 : -1;

	if (t)
	{
		s *= -1;
	}

	var divs = document.getElementsByTagName("DIV");

	for (var d = 0; d < divs.length; d++)
	{
		if (divs[d].className.indexOf(c) == 0)
		{
			divs[d].style.display = (s == 1) ? 'none' : 'block';
		}
	}
}

function selectCode(a)
{
	// Get ID of code block
	var e = a.parentNode.parentNode.getElementsByTagName('CODE')[0];

	if (document.selection)
	{
		var r = document.body.createTextRange();
		r.moveToElementText(e);
		r.select();
	}
	else
	{
		var s = window.getSelection();
		var r = document.createRange();
		r.setStartBefore(e);
		r.setEndAfter(e);
		s.addRange(r);
	}
}

/**
* Play quicktime file by determining it's width/height
* from the displayed rectangle area
*/
function play_qt_file(obj)
{
	var rectangle = obj.GetRectangle();

	if (rectangle)
	{
		rectangle = rectangle.split(',')
		var x1 = parseInt(rectangle[0]);
		var x2 = parseInt(rectangle[2]);
		var y1 = parseInt(rectangle[1]);
		var y2 = parseInt(rectangle[3]);

		var width = (x1 < 0) ? (x1 * -1) + x2 : x2 - x1;
		var height = (y1 < 0) ? (y1 * -1) + y2 : y2 - y1;
	}
	else
	{
		var width = 200;
		var height = 0;
	}

	obj.width = width;
	obj.height = height + 16;

	obj.SetControllerVisible(true);
	obj.Play();
}

/* fungsi untuk ngecheck angka*/
function IsNumeric(sText)

{

   var ValidChars = "0123456789.,";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
}

/* fungsi untuk mengeluarkan confirmasi */
function ConfirmDelete(par1, par2) {     
    var keterangan = par1;
	var isiKeterangan = par2;
	
    answer = confirm('Anda akan menghapus '+keterangan+ ' ' +isiKeterangan+ '.\n\'OK\' untuk menghapus, \'Cancel\' untuk membatalkan.' );
    if(answer !=0) { 
       return true;
    } 
}

function ConfirmBatal(par1, par2) {     
    var keterangan = par1;
	var isiKeterangan = par2;
	
    answer = confirm('Anda akan membatalkan No Faktur '+keterangan+ '.\n\'OK\' untuk proses, \'Cancel\' untuk tidak proses.' );
    if(answer !=0) { 
       return true;
    } 
}


/* fungsi untuk membatasi jumlah karakter ditextarea*/
/*============================================*/
function limitlength(obj, length){
	var maxlength=length
	if (obj.value.length>maxlength)
	obj.value=obj.value.substring(0, maxlength)
}


/* fungsi untuk count down pesan insert/update */
/*========================================*/
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

// function to format a number with separators. returns formatted number.
// num - the number to be formatted
// decpoint - the decimal point character. if skipped, "." is used
// sep - the separator character. if skipped, "," is used
function FormatNumberBy3(num, decpoint, sep) 
{
	
   // check for missing parameters and use defaults if so
   if (arguments.length == 2) {
    sep = ",";
  }
  if (arguments.length == 1) {
    sep = ",";
    decpoint = ".";
  } 
  
  //alert(num+", "+decpoint+", "+sep+", "+arguments.length);
  
  // need a string for operations
  num = num.toString();
  
  // separate the whole number and the fraction if possible
  var a = num.split(decpoint);
  var x = a[0]; // decimal
  var y = a[1]; // fraction
 /* if(y.length > 2){
	  y = y.substring(0,4);
  } */
  //alert(x +"----"+y);
  var z = "";

  
  if (typeof(x) != "undefined") {
    // reverse the digits. regexp works from left to right.
    for (i=x.length-1;i>=0;i--)
      z += x.charAt(i);
    // add seperators. but undo the trailing one, if there
    z = z.replace(/(\d{3})/g, "$1" + sep);
    if (z.slice(-sep.length) == sep)
      z = z.slice(0, -sep.length);
    x = "";
    // reverse again to get back the number
    for (i=z.length-1;i>=0;i--)
      x += z.charAt(i);
    // add the fraction back in, if it was there
    if (typeof(y) != "undefined" && y.length > 0)
      //x += decpoint + y;
		x += "," + y;

	else   
		//x += decpoint + "00";
		x += "" ;
  }
  
  return x; 
}

function testIsNumber(nilai)
{
	var x= nilai.value;
	if(IsNumeric(x) == false)
	{
//		alert("Nilai harus angka");
		nilai.value = "";
	} 
}

function checkIsValidNumber(inputTextId)
{
	var x= document.getElementById(inputTextId).value;
	if(IsNumeric(x) == false)
	{
		//alert("Nilai harus angka");
		document.getElementById(inputTextId).value = "";
	} 
}

/* fungsi untuk ngerubah format number menjadi format ribuan (1.000.000.000) */
/*---------------------------------------------------------------------------*/

function formatNumberOnKeyup(inputTextId)
{
	checkIsValidNumber(inputTextId);
	var nilaiInputText = document.getElementById(inputTextId).value;
	var nilaiInputTextAsli = nilaiInputText.replace(/\./g,"");
	var nilaiInputTextTerformat = FormatNumberBy3(nilaiInputTextAsli, ".", ".");
	//var nilaiInputTextTerformatArr = nilaiInputTextTerformat.split(",");
	//document.getElementById(inputTextId).value = nilaiInputTextTerformatArr[0];
	document.getElementById(inputTextId).value = nilaiInputTextTerformat;
	
}

/* fungsi untuk ngerubah format number menjadi format uang (1.000.000.000,00)*/
/*---------------------------------------------------------------------------*/
function formatNumberOnChange(inputTextId)
{
	checkIsValidNumber(inputTextId);
	var nilaiInputText = document.getElementById(inputTextId).value;
	var nilaiInputTextAsli = nilaiInputText.replace(/\./g,"");
	var nilaiInputTextTerformat = FormatNumberBy3(nilaiInputTextAsli, ".", ".");
	document.getElementById(inputTextId).value = nilaiInputTextTerformat;
}

// fungsi untuk merubah format uang menjadi tanpa format
//-------------------------------------------------------------
function unformatNumber(inputNum)
{
	inputNumArr =inputNum.split(",");
	var hasil = (inputNumArr[0].replace(/\./g,""))*1;
	return hasil;
}

function CalcKeyCode(aChar) {
  var character = aChar.substring(0,1);
  var code = aChar.charCodeAt(0);
  return code;
}

function checkNumber(val) {
  var strPass = val.value;
  var strLength = strPass.length;
  var lchar = val.value.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);

  if ((cCode < 48) || (cCode > 57)) {
    var myNumber = val.value.substring(0, (strLength) - 1);
    val.value = myNumber;
  }
  return false;
}

// Cek Digit NPWP
function checkNPWP(k_npwp1,k_npwp2,k_npwp3,k_npwp4) {
  var strNPWP = k_npwp1.value+""+k_npwp2.value+""+k_npwp3.value;
  var strLength = strNPWP.length;
  var digit_1 = parseInt(strNPWP.substring(0, 1));
  var digit_2 = parseInt(strNPWP.substring(1, 2));
  var digit_3 = parseInt(strNPWP.substring(2, 3));
  var digit_4 = parseInt(strNPWP.substring(3, 4));
  var digit_5 = parseInt(strNPWP.substring(4, 5));
  var digit_6 = parseInt(strNPWP.substring(5, 6));
  var digit_7 = parseInt(strNPWP.substring(6, 7));
  var digit_8 = parseInt(strNPWP.substring(7, 8));
  
  // Cek Digit Genap
  var digit_2_new = 0;

  switch(digit_2){
            case 0:
                digit_2_new = 0;
            break;
            case 1:
                digit_2_new = 2;
            break;
            case 2:
               digit_2_new = 4;
            break;
            case 3:
                digit_2_new = 6;
            break;
            case 4:
                digit_2_new = 8;
            break;
            case 5:
                digit_2_new = 1;
            break;
            case 6:
                digit_2_new = 3;
            break;
            case 7:
                digit_2_new = 5;
            break;
            case 8:
                digit_2_new = 7;
            break;
			case 9:
                digit_2_new = 9;
            break;
        }

  var digit_4_new = 0;
  
	  switch(digit_4){
            case 0:
                digit_4_new = 0;
            break;
            case 1:
                digit_4_new = 2;
            break;
            case 2:
               digit_4_new = 4;
            break;
            case 3:
                digit_4_new = 6;
            break;
            case 4:
                digit_4_new = 8;
            break;
            case 5:
                digit_4_new = 1;
            break;
            case 6:
                digit_4_new = 3;
            break;
            case 7:
                digit_4_new = 5;
            break;
            case 8:
                digit_4_new = 7;
            break;
			case 9:
                digit_4_new = 9;
            break;
        }

  var digit_6_new = 0;
  switch(digit_6){
            case 0:
                digit_6_new = 0;
            break;
            case 1:
                digit_6_new = 2;
            break;
            case 2:
               digit_6_new = 4;
            break;
            case 3:
                digit_6_new = 6;
            break;
            case 4:
                digit_6_new = 8;
            break;
            case 5:
                digit_6_new = 1;
            break;
            case 6:
                digit_6_new = 3;
            break;
            case 7:
                digit_6_new = 5;
            break;
            case 8:
                digit_6_new = 7;
            break;
			case 9:
                digit_6_new = 9;
            break;
        }
  var digit_8_new = 0;
 switch(digit_8){
            case 0:
                digit_8_new = 0;
            break;
            case 1:
                digit_8_new = 2;
            break;
            case 2:
               digit_8_new = 4;
            break;
            case 3:
                digit_8_new = 6;
            break;
            case 4:
                digit_8_new = 8;
            break;
            case 5:
                digit_8_new = 1;
            break;
            case 6:
                digit_8_new = 3;
            break;
            case 7:
                digit_8_new = 5;
            break;
            case 8:
                digit_8_new = 7;
            break;
			case 9:
                digit_8_new = 9;
            break;
        }
		function mod(a,b) {
			var h1=a/b;
			h1=Math.floor(h1);
			h1=h1*b;
			h1=a-h1;
			return h1;
		}
		var sum_digit_new = digit_1 + digit_3 + digit_5 + digit_7 + digit_2_new + digit_4_new + digit_6_new + digit_8_new;
		var cek_digit = mod((80-sum_digit_new),10);
		//alert(cek_digit);

  if(cek_digit != k_npwp4.value){
	  return true;
  }
  else{
	  return false;
  }
}

// konfirmasi reset password
//=======================
function ConfirmReset(par1) {     
    var userid = par1;
	
    answer = confirm('Anda akan mereset password dari user '+userid+ '.\n\'OK\' untuk mereset, \'Cancel\' untuk membatalkan.' );
    if(answer !=0) { 
       return true;
    } 
}