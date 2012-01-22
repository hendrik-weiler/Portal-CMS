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
		if(!model_generator_module::$seo_head)
			return;
			
		$title = (empty($site->site_title) ? $site->label : $site->site_title);
		return '<title>' . stripslashes($title) . '</title>
					<meta name="keywords" content="' . stripslashes($site->keywords) . '">'
			 . '<meta name="description" content="' . $site->description . '">'
			 . '<base href="' . Uri::create('/') . '" />'
			 . '<meta http-equiv="Content-Language" content="' . model_generator_preparer::$lang . '" />'
			 . '<meta name="language" content="' . model_generator_preparer::$lang . '" />'
			 . '<meta name="robots" content="' . stripslashes(model_db_option::getKey('robots')->value) . '" />';
	}

	private static function _getAnalytics()
	{
		if(!model_generator_module::$seo_analytics)
			return;
			
		$id = model_db_option::getKey('analytics_id')->value;
		return "<script>
					    var _gaq=[['_setAccount','" . stripslashes($id) . "'],['_trackPageview']];
					    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
					    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
					    s.parentNode.insertBefore(g,s)}(document,'script'));
					  </script>";
	}

	public static function render($type='all')
	{
		switch($type)
		{
			case 'all':
				$return = '';
				$site = model_generator_preparer::$currentSite;

				if(!empty($site))
					$return .= self::_getSeoData($site);

				$return .= self::_getAnalytics();
				return $return;
			break;
			case 'head':
				$site = model_generator_preparer::$currentSite;

				if(!empty($site))
					return self::_getSeoData($site);
			break;
			case 'analytics':
				return self::_getAnalytics();
			break;
		}
	}
}