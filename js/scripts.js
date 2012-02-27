/**
 *	File: js/scripts.js
 *  Author: Oliver Woodings
 *  About: Global javascript file - contains utility functions and some code for the header
 */

var pageArgs = { };

$(document).ready(function() {
	$(".roundInd").tooltip({ offset: [80, 80], relative: true });
	
	$(".semSel label").click(function() { $.get("index.php?get=semsel&sem=" + $(this).html()); location.reload(true); });
	
	//Extract data from url
	var arr = location.search.substring(1, location.search.length).split("&");
    for (var i = 0; i < arr.length; i++) {
        var arg = arr[i].split("=");
        pageArgs[arg[0]] = arg[1];
    }

});


// **** POPUPS **** //
function makePopup(text) {
	$('body').append("<div class='popup'><h3 style='padding:0px 5px 0px 5px;'>"+text+"</h3></div>");
	$('.popup').css('left',function(){
		return document.documentElement.clientWidth + $(window).scrollLeft() - 200;
	});
	$('.popup').css('top',function(){
		return $(window).scrollTop() + 50;
	});
	$('.popup').fadeIn('slow');
	var t=setTimeout(function(){
		$('.popup').fadeOut('slow');
		var r=setTimeout(function(){$('.popup').remove();},1000);
		},3000);
}

//DUMP 
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}