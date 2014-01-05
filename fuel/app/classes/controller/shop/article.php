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
class Controller_Shop_Article extends Controller
{

	private $data = array();

	private $id;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - Shop';
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!model_permission::currentLangValid())
			Response::redirect('admin/logout');

		Lang::load('tasks');
	}

	public function action_add() 
	{
		if(Input::post('add_article') != '')
		{
			$article = new model_db_article();

			$first_lang = model_db_language::find('first');

			$article->label = json_encode(array(
				$first_lang->prefix => Input::post('label')
			));
			$article->description = json_encode(array(
				$first_lang->prefix => 'Description of ' . Input::post('label')
			));
			$article->price = 0;
			$article->tax_group_id = model_db_tax_group::find('first')->id;
			$article->main_image_index = 0;
			$article->sold_out = 0;
			$article->save();

			$last_article = model_db_article::find('last');
			Response::redirect('admin/shop/articles/edit/' . $last_article->id);
		}

	}

	public function action_delete()
	{
		$article_id = $this->param('id');
		$article = model_db_article::find($article_id);

		File::delete_dir(DOCROOT . 'uploads/shop/article/' . $article->id);

		$article->delete();

		Response::redirect('admin/shop/articles');
	}

	public function action_delete_picture()
	{
		$article_id = $this->param('id');
		$picture_index = $this->param('index');

		$article = model_db_article::find($article_id);

		$images = Format::forge($article->images,'json')->to_array();
		$picture_name = $images[$picture_index];

		unset($images[$picture_index]);
		$article->images = Format::forge($images)->to_json();

		if($article->main_image_index == $picture_index)
			$article->main_image_index = 0;

		$article->save();

		File::delete(DOCROOT . 'uploads/shop/article/' . $article->id . '/original/' . $picture_name);
		File::delete(DOCROOT . 'uploads/shop/article/' . $article->id . '/medium/' . $picture_name);
		File::delete(DOCROOT . 'uploads/shop/article/' . $article->id . '/big/' . $picture_name);
		File::delete(DOCROOT . 'uploads/shop/article/' . $article->id . '/thumbs/' . $picture_name);

		Response::redirect('admin/shop/articles/edit/' . $article->id);

	}

	public function action_edit()
	{
		$id = $this->param('id');
		$data = array();
		$data['article'] = model_db_article::find($id);

		if(Input::post('edit_article') != '')
		{
			$labels = array();
			$descriptions = array();
			foreach ($_POST as $key => $value) {
				if(preg_match('#lang_#i', $key)) {
					$lang_prefix = explode('lang_', $key);
					$labels[$lang_prefix[1]] = $value;
				}
				if(preg_match('#editor_#i', $key)) {
					$lang_prefix = explode('editor_', $key);
					$descriptions[$lang_prefix[1]] = $value;
				}
			}

			$data['article']->label = Format::forge($labels)->to_json();
			$data['article']->description = Format::forge($descriptions)->to_json();
			$data['article']->tax_group_id = Input::post('tax_group');
			$data['article']->price = str_replace(',','.',Input::post('price'));
			$data['article']->article_group_id = Input::post('article_group');
			$data['article']->sold_out = Input::post('sold_out') == '' ? 0 : 1;
			$data['article']->nr = Input::post('nr');
			$data['article']->main_image_index = Input::post('main_image_index');

			if(!is_dir(DOCROOT . 'uploads/shop/article/' . $data['article']->id))
				File::create_dir(DOCROOT . 'uploads/shop/article/' , $data['article']->id,0777);

			if(!is_dir(DOCROOT . 'uploads/shop/article/' . $data['article']->id. '/original'))
				File::create_dir(DOCROOT . 'uploads/shop/article/' , $data['article']->id . '/original',0777);

			if(!is_dir(DOCROOT . 'uploads/shop/article/' . $data['article']->id. '/big'))
				File::create_dir(DOCROOT . 'uploads/shop/article/' , $data['article']->id . '/big',0777);

			if(!is_dir(DOCROOT . 'uploads/shop/article/' . $data['article']->id. '/medium'))
				File::create_dir(DOCROOT . 'uploads/shop/article/' , $data['article']->id . '/medium',0777);

			if(!is_dir(DOCROOT . 'uploads/shop/article/' . $data['article']->id. '/thumbs'))
				File::create_dir(DOCROOT . 'uploads/shop/article/' , $data['article']->id . '/thumbs',0777);

			$config = array(
			    'path' => DOCROOT.'uploads/shop/article/' . $data['article']->id . '/original',
			    'randomize' => true,
			    'auto_rename' => false,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

			empty($data['article']->images) and $data['article']->images = '[]';
			$data['article']->images = json_decode($data['article']->images,true);

			if (Upload::is_valid())
			{
				Upload::save();
				foreach(Upload::get_files() as $file)
				{
					$resizeObj = new image\resize(DOCROOT . 'uploads/shop/article/' . $data['article']->id . '/original/' . $file['saved_as']);
					$size = Image::sizes(DOCROOT . 'uploads/shop/article/' . $data['article']->id . '/original/' . $file['saved_as']);
					
					if($size->width >= 1280)
						$size->width = 1280;

					if($size->height >= 720)
						$size->height = 720;

					$data['article']->images[] = $file['saved_as'];

					$resizeObj -> resizeImage($size->width, $size->height, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/shop/article/' . $data['article']->id . '/big/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage(150, 150, 'crop');
					$resizeObj -> saveImage(DOCROOT . 'uploads/shop/article/' . $data['article']->id . '/medium/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage(50, 50, 'crop');
					$resizeObj -> saveImage(DOCROOT . 'uploads/shop/article/' . $data['article']->id . '/thumbs/' . $file['saved_as'], 100);
				}
			}
			$data['article']->images = Format::forge($data['article']->images)->to_json();
			$data['article']->save();

			Response::redirect(Uri::current());
		}
		

		$labels = Format::forge($data['article']->label, 'json')->to_array();
		
		$data['labels'] = array();
		foreach (model_db_language::find('all') as $lang) {
			!isset($labels[$lang->prefix]) and $labels[$lang->prefix] = '';
			$data['labels'][$lang->prefix] = $labels[$lang->prefix];
		}

		$descriptions = Format::forge($data['article']->description, 'json')->to_array();

		empty($data['article']->images) and $data['article']->images = '[]';
		
		$data['descriptions'] = array();
		foreach (model_db_language::find('all') as $lang) {
			!isset($descriptions[$lang->prefix]) and $descriptions[$lang->prefix] = '';
			$data['descriptions'][$lang->prefix] = $descriptions[$lang->prefix];
		}

		$data['images'] = Format::forge($data['article']->images,'json')->to_array();

		$this->data['content'] = View::factory('admin/shop/columns/article_edit',$data);

	}

	public function action_index()
	{
		$data = array();

		$data['articles'] = model_db_article::find('all');
		
		$this->data['content'] = View::factory('admin/shop/columns/article',$data);
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/shop',$this->data);
	}
}