/****************************************************************************
						  Date Picker version 1.0
						  Valid only from 01/01/1970
						  Release date: 2005.12.30
						  Copyright (c) Regetdesk
*****************************************************************************/
  now=new Date();
  var f;
  var tanggal=now.getDate();
  var bulan=now.getMonth()+1;
  var tahun=now.getYear();
  if (tahun<1970) tahun+=1900;
  var vals;
  var mouseoverbgcolor="#e0e0e0";
  var topcolor="#ffffff";
  var topbgcolor="#808080";
  var nowcolor="#0000ff";
  var nowbgcolor="#0000ff";
  var weekcolor="#ff0000";
  var weekbgcolor="#ffffff";
  var allcolor="#000000";
  var allbgcolor="#ffffff";
  var weekheadcolor="#ffffff";
  var weekheadbgcolor="#ff0000";
  var allheadcolor="#ffffff";
  var allheadbgcolor="#000000";
  var bordercolor="#000000";
  var headbordercolor="#ffffff";
  var buls=new Array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nop","Des");
  var bulans=new Array("","Januari","Pebuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
  var months=new Array("","January","February","March","April","May","June","July","August","September","October","November","December");
  document.write("<style>");
  document.write("  .hov {font-size:9px;background-color:"+allbgcolor+"}");
  document.write("  .nov {font-size:9px;background-color:"+mouseoverbgcolor+"}");
  document.write("</style>");
  function hov(td) {
    td.className="hov";
  }
  function nov(td) {
    td.className="nov";
  }
  function makelist(bl,th) {
    tvals=new Array();
    dte=months[bl]+" 1,"+th.toString();
    nbl=bl+1;
    if (nbl>12) {
      nbl=1;
      nth=th+1;
    }
    else nth=th;
    dtn=months[nbl]+" 0,"+nth.toString();
    dt=new Date(dte);
    dr=new Date(dtn);

    dmax=dr.getDate();
    mg=dt.getDay();

    len=dmax+mg;
    t=1;
    for (c=0;c<50;c++) {
      if ((c>=mg) && (c<len)) {
        val=t;
        t++;
      }
      else val="&nbsp;";
      tvals[c]=val;
    }
    return tvals;
  }
  function weekdraw(n,bln,thn) {
    s=n;
    e=n+7;
    for(c=s;c<e;c++) {
      if (c==s) {
        color=weekcolor;
      }
      else color=allcolor;
      thisthn=now.getYear();
      if (thisthn<1970) thisthn+=1900;
      if ((vals[c]==tanggal) && (bln==now.getMonth()+1) && (thn==thisthn)) {
        color=nowcolor;
      }
      if (vals[c]!="&nbsp;") almanak+="<td class='nov' onmouseover='hov(this)' onmouseout='nov(this)' width=20 height=20 style='border:solid 1px "+bordercolor+"' align='center' onclick='takeit("+vals[c]+","+bln+","+thn+")'><a href='#' style='text-decoration:none;font-family:Verdana;font-size:10px;color:"+color+"' onclick='takeit("+vals[c]+","+bln+","+thn+");return false'>"+vals[c]+"</a></td>";
      else almanak+="<td class='nov' width=20 height=20 style='border:solid 1px "+bordercolor+";font-size:10px' align='center'>"+vals[c]+"</td>";
    }
  }
  function makemo(bln,thn) {
    vals=makelist(bln,thn);
    almanak="<table style='border:solid 1px "+bordercolor+"' border=0 cellpadding=1 cellspacing=0>";
    almanak+="<tr><td bgcolor='"+weekcolor+"' style='color:"+weekbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>M</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>S</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>S</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>R</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>K</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>J</td>";
    almanak+="<td width=20 height=20 bgcolor='"+allcolor+"' style='color:"+allbgcolor+";font-family:Verdana;font-size:10px;border:solid 1px "+headbordercolor+"' align='center'>S</td>";
    almanak+="</tr><tr>";
    weekdraw(0,bln,thn);
    almanak+="</tr><tr>";
    weekdraw(7,bln,thn);
    almanak+="</tr><tr>";
    weekdraw(14,bln,thn);
    almanak+="</tr><tr>";
    weekdraw(21,bln,thn);
    almanak+="</tr><tr>";
    weekdraw(28,bln,thn);
    if (vals[35]!="&nbsp;") {
      almanak+="</tr><tr>";
      weekdraw(35,bln,thn);
    }
    almanak+="</tr></table>";
    return almanak;
  }
  function dprev() {
    bulan--;
    if (bulan<1) {
      bulan=12;
      tahun--;
    }
    if (tahun<1970) {
      bulan=1;
      tahun++;
    }
    else {
      if (document.all) {
        document.all["kalender"].innerHTML=makemo(bulan,tahun);
        document.all["blth"].innerHTML=buls[bulan]+" "+tahun;
      }
      else {
        document.getElementById("kalender").innerHTML=makemo(bulan,tahun);
        document.getElementById("blth").innerHTML=buls[bulan]+" "+tahun;
      }
    }
  }
  function dnext() {
    bulan++;
    if (bulan>12) {
      bulan=1;
      tahun++;
    }
    if (document.all) {
      document.all["kalender"].innerHTML=makemo(bulan,tahun);
      document.all["blth"].innerHTML=buls[bulan]+" "+tahun;
    }
    else {
      document.getElementById("kalender").innerHTML=makemo(bulan,tahun);
      document.getElementById("blth").innerHTML=buls[bulan]+" "+tahun;
    }
  }
  document.write("<DIV id='picker' style='z-index:3;position:absolute;top:-1000;left:-1000'>");
  document.write("<table border=0 cellpadding=0 cellspacing=0>");
  document.write("<tr><td height=20 bgcolor='"+topbgcolor+"' width=5></td><td align='left' bgcolor='"+topbgcolor+"' style='color:"+topcolor+"'><a href='#' onclick='dprev();return false'><img src='images/prev.gif' border=0></a></td><td align='center' bgcolor='"+topbgcolor+"' style='color:"+topcolor+";font-family:Verdana;font-size:11px'><span id='blth'>"+buls[bulan]+" "+tahun+"</span></td><td align='right' bgcolor='"+topbgcolor+"' style='color:"+topcolor+"'><a href='#' onclick='dnext();return false'><img src='images/next.gif' border=0></a></td><td bgcolor='"+topbgcolor+"' width=5></td></tr>");
  document.write("<tr><td colspan=5>");
  document.write("<span id='kalender'>"+makemo(bulan,tahun)+"</span>");
  document.write("</td></tr></table>");
  document.write("</DIV>");

  var ispop=false;
  function takeit(d,m,y) {
    resl=d+" "+bulans[m]+" "+y;
//    eval("document."+f+".dateresult.value=resl");
    document.getElementById(f).value=resl;
    gopopdown();
  }
  function gopopup(p,x,y) {
    yPos = document.getElementById(p).offsetTop;
    tempEl = document.getElementById(p).offsetParent;
    while (tempEl != null) {
      yPos += tempEl.offsetTop;
      tempEl = tempEl.offsetParent;
    }
    xPos = document.getElementById(p).offsetLeft;
    tempEl = document.getElementById(p).offsetParent;
    while (tempEl != null) {
      xPos += tempEl.offsetLeft;
      tempEl = tempEl.offsetParent;
    }
    document.getElementById("picker").style.top=yPos+y;
    document.getElementById("picker").style.left=xPos+x;
  }
  function gopopdown() {
    if (document.all) {
      document.all["picker"].style.pixelLeft=-1000;
      document.all["picker"].style.pixelTop=-1000;
      window.event.cancelBubble=true;
    } 
    else {
      document.getElementById("picker").style.left=-1000;
      document.getElementById("picker").style.top=-1000;
    }
    ispop=false;
  }
  function goassign(fo) {
    f=fo;
  }
  function togle(fo,p,x,y) {
    goassign(fo);
    if (ispop) {
      ispop=false;
      gopopdown();
    }
    else {
      ispop=true;
      gopopup(p,x,y);
    }
  }
  function toglez() {
    document.getElementById("POPDATEVISI").style.visibility="visible";
  }
/****************************************************************************
  USAGE:
  1. include this script in your page
  2. name the input text field with 'uniquename' and the same named id, size '21' and 'readOnly'
  3. make a button beside the input field with onclick tag is togle('inputid','inputname',y,x)
     'inputid' and 'inputname' should be the same value, where y is adjuster to the left and
     x is adjuster to the top.
*****************************************************************************/
