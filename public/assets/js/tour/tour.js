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

pcms.tour = function() 
{
	var _mouse_picture = _tour_mouse_picture;

	var _language = _tour_language.replace('/','').replace('\\','');

	var _next_button = _tour_next_button;

	var _end_tour_button = _tour_end_tour_button;

	var _base_url = _tour_base_url;

	var _cursor = $('<img src="' + _mouse_picture + '" />');

	var _textbox = $('<div class="tour_textbox"><p></p><a href="#" class="btn">' + _next_button + '</a></div></div>');

	var _tour_label = $('<div class="tour_identifier"><div class="tour_label"></div><a href="#" class="btn">' + _end_tour_button + '</a></div>');

	var _xml_data = '';

	var position_top = [];

	var position_left = [];

	var texts = [];

	var conditions = [];

	var targets = [];

	var storyboard = null;

	var next_step = false;

    var debug_mode = false;

	function _get_current_tour()
	{
		return window.location.hash.split('tour=')[1];
	}

	function _get_cursor_location()
	{
		return $(_cursor).position();
	}

	function _refresh_storyline(xml)
	{

        debug_mode = xml.find('debug').length == 1;

		position_top = [];
		position_left = [];
		texts = [];
		conditions = [];
		targets = [];

		xml.find('step').each(function(key, obj) {

            if(debug_mode) console.log('------ Step ' + key + ': -------');

			var target = $(obj).attr('target');
            var position_based_on = $(obj).attr('based_on');

            if($.type(position_based_on) == 'undefined') {
                position_based_on = 'position';
            }

			if($(obj).attr('target') === undefined && $(obj).attr('live_target') !== undefined)
				target = $(obj).attr('live_target');

			if($(target).length == 0 && $(obj).attr('not_found_target') !== undefined)
				target = $(obj).attr('not_found_target');

            var ignore_position = $(obj).attr('mode') == "ignore_position";
            if(debug_mode && ignore_position) console.log('------ Mode=ignore_position ------');

			if($(target).length == 0) {
                if(debug_mode) {
                    console.log("Target: " + target + " not found.");
                }
                return;
            }

            if(position_based_on == 'position') {
                var target_position = $(target).position();
            } else {
                var target_position = $(target).offset();
            }

            if(debug_mode) {
                console.log(target_position);
            }

			if($(target).css('top') != 'auto' && !ignore_position)
			{
				target_position.top = parseFloat( $(target).css('top').replace('px','') );
				target_position.left = parseFloat( $(target).css('left').replace('px','') );
                if(debug_mode) {
                    console.log('------ Positioned ' + key + ': -------');
                    console.log(target_position);
                }
			}
			position_top.push( target_position.top + parseInt($(obj).find('position_difference').attr('top')) );
			position_left.push( target_position.left + parseInt($(obj).find('position_difference').attr('left')) );
			texts.push( $(obj).find('text').text() );
			conditions.push( $(obj).attr('condition') );
			targets.push( target );
		});
	}

	function _make_step(number)
	{
		_refresh_storyline($(_xml_data));

        if(debug_mode) console.log('------ Showing Step ' + number + ': -------');

		var target = targets[number];
		var top = position_top[number];
		var left = position_left[number];
		var text = texts[number];
		var textbox = $(_textbox.clone());
		var condition = conditions[number];
		var live_target = $(storyboard).find('step').eq(number).attr('live_target');
		var redirect_after = $(storyboard).find('step').eq(number).attr('redirect_after');
		var redirect_by_click = $(storyboard).find('step').eq(number).attr('redirect_by_click');
        var execute_javascript = $(storyboard).find('step').eq(number).attr('execute_javascript');
        var javascript = $(storyboard).find('step').eq(number).find('javascript').text();
        var position_based_on = $(storyboard).find('step').attr('based_on');
        var ignore_position = $(storyboard).find('step').eq(number).attr('mode') == "ignore_position";

        if($.type(position_based_on) == 'undefined') {
            position_based_on = 'position';
        }

        if(debug_mode) {
            console.log("1 top=",top);
            console.log("1 left=",left);
        }

		if(redirect_by_click !== undefined)
		{
			$(target).attr('href',_base_url + redirect_by_click);
			$(target).find('a').attr('href',_base_url + redirect_by_click);
			condition = 'clicked';
		}

        if($.type(execute_javascript) == 'undefined') {
            execute_javascript = 'after';
        }

		next_step = false;

		if(live_target !== undefined)
		{
            if(debug_mode) console.log('------ Using live target  -------');

			live_target = $(storyboard).find('step').eq(number).attr('not_found_target') !== undefined ? target : live_target;
            if(position_based_on == 'position') {
			    var new_target = $(live_target).position();
            }
            else {
                var new_target = $(live_target).offset();
            }
            if(!ignore_position) {
                top = new_target.top + parseInt($(storyboard).find('step').eq(number).find('position_difference').attr('top'));
                left = new_target.left + parseInt($(storyboard).find('step').eq(number).find('position_difference').attr('left'));
            } else {
                if(debug_mode) console.log('------ Ignore Position in Live Target');
            }
            if(debug_mode) console.log('------ Position=', new_target);
		}


        if(execute_javascript == 'before') {
            eval(javascript);
        }


        if(debug_mode) {
            console.log("2 top=",top);
            console.log("2 left=",left);
        }

		$(_cursor).animate({
			top : top,
			left : left,
			width : 32,
			height : 32
		},1500, function() {
			var cursor = _get_cursor_location();
			if(number == position_left.length-1 && redirect_after === undefined && redirect_by_click === undefined)
				$(textbox).find('a').text(_end_tour_button);

            if(execute_javascript == 'after') {
                eval(javascript);
            }

			$('body').prepend(textbox);

			if(top >= $(window).height())
			{
				$('html,body').animate({scrollTop : (top/2)},'fast');
			}

			$(textbox).css({
				position : "absolute",
				top : cursor.top + 25,
				left : cursor.left + 25,
				zIndex : 121,
			}).find('p').html(text);

			$(textbox).find('a').addClass('tour_button_disabled');

			if(condition === undefined)
			{
				next_step = true;
				$(textbox).find('a').removeClass('tour_button_disabled');
			}
			if(condition == 'clicked' || condition == 'rightclicked')
			{
				$(target).on('mousedown',function(e) {
					e.preventDefault();
					switch (e.which) 
					{
						case 1:
						if(condition == 'clicked')
						{
							next_step = true;
							$(textbox).find('a').removeClass('tour_button_disabled');
						}
						break;
						case 3:
						if(condition == 'rightclicked')
						{
							next_step = true;
							$(textbox).find('a').removeClass('tour_button_disabled');
						}
						break;
					}

				});
			}

			$(textbox).find('a').click(function(e) {
				e.preventDefault();

				if(!next_step) return;

				if(redirect_after !== undefined)
					window.location.href = _base_url + redirect_after;

				if(number != position_left.length-1)
				{	
					_make_step(number+=1);

					$(this).parent().fadeOut(function() {
						$(this).remove();
					});
				}
				else
				{
					$(_cursor).fadeOut(function() {
						$(this).remove();
					});
					$(this).parent().fadeOut(function() {
						$(this).remove();
					});
					$(_tour_label).fadeOut(function() {
						$(this).remove();
					});
				}
			});
		});
	}

	function _start_tour(xml)
	{
		$('body').prepend(_cursor);
		$(_cursor).css({
			position : 'absolute',
			top : 0,
			left : 0,
			zIndex : 120,
			height : 64,
			width : 64
		});

		storyboard = xml;

		_refresh_storyline(xml);

		_make_step(0);
	}

	function _show_tour_label(xml)
	{
		$(_tour_label).css({
			position : "absolute",
			zIndex : 110,
			top : -50,
			width : 400,
			left : ($(window).width() / 2) + 495 - 450
		}).find('.tour_label').html('<strong>Tour: </strong>'+xml.find('tour').attr('label'));
		$('body').prepend(_tour_label);
		$(_tour_label).animate({
			top : 100
		});
		$(_tour_label).find('a').click(function(e) {
			e.preventDefault();

			$(_cursor).fadeOut(function() {
				$(this).remove();
			});
			$('.tour_textbox').fadeOut(function() {
				$(this).remove();
			});
			$(_tour_label).fadeOut(function() {
				$(this).remove();
			});
		});
	}

	function tour()
	{
		var tour = _get_current_tour();

		if(tour !== undefined)
		{
			$.ajax({
			    type: "GET",
				url: _base_url + 'assets/xml/tour/' + _language + '/' + tour + '.xml',
				dataType: "xml",
				success: function(xml) {
					_xml_data = xml;
					_show_tour_label($(xml));
			 		_start_tour($(xml));
				}
			});
		}
	}

	tour();
}