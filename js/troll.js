$(document).ready(function() {
	
	setInterval('troll()', 500);

});

function troll() {
	$("body").prepend('<iframe width="560" height="315" src="http://www.youtube.com/embed/_Uj8p6GNIRY&autostart=true&loop=1" frameborder="0" allowfullscreen></iframe>');
	alert('ByE OlLy!!!!');
}