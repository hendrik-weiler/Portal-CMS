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
 * @copyright  2013 Hendrik Weiler
 */

if(pcms === undefined)
	var pcms = new Object();

$.pcms_siteselector_instances = 0;

pcms.siteselector = function(selector, options) 
{
	var instance_id = null;
	$.pcms_siteselector_instances+=1;
	instance_id = $.pcms_siteselector_instances;

	var self = this;
	var box = $('');
	var selector = selector;
	var dialog_helper = new Object();

	var _overlay = $('<div class="overlay"></div>').addClass('overlay_siteselector_instance_' + instance_id);
	var _box = $('<div class="siteselector"></div>').addClass('siteselector_instance_' + instance_id);
	var _title = $('<h3></h3>');
	var _text = $('<div></div>', {'class':'datacontent'});
	var _confirm = $('<button></button>');
	var _cancel = $('<button></button>');

	var _selection = $('<select>');
	var _option = $('<option>');
	var _optgroup = $('<optgroup>');

	var _current_id = '';
	var _current_label = '';

	var _selectorcontent = $('<div>',{'class':'selectorcontent'});
	_selectorcontent.css({
		height : 400,
		overflow : 'auto'
	});

	var _options = options;

	var _data = {};

	_text.append(_selection);
	_text.append(_selectorcontent);

	this.html = null;

	function siteselector()
	{

		box = $(_box).clone();

		$(_title)  .html(options.title);
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

		}

		$('body').append(_overlay);
		$('body').append(box);

		$('.overlay_siteselector_instance_' + instance_id).css({
			width : '100%',
			height : $(document).height(),
			position : 'fixed',
			display : 'none'
		});

		$('.overlay_siteselector_instance_' + instance_id).click(function() {
			if(!_options.no_cancel)
				dialog_helper.cancel_dialog();
		});

		$('.siteselector_instance_' + instance_id).css({
			top : ($(window).height() / 2) - $('.siteselector_instance_' + instance_id).height() /2 ,
			position : 'fixed',
			display : 'none'
		});

	}

	function showNavigation(index) {

		var html = _data.navigations[index];

		_selectorcontent.html(html);

		_selectorcontent.find('a').hover(function() {
			$(this).addClass('hover');
		}, function() {
			$(this).removeClass('hover');
		});

		_selectorcontent.find('a').click(function(e) {
			e.preventDefault();
			_current_id = $(this).attr('data-id');
			_current_label = $(this).html();
			_selectorcontent.find('a').removeClass('selected');
			$(this).addClass('selected');
			self.onValidation();
		});
	}

	this.initiator = null;

	this.onConfirm = function(dialog_helper, event) 
	{
		dialog_helper.cancel_dialog();
	}

	this.onCancel = function(dialog_helper, event) 
	{
		dialog_helper.cancel_dialog();
	}

	this.onValidation = function(dialog_helper, event) {
		var validation = _current_id != 0;
		if (validation) {
			_confirm.css('opacity',1);
		} else {
			_confirm.css('opacity',0.5);
		}
		return validation;
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

		siteselector();

		self.html = $(box);

		$.getJSON(_url + 'admin/siteselector/data', function(data) {
			_data = data;
			$.each(data.langgroups, function(lang, navigations) {
				var group = _optgroup.clone();
				group.attr('label', lang);
				$.each(navigations, function(key, title) {
					var option = _option.clone();
					option.attr('value',key);
					option.html(title);
					group.append(option);
				});
				_selection.append(group);
			});
			showNavigation(0);

			_selection.change(function() {
				var index = $(_selection.find('option')).index($(_selection).find('option:selected'));
				showNavigation(index);
			});
		});

		_confirm.click(function(e) {
			self.isValidated = self.onValidation(dialog_helper, self);
			if(self.isValidated) {
				self.option_id = _current_id;
				self.option_label = _current_label;
				self.onConfirm(dialog_helper, self, e, _options);
			}
			else {
				self.onValidationFail(dialog_helper, self);
			}
		});

		_cancel.click(function(e) {
			e.stopPropagation();
			self.onCancel(dialog_helper, self, e,_options);
		});

		this.onValidation();
	}
}