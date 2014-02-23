$(function() {

	var selector = [];
	selector.push('input[name="color_text"]');
	selector.push('input[name="color_seekbar"]');
	selector.push('input[name="color_loadingbar"]');
	selector.push('input[name="color_seekbarbg"]');
	selector.push('input[name="color_button_out"]');
	selector.push('input[name="color_button_over"]');
	selector.push('input[name="color_button_highlight"]');

	$(selector).each(function(key, value) {

		$(value).spectrum({
			color : $(value).attr('value'),
			showButtons: false,
			showInput: true,
			move : function(color) {
				var c = color.toHexString();
				var color = c;

				if(c.length == 4) {
					color = '#';
					for (var i = 0; i < c.length; i++) {
						color += c[i] + c[i];
					};
				}


				$(this).attr('value',color);
			}

		});

	});

	// ------- save skin dialog

	var save_dialog = new pcms.dialog('button[name=save]', {
		title : _dialog_save_headline,
		text : '',
		confirm : _dialog_save_confirm,
		cancel : _dialog_save_cancel,
		no_cancel : false
	});
	save_dialog.onConfirm = function(helper, self, event) {

		var skinname = self.html.find('input').val();

		var data = { styling : {} };
		data.styling['color_text'] = $('input[name="color_text"]').attr('value');
		data.styling['color_seekbar'] = $('input[name="color_seekbar"]').attr('value');
		data.styling['color_loadingbar'] = $('input[name="color_loadingbar"]').attr('value');
		data.styling['color_seekbarbg'] = $('input[name="color_seekbarbg"]').attr('value');
		data.styling['color_button_out'] = $('input[name="color_button_out"]').attr('value');
		data.styling['color_button_over'] = $('input[name="color_button_over"]').attr('value');
		data.styling['color_button_highlight'] = $('input[name="color_button_highlight"]').attr('value');

		$.get(_save_skin_url + '/' + skinname,data,function(data) {

			helper.cancel_dialog();
			$('form input[name=skin_saved]').attr('value',1);
			$('form').submit();

		});
	}
	save_dialog.onValidation = function(helper, self) {

		return self.html.find('input').val() != '';

	}
	save_dialog.onValidationFail = function(helper, self) {

		self.html.find('input').css({
			border : '1px solid red'
		});

	}
	save_dialog.onInitiate = function(helper, self) {
		var text = $('<input style="width:240px" value="" />');

		helper.set_new_content({
			text : text
		});
	}
	save_dialog.render(); 

	// --------------------

	$('button[name=load]').click(function(e) {
		e.preventDefault();

		var skinname = $('select[name=skin]').find(":selected").attr('value');
		if(skinname == 'none') return;

		$.get(_url + 'player/serve/skin/' + skinname,function(data) {

			$('input[name="color_text"]').attr('value',$(data).find('color_text').text());
			$('input[name="color_seekbar"]').attr('value',$(data).find('color_seekbar').text());
			$('input[name="color_loadingbar"]').attr('value',$(data).find('color_loadingbar').text());
			$('input[name="color_seekbarbg"]').attr('value',$(data).find('color_seekbarbg').text());
			$('input[name="color_button_out"]').attr('value',$(data).find('color_button_out').text());
			$('input[name="color_button_over"]').attr('value',$(data).find('color_button_over').text());
			$('input[name="color_button_highlight"]').attr('value',$(data).find('color_button_highlight').text());

			$('input[name="color_text"]').spectrum("set", $(data).find('color_text').text());
			$('input[name="color_seekbar"]').spectrum("set", $(data).find('color_seekbar').text());
			$('input[name="color_loadingbar"]').spectrum("set", $(data).find('color_loadingbar').text());
			$('input[name="color_seekbarbg"]').spectrum("set", $(data).find('color_seekbarbg').text());
			$('input[name="color_button_out"]').spectrum("set", $(data).find('color_button_out').text());
			$('input[name="color_button_over"]').spectrum("set", $(data).find('color_button_over').text());
			$('input[name="color_button_highlight"]').spectrum("set", $(data).find('color_button_highlight').text());

			$(selector).each(function(key, value) {

				var c = $(value).attr('value');
				var color = '';
				if(c.length == 4) {
					color = '#';
					for (var i = 0; i < c.length; i++) {
						color += c[i] + c[i];
					};
					$(value).attr('value', color);
				}

			});

		});

	});

});