$(function() {

	var split_selects = [];

	var first_pos = $('input[name="split_box[]"]').eq(0).offset();

	if(first_pos === undefined || first_pos == null) return false;

	$('div.split_box_container').css({
		top : first_pos.top + 40,
		left : first_pos.left - 395
	});

	$('input[name="split_box[]"]').click(function(e) {

		var pos = $(this).offset();

		$('div.split_box_container').show();
		$('div.split_box_container').animate({
			top : pos.top + 40,
			left : pos.left - 395
		});

		split_selects = [];
		$('input[name="split_box[]"]').each(function(key, obj) {
			if($(obj).is(':checked'))
			split_selects.push($(obj).attr('data-content-id'));
		});

		if(split_selects.length == 1)
		{
			$('div.split_box_container div.entries-text').text('1 ' + _confirm_count_single);
		}
		else
		{
			$('div.split_box_container div.entries-text').text(split_selects.length + ' ' + _confirm_count_multiple);
		}

		if(split_selects.length == 0)
			$('div.split_box_container').fadeOut();

	});

	$('div.split_box_container button[name="split_box_choice_button"]').click(function(e) {
		e.preventDefault();
		$.post(_url + 'admin/sites/classnames/update', {classname : $('select[name="split_box_choice"] option:selected').attr('value'), ids : split_selects}, function(data) {
			window.location.hash = '';
			window.location.reload();
		})
	});
});