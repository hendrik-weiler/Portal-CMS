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
class model_helper_management_folder
{
	public static function look_for_missing_folders()
	{
		if(!is_dir(DOCROOT . 'uploads'))
			File::create_dir(DOCROOT , 'uploads');

		foreach (model_db_language::find('all') as $lang) 
		{
			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix))
				File::create_dir(DOCROOT . 'uploads' , $lang->prefix);

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/content'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'content');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/news'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'news');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/gallery'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'gallery');

			if(!is_dir(DOCROOT . 'uploads/' . $lang->prefix . '/flash'))
				File::create_dir(DOCROOT . 'uploads/' . $lang->prefix , 'flash');
		}
	}
}