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

pcms.picturemanager = function(options) 
{
	var _button = $('<button class="btn btn-secondary"></button>');

	this.build_button = function(selector) 
	{
		_button.html($(selector).html());
		_button.click(function(ev) {
			var width = $(document).width();
			window.open(_url + 'admin/picturemanager/own_pictures',
						'Portalcms - Picturemanager','status=no,resizable=no,width=900,height=600,top=120, left=' + ((width / 2) - 450) );
			ev.preventDefault();
		});
		$(selector).replaceWith(_button);
	}
}