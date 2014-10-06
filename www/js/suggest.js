jQuery.autocomplete=function(_1,_2){
var me=this;
var _4=$(_1).attr("autocomplete","off");
if(_2.inputClass){
_4.addClass(_2.inputClass);
}
var _5=document.createElement("div");
var _6=$(_5);
_6.hide().addClass(_2.resultsClass).css("position","absolute");
if(_2.width>0){
_6.css("width",_2.width);
}
$("body").append(_5);
_1.autocompleter=me;
var _7=null;
var _8="";
var _9=-1;
var _a={};
var _b=false;
var _c=false;
var _d=null;
function flushCache(){
_a={};
_a.data={};
_a.length=0;
}
flushCache();
if(_2.data!=null){
var _e="",_f={},row=[];
if(typeof _2.url!="string"){
_2.cacheLength=1;
}
for(var i=0;i<_2.data.length;i++){
row=((typeof _2.data[i]=="string")?[_2.data[i]]:_2.data[i]);
if(row[0].length>0){
_e=row[0].substring(0,1).toLowerCase();
if(!_f[_e]){
_f[_e]=[];
}
_f[_e].push(row);
}
}
for(var k in _f){
_2.cacheLength++;
addToCache(k,_f[k]);
}
}
_4.keydown(function(e){
_d=e.keyCode;
switch(e.keyCode){
case 38:
e.preventDefault();
moveSelect(-1);
break;
case 40:
e.preventDefault();
moveSelect(1);
break;
case 9:
case 13:
if(selectCurrent()){
_4.get(0).blur();
e.preventDefault();
}
break;
default:
_9=-1;
if(_7){
clearTimeout(_7);
}
_7=setTimeout(function(){
onChange();
},_2.delay);
break;
}
}).focus(function(){
_c=true;
}).blur(function(){
_c=false;
hideResults();
});
hideResultsNow();
function onChange(){
if(_d==46||(_d>8&&_d<32)){
return _6.hide();
}
var v=_4.val();
if(v==_8){
return;
}
_8=v;
if(v.length>=_2.minChars){
_4.addClass(_2.loadingClass);
requestData(v);
}else{
_4.removeClass(_2.loadingClass);
_6.hide();
}
}
function moveSelect(_15){
var lis=$("li",_5);
if(!lis){
return;
}
_9+=_15;
if(_9<0){
_9=0;
}else{
if(_9>=lis.size()){
_9=lis.size()-1;
}
}
lis.removeClass("ac_over");
$(lis[_9]).addClass("ac_over");
}
function selectCurrent(){
var li=$("li.ac_over",_5)[0];
if(!li){
var $li=$("li",_5);
if(_2.selectOnly){
if($li.length==1){
li=$li[0];
}
}else{
if(_2.selectFirst){
li=$li[0];
}
}
}
if(li){
selectItem(li);
return true;
}else{
return false;
}
}
function selectItem(li){
if(!li){
li=document.createElement("li");
li.extra=[];
li.selectValue="";
}
var v=$.trim(li.selectValue?li.selectValue:li.innerHTML);
_1.lastSelected=v;
_8=v;
_6.html("");
_4.val(v);
hideResultsNow();
if(_2.onItemSelect){
setTimeout(function(){
_2.onItemSelect(li);
},1);
}
}
function createSelection(_1b,end){
var _1d=_4.get(0);
if(_1d.createTextRange){
var _1e=_1d.createTextRange();
_1e.collapse(true);
_1e.moveStart("character",_1b);
_1e.moveEnd("character",end);
_1e.select();
}else{
if(_1d.setSelectionRange){
_1d.setSelectionRange(_1b,end);
}else{
if(_1d.selectionStart){
_1d.selectionStart=_1b;
_1d.selectionEnd=end;
}
}
}
_1d.focus();
}
function autoFill(_1f){
if(_d!=8){
_4.val(_4.val()+_1f.substring(_8.length));
createSelection(_8.length,_1f.length);
}
}
function showResults(){
var pos=findPos(_1);
var _21=(_2.width>0)?_2.width:_4.width();
_6.css({width:parseInt(_21)+"px",top:(pos.y+_1.offsetHeight)+"px",left:pos.x+"px"}).show();
}
function hideResults(){
if(_7){
clearTimeout(_7);
}
_7=setTimeout(hideResultsNow,200);
}
function hideResultsNow(){
if(_7){
clearTimeout(_7);
}
_4.removeClass(_2.loadingClass);
if(_6.is(":visible")){
_6.hide();
}
if(_2.mustMatch){
var v=_4.val();
if(v!=_1.lastSelected){
selectItem(null);
}
}
}
function receiveData(q,_24){
if(_24){
_4.removeClass(_2.loadingClass);
_5.innerHTML="";
if(!_c||_24.length==0){
return hideResultsNow();
}
if($.browser.msie){
_6.append(document.createElement("iframe"));
}
_5.appendChild(dataToDom(_24));
if(_2.autoFill&&(_4.val().toLowerCase()==q.toLowerCase())){
autoFill(_24[0][0]);
}
showResults();
}else{
hideResultsNow();
}
}
function parseData(_25){
if(!_25){
return null;
}
var _26=[];
var _27=_25.split(_2.lineSeparator);
for(var i=0;i<_27.length;i++){
var row=$.trim(_27[i]);
if(row){
_26[_26.length]=row.split(_2.cellSeparator);
}
}
return _26;
}
function dataToDom(_2a){
var ul=document.createElement("div");
var num=_2a.length;
if((_2.maxItemsToShow>0)&&(_2.maxItemsToShow<num)){
num=_2.maxItemsToShow;
}
for(var i=0;i<num;i++){
var row=_2a[i];
if(!row){
continue;
}
var li=document.createElement("div");
if(_2.formatItem){
li.innerHTML=_2.formatItem(row,i,num);
li.selectValue=row[0];
}else{
li.innerHTML=row[0];
li.selectValue=row[0];
}
var _30=null;
if(row.length>1){
_30=[];
for(var j=1;j<row.length;j++){
_30[_30.length]=row[j];
}
}
li.extra=_30;
ul.appendChild(li);
$(li).hover(function(){
$("div",ul).removeClass("ac_over");
$(this).addClass("ac_over");
_9=$("div",ul).indexOf($(this).get(0));
},function(){
$(this).removeClass("ac_over");
}).click(function(e){
e.preventDefault();
e.stopPropagation();
selectItem(this);
});
}
return ul;
}
function requestData(q){
if(!_2.matchCase){
q=q.toLowerCase();
}
var _34=_2.cacheLength?loadFromCache(q):null;
if(_34){
receiveData(q,_34);
}else{
if((typeof _2.url=="string")&&(_2.url.length>0)){
$.get(makeUrl(q),function(_35){
_35=parseData(_35);
addToCache(q,_35);
receiveData(q,_35);
});
}else{
_4.removeClass(_2.loadingClass);
}
}
}
function makeUrl(q){
var url=_2.url+"?q="+encodeURI(q);
for(var i in _2.extraParams){
url+="&"+i+"="+encodeURI(_2.extraParams[i]);
}
return url;
}
function loadFromCache(q){
if(!q){
return null;
}
if(_a.data[q]){
return _a.data[q];
}
if(_2.matchSubset){
for(var i=q.length-1;i>=_2.minChars;i--){
var qs=q.substr(0,i);
var c=_a.data[qs];
if(c){
var _3d=[];
for(var j=0;j<c.length;j++){
var x=c[j];
var x0=x[0];
if(matchSubset(x0,q)){
_3d[_3d.length]=x;
}
}
return _3d;
}
}
}
return null;
}
function matchSubset(s,sub){
if(!_2.matchCase){
s=s.toLowerCase();
}
var i=s.indexOf(sub);
if(i==-1){
return false;
}
return i==0||_2.matchContains;
}
this.flushCache=function(){
flushCache();
};
this.setExtraParams=function(p){
_2.extraParams=p;
};
this.findValue=function(){
var q=_4.val();
if(!_2.matchCase){
q=q.toLowerCase();
}
var _46=_2.cacheLength?loadFromCache(q):null;
if(_46){
findValueCallback(q,_46);
}else{
if((typeof _2.url=="string")&&(_2.url.length>0)){
$.get(makeUrl(q),function(_47){
_47=parseData(_47);
addToCache(q,_47);
findValueCallback(q,_47);
});
}else{
findValueCallback(q,null);
}
}
};
function findValueCallback(q,_49){
if(_49){
_4.removeClass(_2.loadingClass);
}
var num=(_49)?_49.length:0;
var li=null;
for(var i=0;i<num;i++){
var row=_49[i];
if(row[0].toLowerCase()==q.toLowerCase()){
li=document.createElement("li");
if(_2.formatItem){
li.innerHTML=_2.formatItem(row,i,num);
li.selectValue=row[0];
}else{
li.innerHTML=row[0];
li.selectValue=row[0];
}
var _4e=null;
if(row.length>1){
_4e=[];
for(var j=1;j<row.length;j++){
_4e[_4e.length]=row[j];
}
}
li.extra=_4e;
}
}
if(_2.onFindValue){
setTimeout(function(){
_2.onFindValue(li);
},1);
}
}
function addToCache(q,_51){
if(!_51||!q||!_2.cacheLength){
return;
}
if(!_a.length||_a.length>_2.cacheLength){
flushCache();
_a.length++;
}else{
if(!_a[q]){
_a.length++;
}
}
_a.data[q]=_51;
}
function findPos(obj){
var _53=obj.offsetLeft||0;
var _54=obj.offsetTop||0;
while(obj=obj.offsetParent){
_53+=obj.offsetLeft;
_54+=obj.offsetTop;
}
return {x:_53,y:_54};
}
};
jQuery.fn.autocomplete=function(url,_56,_57){
_56=_56||{};
_56.url=url;
_56.data=((typeof _57=="object")&&(_57.constructor==Array))?_57:null;
_56.inputClass=_56.inputClass||"ac_input";
_56.resultsClass=_56.resultsClass||"ac_results";
_56.lineSeparator=_56.lineSeparator||"\n";
_56.cellSeparator=_56.cellSeparator||"|";
_56.minChars=_56.minChars||1;
_56.delay=_56.delay||400;
_56.matchCase=_56.matchCase||0;
_56.matchSubset=_56.matchSubset||1;
_56.matchContains=_56.matchContains||0;
_56.cacheLength=_56.cacheLength||1;
_56.mustMatch=_56.mustMatch||0;
_56.extraParams=_56.extraParams||{};
_56.loadingClass=_56.loadingClass||"ac_loading";
_56.selectFirst=_56.selectFirst||false;
_56.selectOnly=_56.selectOnly||false;
_56.maxItemsToShow=_56.maxItemsToShow||-1;
_56.autoFill=_56.autoFill||false;
_56.width=parseInt(_56.width,10)||0;
this.each(function(){
var _58=this;
new jQuery.autocomplete(_58,_56);
});
return this;
};
jQuery.fn.autocompleteArray=function(_59,_5a){
return this.autocomplete(null,_5a,_59);
};
jQuery.fn.indexOf=function(e){
for(var i=0;i<this.length;i++){
if(this[i]==e){
return i;
}
}
return -1;
};

