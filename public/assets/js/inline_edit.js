$(function() {

	var lang_text = {
		de : 'Bearbeiten',
		en : 'Edit'
	}

	var edit_button = $('<button>');
	edit_button.css({
		border : '1px solid black',
		background : 'white',
		padding : '10px',
		cursor : 'pointer'
	});

	if(!lang_text[inline_edit_language]) inline_edit_language = 'en';

	edit_button.text(lang_text[inline_edit_language]);

	$('[data-inline-edit-content-id]').each(function(key, obj) {
		var button = edit_button.clone();
		button.attr('data-content-id', $(this).attr('data-inline-edit-content-id'));
		button.attr('data-site-id', $(this).attr('data-inline-edit-site-id'));
		button.attr('data-type-id', $(this).attr('data-inline-edit-type-id'));
		button.click(function() {
			var content_id = $(this).attr('data-content-id');
			var site_id = $(this).attr('data-site-id');
			var type_id = $(this).attr('data-type-id');
			var redirect_url = '?redirect=' + base_url + 'admin/content/' + site_id + '/edit/' + content_id + '/type/' + type_id;
			window.open(base_url + 'admin/inlineedit/lang/change/' + current_language + redirect_url, '_blank');
			window.focus();
		});
		$(this).append(button);
	});

});