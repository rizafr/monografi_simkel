if (document.all) var hwidth=114;
else var hwidth=120;
var rwidth=hwidth-50;
var rundate;

Array.prototype.in_array = function (obj) {
  var len = this.length;
  for ( var x = 0 ; x <= len ; x++ ) {
    if ( this[x] == obj ) return true;
  }
  return false;
}
function writedate(theday) {
  var c=0;
  var cc=0;
  var moyear=theday.substring(theday.indexOf("/"),theday.length);
  function mod(a,b) {
    var h1=a/b;
    h1=Math.floor(h1);
    h1=h1*b;
    h1=a-h1;
    return h1;
  }
  var aday=new Array("","M","S","S","R","K","J","S");
  var amon=new Array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  var dlimit=new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);

  var nday=theday.split("/");
  var day=1;
  var month=nday[1]*1;
  var year=nday[2]*1;
  var nm=month+1;
  var ny=year;
  if (nm>12) {
    nm=1;
    ny++;
  }
  var dnext="1/"+nm+"/"+ny;
  var pm=month-1;
  var py=year;
  if (pm<1) {
    pm=12;
    py--;
  }
  var dprev="1/"+pm+"/"+py;
  var maxday=dlimit[month]*1;
  if (month==2) {
    if (mod(year,400)==0) maxday=29;
    else if (mod(year,100)==0) maxday=28;
    else if (mod(year,4)==0) maxday=29;
  }
  var a=Math.floor((14-month)/12);
  var y=year-a;
  var m=month+(12*a)-2;
  var d=day+y+Math.floor(y/4)-Math.floor(y/100)+Math.floor(y/400)+Math.floor(31*m/12);
  d=mod(d,7)+1;
  var prout="<center><table border=0 cellpadding=0 cellspacing=0><tr><td colspan=8>";
  prout+="<table width=100% border=0 cellpadding=0 cellspacing=0>";
  prout+="<tr><td width=10 align='left'><img onclick=\"writedate('"+dprev+"')\" src=\"images/dprev.gif\" class=\"klik\" border=0></td>";
  prout+="<td align='center' style='font-size:10px;font-family:Tahoma'><b>"+amon[month]+" "+year+"</b></td>";
  prout+="<td width=10 align:'right'><img onclick=\"writedate('"+dnext+"')\" src=\"images/dnext.gif\" class=\"klik\" border=0></td></tr>";
  prout+="</table>";
  prout+="</td></tr><tr>";
  for (c=1;c<=7;c++) {
    if (c==1) prout+="<td class=\"boxedm\">"+aday[c]+"</td>";
    else prout+="<td class=\"boxedh\">"+aday[c]+"</td>";
  }
  prout+="</tr><tr>";
  dclass="boxed";
  var p=d;
  for (c=1; c<=maxday; c++) {
    rundate=c+moyear;
    if (c==1) {
      for(cc=1;cc<d;cc++) prout+="<td class=\"boxed\">&nbsp;</td>";
      if (p==1) {
        if (datedarray.in_array(rundate)) dclass="boxedmo";
        else dclass="boxedm";
      }
      else {
        if (datedarray.in_array(rundate)) dclass="boxedo";
        else dclass="boxed";
      }
    }
    else if (p==1) {
      if (datedarray.in_array(rundate)) dclass="boxedmo";
      else dclass="boxedm";
    }
    else {
      if (datedarray.in_array(rundate)) dclass="boxedo";
      else dclass="boxed";
    }
    var nstyle=" ";
    if (today==rundate) nstyle=" style='border:solid 1px #0000ff' ";
    var gabung = c+""+month+""+year;

    if (datedarray.in_array(rundate)) prout+="<td style=\"cursor:pointer\" id=\""+c+"\" class=\""+dclass+"\" onclick=\"balloonText("+gabung+" , this, event);\" style=\"display: block;\">"+c+"</td>";
    else prout+="<td"+nstyle+"id=\""+c+"\" class=\""+dclass+"\">"+c+"</td>";
    if (mod(p,7)==0) {
      prout+="</tr><tr>";
      p=1;
    }
    else {
      p++;
    }
  }
  p=7-p+1;
  if (p<7) {
    for(c=1;c<=p;c++) {
      prout+="<td class=\"boxed\">&nbsp;</td>";
    }
  }
  prout+="</tr><tr><td height=4></td></tr></table>";
  document.getElementById("MYCAL").innerHTML=prout+"</center>";
}
writedate(today);
