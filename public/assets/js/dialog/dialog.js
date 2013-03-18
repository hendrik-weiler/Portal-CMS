/*
 * Portal Content Management System
 * Copyright (C) 2011  Hendrik Weiler
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author     Hendrik Weiler
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Hendrik Weiler
 */

if(pcms === undefined)
	var pcms = new Object();

$.pcms_dialog_instances = 0;

pcms.dialog = function(selector, options) 
{
	var instance_id = null;
	var self = this;
	var box = $('');
	var selector = selector;
	var dialog_helper = new Object();

	var _overlay = $('<div class="overlay"></div>');
	var _box = $('<div class="prompt"></div>');
	var _title = $('<h3></h3>');
	var _text = $('<p></p>');
	var _confirm = $('<button></button>');
	var _cancel = $('<button></button>');
	var _options = options;

	this.html = null;

	function dialog()
	{
		$.pcms_dialog_instances+=1;
		instance_id = $.pcms_dialog_instances;
		box = $(_box).clone();

		$(_title)  .html(options.title);
		$(_text)   .html(options.text);
		$(_confirm).html(options.confirm);
		$(_cancel) .html(options.cancel);

		$(box).append(_title);
		$(box).append(_text);
		$(box).append(_confirm);

		if(!_options.no_cancel)
			$(box).append(_cancel);

		dialog_helper.post_data = function(url, data, callback) {
			if(callback === undefined)
			{
				callback = function(response) {
					window.location.reload();
				}
			}
			$.post(url, data, callback);
		}
		dialog_helper.cancel_dialog = function() {
			$(box).fadeOut();
			$(_overlay).fadeOut();
		}
		dialog_helper.set_new_content = function(new_options) {
			if(new_options.title !== undefined)
				$(box).find('h3').html(new_options.title);

			if(new_options.text !== undefined)
				$(box).find('p').html(new_options.text);

			if(new_options.confirm !== undefined)
				$(box).find('button').eq(0).html(new_options.confirm);

			if(new_options.cancel !== undefined)
				$(box).find('button').eq(1).html(new_options.cancel);
		}

		$('body').append(_overlay);
		$('body').append(box);

		$('body').find('.overlay').addClass('overlay_instance_' + instance_id);
		$('body').find('.prompt').addClass('prompt_instance_' + instance_id);

		$('.overlay_instance_' + instance_id).css({
			width : '100%',
			height : $(document).height(),
			position : 'fixed',
			display : 'none'
		});

		$('.overlay_instance_' + instance_id).click(function() {
			if(!_options.no_cancel)
				dialog_helper.cancel_dialog();
		});

		$('.prompt_instance_' + instance_id).css({
			top : ($(window).height() / 2) - $('.prompt_instance_' + instance_id).height(),
			position : 'fixed',
			display : 'none'
		});

	}

	this.initiator = null;

	this.onConfirm = function(dialog_helper, event) 
	{
		dialog_helper.post_data(_options.url, _options.data);
	}

	this.onCancel = function(dialog_helper, event) 
	{
		dialog_helper.cancel_dialog();
	}

	this.onValidation = function(dialog_helper, event) {
		return 1;
	} 

	this.onValidationFail = function(dialog_helper, event) {}

	this.onInitiate = function(dialog_helper, event) {}
	
	this.render = function()
	{
		$(selector).click(function(e) {
			e.preventDefault();
			self.initiator = $(e.currentTarget);
			self.onInitiate(dialog_helper, e);

			$(box).fadeIn();
			$(_overlay).fadeIn();
		});

		dialog();

		self.html = $(box);

		$(box).find('button').eq(0).click(function(e) {
			self.isValidated = self.onValidation(dialog_helper, self);
			if(self.isValidated) {
				self.onConfirm(dialog_helper, self, e, _options);
			}
			else {
				self.onValidationFail(dialog_helper, self);
			}
		});

		$(box).find('button').eq(1).click(function(e) {
			self.onCancel(dialog_helper, self, e,_options);
		});
	}
}