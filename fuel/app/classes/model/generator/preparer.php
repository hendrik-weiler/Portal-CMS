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
class model_generator_preparer extends model_db_site
{
	
	public static $options;

	public static $lang;

	public static $main;

	public static $sub;

	public static $currentSite;

	private static function _getActualSite()
	{
		if(empty(self::$main))
		{
			$main = model_db_navigation::find('first',array(
				'order_by' => array('sort'=>'ASC')
			));
			
			if(!empty($main))
			{
				$site = self::find('first',array(
					'where' => array('navigation_id'=>$main->id)
				));
			}

			if(empty($site))
			{
				$site = self::find('first',array(
					'order_by' => array('sort'=>'ASC')
				));
			}
		}

		if(!empty(self::$main) && empty(self::$sub))
		{
			$main = model_db_navigation::find('first',array(
				'where' => array('url_title'=>self::$main)
			));

			if(!empty($main))
			{
				$site = self::find('first',array(
					'where' => array('navigation_id'=>$main->id)
				));
			}
		}

		if(!empty(self::$main) && !empty(self::$sub))
		{
			if(self::$main != 'news') 
			{
				$main = model_db_navigation::find('first',array(
					'where' => array('url_title'=>self::$main)
				));

				$sub = model_db_navigation::find('first',array(
					'where' => array('parent'=>$main->id)
				));

				if(!empty($main) && !empty($sub))
				{
					if($sub->parent == $main->id)
					{
						$site = self::find('first',array(
							'where' => array('navigation_id'=>$sub->id)
						));
					}
				}
			}
		}

		if(isset($site))
		{
			self::$currentSite = $site;
			return $site;
		}
		else
		{
			return false;
		}
	}

	public static function initialize()
	{
		self::$lang = Uri::segment(1);
		self::$main = Uri::segment(2);
		self::$sub = Uri::segment(3);

		# Parser\Htaccess::check();

		$langSearch = model_db_language::find('first',array(
			'where' => array('prefix' => self::$lang)
		));

		if(empty($langSearch))
			$langSearch = model_db_language::find('first');
		
		self::setLangPrefix($langSearch->prefix);
		model_db_navigation::setLangPrefix($langSearch->prefix);
		model_db_content::setLangPrefix($langSearch->prefix);
		model_db_news::setLangPrefix($langSearch->prefix);
		model_db_navgroup::setLangPrefix($langSearch->prefix);
		self::$lang = $langSearch->prefix;

		self::$options = Controller_Advanced_Advanced::getOptions();

		Config::set('language',self::$lang);

		Lang::load('frontend');

		self::_getActualSite();
	}

	public static function getParentsFromSite($site)
	{
		$main = null;
		$sub = null;

		if($site == null)
			return false;

		$firstSearch = model_db_navigation::find('first',array(
			'where' => array('id'=>$site->navigation_id)
		));

		if($firstSearch->parent != 0)
		{
			$sub = $firstSearch;

			$secondSearch = model_db_navigation::find('first',array(
				'where' => array('id'=>$firstSearch->parent)
			));

			$main = $secondSearch;
		}
		else
		{
			$main = $firstSearch;
		}

		return array(
			'main' => $main,
			'sub' => $sub,
		);
	}
}