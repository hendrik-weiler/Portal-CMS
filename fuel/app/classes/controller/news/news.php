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
class Controller_News_News extends Controller
{
	private $data = array();

	private $id;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][2]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		model_db_news::setLangPrefix(Session::get('lang_prefix'));
	}

	public function action_index()
	{
		model_db_news::setLangPrefix(Session::get('lang_prefix'));
		$data = array();
		$data['title'] = '';
		
		$this->data['content'] = View::factory('admin/columns/news',$data);
	}

	public function action_add()
	{
		model_db_news::setLangPrefix(Session::get('lang_prefix'));
		if(isset($_POST['submit']))
		{
			$title = Input::post('title');
			$new = new model_db_news();
			$new->title = (empty($title)) ? __('constants.untitled_element') : $title;
			$new->picture = '{}';
			$new->creation_date = \DB::expr('CURRENT_TIMESTAMP');
			$new->attachment = '';
			$new->save();

			Response::redirect('admin/news');
		}
	}

	public function action_edit()
	{		
		model_db_news::setLangPrefix(Session::get('lang_prefix'));
		$news = model_db_news::find($this->id);

		if(isset($_POST['submit']))
		{
			$news->title = Input::post('title');
			$news->text = stripslashes(Input::post('editor'));
			$news->attachment = Input::post('attachment');


		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' . $news->id))
		{
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' , $news->id,0777);
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' , $news->id . '/original',0777);
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' , $news->id . '/big',0777);
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' , $news->id . '/thumb',0777);
		}

		$config = array(
		    'path' => DOCROOT.'uploads/' . Session::get('lang_prefix') . '/news/' . $news->id . '/original',
		    'randomize' => true,
		    'auto_rename' => false,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
		);
		Upload::process($config);

		$options = \Controller_Advanced_Advanced::getOptions();

		if (Upload::is_valid())
		{
			Upload::save();

			$pictures = Format::factory( $news->picture, 'json')->to_array();

			foreach(Upload::get_files() as $file)
			{
				if(isset($pictures[$file['field']]) && file_exists(DOCROOT . $pictures[$file['field']]))
				{
					File::delete(DOCROOT . $pictures[$file['field']]);
				}
				$pictures[$file['field']] = 'uploads/' . Session::get('lang_prefix') . '/news/' . $news->id . '/original/' .  $file['saved_as'];

				$resizeObj = new image\resize(DOCROOT . $pictures[$file['field']]);

				$size = Image::sizes(DOCROOT . $pictures[$file['field']]);
				
				if($size->width >= 1280)
					$size->width = 1280;

				if($size->height >= 720)
					$size->height = 720;

				$resizeObj -> resizeImage($size->width, $size->height, 'auto');
				$resizeObj -> saveImage(DOCROOT . str_replace('original/','big/',$pictures[$file['field']]), 100);

				$resizeObj -> resizeImage($options['news_thumbs_width'], $options['news_thumbs_height'], 'crop');
				$resizeObj -> saveImage(DOCROOT . str_replace('original/','thumb/',$pictures[$file['field']]), 100);
			}

			$news->picture = Format::factory( $pictures )->to_json();
		}
			$news->save();
			Response::redirect(Uri::current());
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/news');
		}

		$data = array();
		$data['title'] = $news->title;
		$data['text'] = $news->text;
		$data['images'] = Format::factory( $news->picture, 'json')->to_array();
		$data['id'] = $news->id;
		$data['attachment'] = $news->attachment;

		$this->data['content'] = View::factory('admin/columns/news_edit',$data);
	}

	public function action_delete()
	{
		$news = model_db_news::find($this->id);
		$news->delete();

		if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' . $news->id))
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/news/' . $news->id);

		Response::redirect('admin/news');
	}

	public function action_picture()
	{
		$news = model_db_news::find($this->id);
		$picture = Format::factory($news->picture, 'json')->to_array();

		if(isset($picture[$this->param('picture')]) && file_exists(DOCROOT. $picture[$this->param('picture')]))
		{
			File::delete(DOCROOT. $picture[$this->param('picture')]);
			File::delete(DOCROOT. str_replace('original/','big/',$picture[$this->param('picture')]));
			File::delete(DOCROOT. str_replace('original/','thumb/',$picture[$this->param('picture')]));
		}

		unset($picture[$this->param('picture')]);
		$news->picture = Format::factory($picture)->to_json();
		$news->save();

		Response::redirect('admin/news/edit/' . $this->id);
	}

	public function action_order()
	{
		$order = Input::post('order');

		foreach($order as $position => $id)
		{
			$row = model_db_sites::find($id);
			$row->sort = $position;
			$row->save();
		}
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}