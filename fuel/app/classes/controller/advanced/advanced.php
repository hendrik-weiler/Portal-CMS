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
class Controller_Advanced_Advanced extends Controller
{
	private $data = array();

	private $id;

	private static $options = array(
		# general
		'news_thumbs_width','news_thumbs_height','gallery_thumbs_width','gallery_thumbs_height',
		'show_last','show_max_token',
		# seo
		'analytics_id','robots',
		# modules
		'module_navigation','module_content','module_seo_head','module_seo_analytics','module_language_switcher',
		# assets
		'asset_jquery','asset_modernizr','asset_colorbox','asset_nivo_slider','asset_swfobject','asset_custom'
	);

	private static $defaultValue = array(
		# general
		'news_thumbs_width' => 160,
		'news_thumbs_height' => 120,
		'gallery_thumbs_width' => 200,
		'gallery_thumbs_height' => 160,
		'show_last' => '3',
		'show_max_token' => '100',
		# seo
		'analytics_id' => 'UA-XXXXXXXX-X',
		'robots' => 'index,follow',
		# modules
		'module_navigation' => '1',
		'module_content' => '1',
		'module_seo_head' => '1',
		'module_seo_analytics' => '0',
		'module_language_switcher' => '0',
		# assets
		'asset_jquery' => 1,
		'asset_modernizr' => 1,
		'asset_colorbox' => 1,
		'asset_nivo_slider' => 1,
		'asset_swfobject' => 1,
		'asset_custom' => 1,
	);

	private static $minValue = array(
		# general
		'news_thumbs_width' => 50,
		'news_thumbs_height' => 50,
		'gallery_thumbs_width' => 50,
		'gallery_thumbs_height' => 50,
		'show_last' => '1',
		'show_max_token' => '50',
		# seo
		'analytics_id' => 'UA-XXXXXXXX-X',
		'robots' => '',
		# modules
		'module_navigation' => '0',
		'module_content' => '0',
		'module_seo_head' => '0',
		'module_seo_analytics' => '0',
		'module_language_switcher' => '0',
		# assets
		'asset_jquery' => 0,
		'asset_modernizr' => 0,
		'asset_colorbox' => 0,
		'asset_nivo_slider' => 0,
		'asset_swfobject' => 0,
		'asset_custom' => 0,
	);

	private static function _resizeAllPictures()
	{
		$settings = self::getOptions();

		$news = model_db_news::find('all');

		foreach($news as $new)
		{
			$pictures = Format::factory( $new->picture ,'json')->to_array();

			if(isset($pictures['picture_1']))
			{
				$resizer = new image\resize(DOCROOT . $pictures['picture_1']);
				$resizer->resizeImage($settings['news_thumbs_width'], $settings['news_thumbs_height'], 'auto');
				$resizer->saveImage(DOCROOT . str_replace('/original','/',$pictures['picture_1']), 100);
			}

			if(isset($pictures['picture_2']))
			{
				$resizer = new image\resize(DOCROOT . $pictures['picture_2']);
				$resizer->resizeImage($settings['news_thumbs_width'], $settings['news_thumbs_height'], 'auto');
				$resizer->saveImage(DOCROOT . str_replace('/original','/',$pictures['picture_2']), 100);
			}
			
			if(isset($pictures['picture_3']))
			{
				$resizer = new image\resize(DOCROOT . $pictures['picture_3']);
				$resizer->resizeImage($settings['news_thumbs_width'], $settings['news_thumbs_height'], 'auto');
				$resizer->saveImage(DOCROOT . str_replace('/original','/',$pictures['picture_3']), 100);
			}
		}

		$contents = model_db_content::find('all');

		foreach($contents as $content)
		{
			if(is_dir(DOCROOT . 'uploads/gallery/' . $content->id))
			{
				foreach(File::read_dir(DOCROOT . 'uploads/gallery/' . $content->id . '/original',1) as $picture)
				{
					$resizer = new image\resize(DOCROOT . 'uploads/gallery/' . $content->id . '/original/' . $picture);
					$resizer->resizeImage($settings['gallery_thumbs_width'], $settings['gallery_thumbs_height'], 'crop');
					$resizer->saveImage(DOCROOT . 'uploads/gallery/' . $content->id . '/thumbs/' . $picture, 100);
				}
			}
		}

	}

	private static function _checkForOptions()
	{
		foreach(self::$options as $option)
		{
			$test = model_db_option::find('first',array(
				'where' => array('key' => $option)
			));

			if(empty($test))
			{
				$newOption = new model_db_option();
				$newOption->key = $option;
				$newOption->value = self::$defaultValue[$option];
				$newOption->save();
			}
			else
			{
				$value = Input::post($option);
				if(!empty($value))
				{
					if(preg_match('#^[0-9]+$#i',$value) && $value < self::$minValue[$option])
						$value = self::$minValue[$option];
						
					$test->value = $value;
					$test->save();
				}
				else if(preg_match('#(module_|asset_)#i',$option) && empty($value))
				{
					$test->value = 0;
					$test->save();
				}
			}
		}
	}

	public static function getOptions()
	{
		$return = array();

		foreach(self::$options as $option)
		{
			$row = model_db_option::find('first',array(
				'where' => array('key' => $option)
			));

			$return[$option] = $row->value;
		}
		return $return;
	}

	public static function initializeOptions()
	{
		self::_checkForOptions();
	}

	public function before()
	{
		model_auth::check_startup();
		model_db_news::setLangPrefix(Session::get('lang_prefix'));
		model_db_content::setLangPrefix(Session::get('lang_prefix'));
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][5]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');
	}

	public function action_index()
	{
		$data = self::getOptions();

		$this->data['content'] = View::factory('admin/columns/advanced',$data);
	}

	public function action_edit()
	{
		self::_resizeAllPictures();
		self::_checkForOptions();

		Response::redirect('admin/advanced');
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}