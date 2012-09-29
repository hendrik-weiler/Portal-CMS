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
class model_helper_management_content
{
	public static function remove_unneeded_content() 
	{

		$language_version = Session::get('lang_prefix');
		model_db_content::setLangPrefix($language_version);
		model_db_site::setLangPrefix($language_version);
		model_db_news::setLangPrefix($language_version);
		model_db_navigation::setLangPrefix($language_version);
		model_db_navgroup::setLangPrefix($language_version);

		foreach(model_db_navigation::find('all',array(
			'where' => array(array('parent','>',0))
		)) as $navigation)
		{
			$nav = model_db_navigation::find($navigation->parent);
			if(!is_object($nav))
			{
				$navigation->delete();
			}
		}

		foreach(model_db_site::find('all') as $content)
		{
			$site = model_db_navigation::find($content->navigation_id);
			if(!is_object($site))
			{
				$content->delete();
			}
		}

		foreach(model_db_content::find('all') as $content)
		{
			$site = model_db_site::find($content->site_id);
			if(!is_object($site))
			{
				$content->delete();
			}
		}
	}
}