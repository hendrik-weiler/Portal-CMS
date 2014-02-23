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

pcms.supersearch = function() 
{
	var hash_value = '';

	var type = '';

	var _base_url = _supersearch_base_url;

	var _current_lang_version = 'en';

	var searchterm = '';

	var is_shown = false;

	var current_index = -1;

	var options_for_ajax = {};

	var selector_html = $('<div class="supersearch-container row"></div>');

	function _set_variables(href)
	{
		var href = href.split('#');
		hash_value = href[1];

		var options = href[0].replace('http://','').split('//')[1].split('/');
		type = options[0];
		options_for_ajax['option'] = options[1];
		options_for_ajax['hash'] = hash_value;
		options_for_ajax['searchterm'] = '*';
		options_for_ajax['type'] = type;
		options_for_ajax['language_version'] = _current_lang_version;

		$('#form_supersearch_cat').find('option').removeAttr('selected');
		$('#form_supersearch_cat').find('option[value=' + type + ']').attr('selected','selected');
		$('#form_supersearch_input').val('*');
	}

	function _update_selection()
	{
		$('.supersearch-container .result-block:first').css('borderTop','none');
		$('.supersearch-container .result-block').find('a:first').css('borderTop','none');
		if(current_index != -1)
		{
			$('.supersearch-container a').removeClass('active');
			$('.supersearch-container a').eq(current_index).addClass('active');
		}
	}

	function _create_selector(content)
	{
		var input = $('#form_supersearch_input');
		var width = $('.inputbutton-input').width();
		var width2 = $('.inputbutton-button').width();
		var pos = input.position();

		$(selector_html).remove();
		$(selector_html).css({
			left : pos.left + 15,
			top : pos.top + 15 + 47,
			width : width + width2,
			zIndex : 105
		}).html($(content));
		$('body').prepend(selector_html);
	}

	function _get_results(type)
	{
		$.get(_base_url + 'admin/supersearch/' + type, options_for_ajax,function(data) {
			current_index = -1;
			_create_selector(data);
			_update_selection();
			_set_link_events($(document).find('div.supersearch-container a[href*="open-supersearch"]'));
			$(document).on('mouseover','div.supersearch-container a',function() {
				current_index = $(document).find('div.supersearch-container a').index(this);
			});
		});
	}

	function _link_action(element)
	{
		_set_variables($(element).attr('href'));
		_get_results(type);
		$("html,body").animate({ scrollTop: 0 }, "slow");
	}

	function _set_link_events(selector)
	{
		selector.on('click',function(e) {
			e.preventDefault();
			_link_action($(this));
		});
	}

	function _input_action(e)
	{
		if(e.keyCode == 13 || e.keyCode == 27)
			return false;

		$(selector_html).fadeIn();
		var type = $('#form_supersearch_cat option:selected').val();
		var old_searchterm = searchterm;
		searchterm = $('#form_supersearch_input').val();
		if(searchterm == '')
		{
			$(selector_html).fadeOut(0);
			is_shown = false;
			return;
		}
		is_shown = true;
		options_for_ajax['searchterm'] = searchterm;
		options_for_ajax['type'] = type;
		options_for_ajax['language_version'] = _current_lang_version;

		if(old_searchterm != searchterm)
		_get_results(type);
	}

	this.init_keyboard_shorcuts = function() 
	{
		var keyboard_selector = 'body ,body *, .userarea *, #form_filter, .el-rte';

		var not_in = '#content input, #content textarea';

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+e', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(0).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+t', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(1).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+c', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(2).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+s', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(3).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+n', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(4).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+a', function(){
			$('#form_supersearch_cat').find('option').removeAttr('selected').eq(5).attr('selected','selected');
			$('#form_supersearch_input').focus();
		});

		$(keyboard_selector).not(not_in).bind('keyup', 'shift+r', function(){
			$('#form_supersearch_input').val('').focus();
		});

		$('body ,body *').not(not_in).bind('keyup','down', function(e) {

			if(is_shown)
			{
				current_index++;
				if(current_index >= $('.supersearch-container a').length)
					current_index = 0;

				_update_selection();
			}
		});

		$('body ,body *').not(not_in).bind('keyup','up', function() {
			if(is_shown)
			{
				current_index--;
				if(current_index < 0)
					current_index = $('.supersearch-container a').length-1;

				_update_selection();
			}
		});

		$('body ,body *').bind('keyup','return', function() {
			if(is_shown)
			{
				if(current_index != -1)
				{
					var href = $('.supersearch-container a').eq(current_index).attr('href');
					if( /open-supersearch/i.test(href) )
					{
						_link_action($('.supersearch-container a').eq(current_index));
					}
					else
					{
						var old_href = window.location.href;
						window.location.href = $('.supersearch-container a').eq(current_index).attr('href');

						var new_href = window.location.href.split('#');
						if( /tour=/.test(new_href[1]) && new_href[0] == old_href )
							window.location.reload();
					}
				}
			}
		});
/*
		$('body ,body *').bind('keyup','esc', function() {
			if(is_shown) {
				$(selector_html).fadeOut();
				is_shown = false;
			}
		});
*/
	}

	function supersearch()
	{
		$(function() {
			_current_lang_version = _supersearch_lang_version;
			_set_link_events($('a[href*="open-supersearch"]'));

			$('#form_supersearch_input').bind('keyup',_input_action);
			
			$(document).on('click',':not(.supersearch-container)',function(e) {
				e.stopPropagation();
				if(is_shown) {
					$(selector_html).fadeOut();
					is_shown = false;
				}
			});

		});
	}

	supersearch();
}