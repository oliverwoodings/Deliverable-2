$(document).ready(function() {
	$(".roundInd").tooltip({ offset: [-135, -105] });
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