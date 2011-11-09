<?php
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
 * @copyright  2011 Hendrik Weiler
 */
class model_lang
{
	public static function to_selectbox()
	{
		$lang_dir = File::read_dir(APPPATH . '/lang',1);

		$langs = array();

		foreach($lang_dir as $lang => $under)
		{
			$file = \File::get(APPPATH.'lang/' . $lang . 'description.txt');
			$langs[str_replace('\\','',$lang)] = $file->read(true);
		}

		return $langs;
	}
}