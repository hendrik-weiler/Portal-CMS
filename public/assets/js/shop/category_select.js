$(function() {
	$('.shop-category-article-list-article').hover(function() {
		$(this).addClass('selected');
	}, function() {
		$(this).removeClass('selected');
	});
});