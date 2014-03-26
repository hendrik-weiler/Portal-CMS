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

	public static $mainLang = '';

	public static $currentSite;

	public static $currentMainNav = null;

	public static $currentSubNav = null;

	public static $isMainLanguage = false;

	public static $isShop = false;

	public static $shopLocation = '';

	public static $shopId = 0;

	public static $publicVariables = array();

	public static function addPublicVariables($array)
	{
		static::$publicVariables = array_merge(static::$publicVariables, $array);
	}

	private static function _redirect_to_start()
	{
		if(static::$isMainLanguage)
		{
			if(Uri::segment(1) != 'news')
			Response::redirect('/');
		}
		else
		{
			if(empty(static::$lang))
			{
				Response::redirect('/');
			}
			else
			{
				if(Uri::segment(2) != 'news')
				Response::redirect(static::$lang);
			}
		}
		
	}

	private static function _get_first_site()
	{
		$site = null;

		$lprefix = static::$lang;
		$lprefix == null and $lprefix = static::$mainLang;

        $lid = model_db_language::prefixToId($lprefix);

        $landing_page = model_db_option::getKey('landing_page');

        empty($landing_page->value) and $landing_page->value = '[]';
        $format = Format::forge($landing_page->value,'json')->to_array();

        if(isset($format[$lid]) && $format[$lid] != 0)
        {
            $site = model_db_site::find($format[$lid]);
        }
        else
        {
        	$nav = model_db_navigation::find('first',array(
        		'order_by' => array('sort'=>'ASC')
        	));

        	if(!is_null($nav)) {

	        	$sub = model_db_navigation::find('first',array(
	        		'where' => array('parent'=>$nav->id),
	        		'order_by' => array('sort'=>'ASC')
	        	));

	        	if(is_object($sub))
	        	{
	        		$id = $sub->id;
	        	}

	        	$id = $nav->id;

	        	$site = model_db_site::find('first',array(
	        		'where' => array('navigation_id'=>$id)
	        	));

        	}
        }
    
        $parents = static::getParentsFromSite($site);

        if(empty($parents['sub']))
        {
        	static::$currentMainNav = $parents['main'];
        }
        else
        {
        	static::$currentMainNav = $parents['main'];
        	static::$currentSubNav = $parents['sub'];
        }

        return $site;
	}

	private static function _get_site_with_main_without_lang()
	{
		$site = null;

		$main = model_db_navigation::find('first',array(
			'where' => array('url_title'=>self::$lang)
		));

		if(!empty($main))
		{
			$site = self::find('first',array(
				'where' => array('navigation_id'=>$main->id)
			));

			static::$currentMainNav = $main;
		}

		return $site;
	}

	private static function _get_site_with_main_with_lang()
	{
		$site = null;

		$main = model_db_navigation::find('first',array(
			'where' => array('url_title'=>self::$main)
		));

		if(!empty($main))
		{
			$site = self::find('first',array(
				'where' => array('navigation_id'=>$main->id)
			));

			static::$currentMainNav = $main;
		}

		return $site;
	}

	private static function _get_site_with_mainsub_without_lang()
	{
		$site = null;

		$main = model_db_navigation::find('first',array(
			'where' => array('url_title'=>self::$lang)
		));

		if(!empty($main))
		{
			$sub = model_db_navigation::find('first',array(
				'where' => array('url_title'=>self::$main)
			));

			if(!empty($sub))
			{
				$site = self::find('first',array(
					'where' => array('navigation_id'=>$sub->id)
				));

				static::$currentSubNav = $sub;
			}

			static::$currentMainNav = $main;
		}

		return $site;
	}

	private static function _get_site_with_submain_with_lang()
	{
		$site = null;

		if(self::$main != 'news') 
		{
            $mains = model_db_navigation::find('all',array(
                    'where' => array('url_title'=>self::$main)
            ));

            $correctMain = null;

            foreach ($mains as $main) 
            {
                $sub = model_db_navigation::find('first',array(
                        'where' => array('parent'=>$main->id, 'url_title'=>self::$sub)
                ));
                if(!empty($sub)) {
                	$correctMain = $main;
                	break;
                }
            }

            static::$currentMainNav = $main;

            if(!empty($main) && !empty($sub))
            {

                if($sub->parent == $main->id)
                {
                    $site = self::find('first',array(
                            'where' => array('navigation_id'=>$sub->id)
                    ));

                  	static::$currentSubNav = $sub;
                }
            }
		}

		return $site;
	}

	private static function _getActualSite()
	{

        if(empty(self::$lang) && empty(self::$main) && empty(self::$sub) && !static::$isShop)
        {

            $site = static::_get_first_site();
            if(is_null($site)) {
            	return false;
            }

        }

        if(!empty(self::$lang) && empty(self::$main) && empty(self::$sub) && !static::$isShop)
        {
        	if(static::$isMainLanguage)
        	{
        		$site = static::_get_site_with_main_without_lang();
        	}
        	else
        	{
        		$site = static::_get_first_site();
        	}
            if(is_null($site)) {
            	return false;
            }
        }

		if(!empty(self::$lang) && !empty(self::$main) && empty(self::$sub) && !static::$isShop)
		{

        	if(static::$isMainLanguage)
        	{
        		$site = static::_get_site_with_mainsub_without_lang();
        	}
        	else
        	{
        		$site = static::_get_site_with_main_with_lang();
        	}

		}

		if(!empty(self::$lang) && !empty(self::$main) && !empty(self::$sub) && !static::$isShop)
		{
			if(static::$isMainLanguage)
			{

			}
			else
			{
				$site = static::_get_site_with_submain_with_lang();
			}
		}

		if(!is_object(static::$currentSubNav))
		{
			static::$currentSubNav = new stdClass;
			static::$currentSubNav->url_title = '';
		}

		if(!is_object(static::$currentMainNav))
		{
			static::$currentMainNav = new stdClass;
			static::$currentMainNav->url_title = '';
		}

		if(static::$isShop)
		{

			if(Uri::segment(1) == 'product') 
			{
				$site = static::_get_first_site();
      	
      	static::$shopLocation = 'product';
			}

			if(Uri::segment(2) == 'product')
			{
				if(static::$isMainLanguage) {
					$site = static::_get_site_with_main_without_lang();
				}
				else {
					$site = static::_get_first_site();
				}
	    	
	    	static::$shopLocation = 'product';
			}

			if(Uri::segment(3) == 'product')
			{
				static::$shopLocation = 'product';
				if(static::$isMainLanguage) {
					$site = static::_get_site_with_mainsub_without_lang();
				}
				else {
					$site = static::_get_site_with_main_with_lang();
				}
			}

			if(Uri::segment(4) == 'product')
			{
				static::$shopLocation = 'product';
				$site = static::_get_site_with_submain_with_lang();
			}

			if(static::$main == 'cart') {
				$site = static::_get_first_site();
				static::$shopLocation = 'cart';

			}

			if(static::$main == 'cart' && static::$sub == 'step') {
				$site = static::_get_first_site();
				static::$shopLocation = 'cart->step';

			}
		}

		if(isset($site))
		{
			self::$currentSite = $site;
			return $site;
		}
		else
		{
      static::_redirect_to_start();
			return false;
		}
	}

	private static function _is_shop()
	{
		if(Uri::segment(4) == 'product'
			|| Uri::segment(3) == 'product'
			|| Uri::segment(2) == 'product'
			|| Uri::segment(1) == 'product'
			|| Uri::segment(2) == 'cart' && Uri::segment(3) == 'overview'
			|| Uri::segment(2) == 'cart' && Uri::segment(3) == 'step')
		{
			static::$isShop = true;
		}


	}

	public static function initialize()
	{
		self::$lang = Uri::segment(1);
		self::$main = Uri::segment(2);
		self::$sub = Uri::segment(3);

		$langs = model_db_language::find('all');
		static::$isMainLanguage = true;
		foreach ($langs as $lang) {
			if($lang->prefix == self::$lang)
			{
				static::$isMainLanguage = false;
			}
			if($lang->sort == 0)
			{
				static::$mainLang = $lang->prefix;
			}
		}

		# Parser\Htaccess::check();

		$langSearch = model_db_language::find('first',array(
			'where' => array('prefix' => self::$lang)
		));

		if(empty($langSearch))
		{
			$langSearch = model_db_language::find('first',array(
				'order_by' => array('sort'=>'ASC')
			));
		}	

		if(static::$isMainLanguage)
		{
			$langSearch->prefix = static::$mainLang;
		}
		
		self::setLangPrefix($langSearch->prefix);
		model_db_navigation::setLangPrefix($langSearch->prefix);
		model_db_content::setLangPrefix($langSearch->prefix);
		model_db_news::setLangPrefix($langSearch->prefix);
		model_db_navgroup::setLangPrefix($langSearch->prefix);

		self::$options = Controller_Advanced_Advanced::getOptions();

		Config::set('language',self::$lang);

		Lang::load('frontend');
		Lang::load('shop');

		static::_is_shop();
		self::_getActualSite();

		// Adding public variables
		$data = array();
		$parameter = array();
		$data['current_language'] = (static::$isMainLanguage) ? static::$mainLang : static::$lang;

		if(is_object(model_generator_preparer::$currentSite))
		{
			$data['content_count'] = count(model_db_content::find('all',array(
				'where' => array('site_id'=>model_generator_preparer::$currentSite->id)
			)));

			
			if(property_exists(static::$currentSubNav, 'parameter'))
			{
				$parameter = Format::forge(static::$currentSubNav->parameter,'json')->to_array();
			}
			else
			{
				$parameter = Format::forge(static::$currentMainNav->parameter,'json')->to_array();
			}
		}

		static::addPublicVariables($data + $parameter);

		$lang = $data['current_language'];

		$layoutpath = APPPATH . '../../layouts/' . model_db_option::getKey('layout')->value . '/lang';
		$langfilepath = $layoutpath . '/' . $lang . '/frontend.php';
		if(file_exists($langfilepath)) {
			Lang::load_path($langfilepath);
		}

        $langfilepath2 = $layoutpath . '/' . $lang . '/shop.php';
        if(file_exists($langfilepath2)) {
            Lang::load_path($langfilepath2);
        }
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

		if(!empty($firstSearch))
		{
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
		}
		else
		{
			$main = null;
		}

		return array(
			'main' => $main,
			'sub' => $sub,
		);
	}
}