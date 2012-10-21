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
class Controller_Picturemanager_Picturemanager extends Controller
{
	private $_ajax = false;

	private $data = array();

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->data['current_type'] = Uri::segment(3);
		$this->id = $this->param('id');
		Lang::load('picturemanager');

		$path = DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/content';
		if(!is_dir($path . '/original'))
		{
			File::create_dir($path,'original');
			File::create_dir($path,'thumbs');
		}

		$language_version = Session::get('lang_prefix');

		model_db_content::setLangPrefix($language_version);
		model_db_site::setLangPrefix($language_version);
		model_db_news::setLangPrefix($language_version);
		model_db_navigation::setLangPrefix($language_version);
		model_db_navgroup::setLangPrefix($language_version);

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][3]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');
	}

	public function action_own_pictures()
	{
		$path = DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/content';
		$uri_path = 'uploads/' . Session::get('lang_prefix') . '/content';
		$data = array();

		$data['pictures'] = File::read_dir($path . '/thumbs',1);
		$data['pictures'] = array_map(function($value) use ($uri_path) {
			return Uri::create($uri_path . '/thumbs/' . $value);
		}, $data['pictures']);
		$this->data['content'] = View::factory('admin/columns/picturemanager/own_pictures', $data);
	}

	public function action_own_pictures_delete()
	{
		$this->_ajax = true;

		$file = Input::post('file');
		$file = str_replace(Uri::create('/'), DOCROOT, $file);
		var_dump($file);
		if(file_exists($file))
		{
			unlink(str_replace('thumbs','original',$file));
			unlink($file);
		}	
	}

	public function action_own_pictures_add()
	{
		$this->_ajax = true;

		$path = DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/content';

		$config = array(
		    'path' => $path . '/original',
		    'randomize' => true,
		    'auto_rename' => false,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
		);
		Upload::process($config);

		if (Upload::is_valid())
		{
			$options = \Controller_Advanced_Advanced::getOptions();
			Upload::save();
			foreach(Upload::get_files() as $file)
			{
				$resizeObj = new image\resize($path . '/original/' . $file['saved_as']);
				$size = Image::sizes($path . '/original/' . $file['saved_as']);
				
				if($size->width >= 1280)
					$size->width = 1280;

				if($size->height >= 720)
					$size->height = 720;

				$resizeObj -> resizeImage($size->width, $size->height, 'auto');
				$resizeObj -> saveImage($path . '/original/' . $file['saved_as'], 100);

				$resizeObj -> resizeImage(120, 120, 'crop');
				$resizeObj -> saveImage($path . '/thumbs/' . $file['saved_as'], 100);
			}
		}

		Response::redirect('admin/picturemanager/own_pictures');
	}

	public function action_galleries()
	{
		$data = array();

		$data['galleries'] = model_db_content::find('all',array(
			'where' => array('type'=>3)
		));

		$this->data['content'] = View::factory('admin/columns/picturemanager/galleries', $data);
	}

	public function action_news()
	{
		$data = array();

		$data['all_news'] = model_db_news::find('all',array(
			'oder_by' => array('creation_date'=>'DESC')
		));

		$this->data['content'] = View::factory('admin/columns/picturemanager/news', $data);
	}

	public function after($response)
	{
		if(!$this->_ajax)
			$this->response->body = View::factory('admin/columns/picturemanager',$this->data);
	}
}