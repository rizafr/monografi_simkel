//// *********************************** ////
//// State of California master template ////
////             Version 1.20            ////
////       Last Updated 03/23/2007       ////
//// *********************************** ////


initNavigation = function() {

	if (document.getElementById) { // Does the browser support the getElementById method?
		navRoot = document.getElementById("nav_list"); // Get main list ul

		if (typeof defaultMainList!="undefined")
			var reMainNav = new RegExp("^" + defaultMainList + "<", "i"); // Regex for finding the index of the default main list item

		for (i=0; i<navRoot.childNodes.length; i++) { // Loop over main list items
			node = navRoot.childNodes[i];
			if (node.nodeName == "LI") {
				if ((typeof defaultMainList!="undefined") && node.firstChild.innerHTML.match(reMainNav)) { // Found default main nav item
					defaultMainListIndex = i;
				} else {

					////// Apply onmouseover and onmouseout event handlers to each main list item //////
					node.onmouseover = function() {
						if (defaultMainListIndex != -1) // Is there a default main list item?
							navRoot.childNodes[defaultMainListIndex].className = "nav_default_off"; // De-activate it
						this.className = "mouse_over"; // Activate the hovered item
					}
					node.onmouseout = function() {
						this.className = ""; // De-activate the hovered item
						if (defaultMainListIndex != -1) // Is there a default main list item?
							navRoot.childNodes[defaultMainListIndex].className = "nav_default_on"; // Activate it
					}
				}
			}
		}

		////// Activate the default main list item //////
		if (defaultMainListIndex != -1)
			navRoot.childNodes[defaultMainListIndex].className = "nav_default_on";

		////// If the search form has radio buttons, make them visible //////
		radioContainer = document.getElementById("radio_container");
		if (radioContainer) {
			//Comment the following line to always hide the radio buttons
			//radioContainer.style.display = "inline";
		}
	}
}

// addLoadEvent by Simon Willison
// Adds a handler to an event without over-riding other handlers

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}

function externalLinks() { 
 if (!document.getElementsByTagName) return; 
 var anchors = document.getElementsByTagName("a"); 
 for (var i=0; i<anchors.length; i++) { 
   var anchor = anchors[i]; 
   if (anchor.getAttribute("href") && 
       anchor.getAttribute("rel") == "external") 
     anchor.target = "_blank"; 
 } 
} 

var defaultMainListIndex = -1; // Initialize the index of the default main list item

addLoadEvent(initNavigation); // Add initNavigation to the page onload event handler

addLoadEvent(externalLinks); //opens external webpage