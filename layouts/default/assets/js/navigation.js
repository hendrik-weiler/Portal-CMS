$(function() {
	$('.navigation div.navigation-link').click(function() {
		var href = $(this).find('a').attr('href');
		window.location.href = href;
	});
});