$(document).ready( function () {
   
  $("div#nav a").hover(hoverIn,function(){});
 
});

function hoverIn() {
     var elm = this;
	 $("div#Debug").empty();

	 var src = document.documentElement.scrollWidth - document.documentElement.clientWidth;	
     if(src > 0) 	 {
	    if( $(this).parent().parent().attr("id") == "pmenu") {
		   var sib   = $(this).siblings("ul").get(0);
		   var geser = -(sib.offsetWidth - elm.offsetWidth)+ 11;
		   $(this).siblings("ul").addClass("mainleft").css("left",geser);
		   //$("div#Debug").append("<br/>GESER: " + geser);		   
		}else {
		   $(this).siblings("ul").addClass("left");
		}   
	 } else {
	    if( $(this).parent().parent().attr("id") == "pmenu") {
		   $(this).siblings("ul").removeClass("mainleft");
		}else {
		   $(this).siblings("ul").removeClass("left");
		}   
	 
	 }
}  


