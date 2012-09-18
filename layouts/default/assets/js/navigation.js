$(function() {
	$('.navi ul li ul').hide();
	$('.navi ul li').hover(function(e) {
		var location = $(this).position();
		$(this).find('ul').css({
			position : "absolute",
			top : location.top + 20,
			left : location.left
		}).show();
	},function() {
		$(this).find('ul').hide();
	});
});