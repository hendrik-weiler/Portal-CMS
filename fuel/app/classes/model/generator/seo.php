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
class model_generator_seo
{

	private static function _getSeoData($site)
	{
		$title = (empty($site->site_title) ? $site->label : $site->site_title);
		return '<title>' . $title . '</title>
					<meta name="keywords" content="' . $site->keywords . '">'
			 . '<meta name="description" content="' . $site->description . '">';
	}

	public static function render()
	{
		$site = model_generator_preparer::$currentSite;

		if(!empty($site))
			return self::_getSeoData($site);
	}
}