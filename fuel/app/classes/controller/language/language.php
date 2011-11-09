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
class Controller_Language_Language extends Controller
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
		if(!$this->data['permission'][4]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

	}

	public function action_index()
	{
		$data = array();

		$data['label'] = '';
		$data['prefix'] = '';
		$data['mode'] = 'add';
		$data['id'] = '';

		$this->data['content'] = View::factory('admin/columns/languages',$data);
	}

	public function action_order()
	{
		$order = Input::post('order');

		foreach($order as $position => $id)
		{
			$row_lang = model_db_language::find($id);
			$row_lang->sort = $position;
			$row_lang->save();
		}
	}

	public function action_add()
	{
		$row = new model_db_language();
		$prefix = Input::post('prefix');

		if(isset($_POST['submit']))
		{
			DB::query('CREATE TABLE `' . $prefix . '_site` LIKE `dummy_site`')->execute();
			DB::query('CREATE TABLE `' . $prefix . '_content` LIKE `dummy_content`')->execute();
			DB::query('CREATE TABLE `' . $prefix . '_navigation` LIKE `dummy_navigation`')->execute();
			DB::query('CREATE TABLE `' . $prefix . '_news` LIKE `dummy_news`')->execute();

			$sort = DB::query('SELECT max(`sort`) + 1 as maxsort FROM languages')->execute();
			$sort = $sort->as_array();

			$row->prefix = $prefix;
			$row->label = Input::post('label');
			$row->sort = ($sort['maxsort'] == null) ? 0 : $sort['maxsort'];
			$row->save();

			File::create_dir(DOCROOT . 'uploads/' , $prefix,0775);
			File::create_dir(DOCROOT . 'uploads/' . $prefix , '/content', 0775);
			File::create_dir(DOCROOT . 'uploads/' . $prefix , '/news',0775);
			File::create_dir(DOCROOT . 'uploads/' . $prefix , '/gallery',0775);

			model_permission::addLangToPermissionList($prefix);

			Response::redirect('admin/language');
		}
	}

	public function action_edit()
	{
		$row = model_db_language::find($this->id);

		$data = array();

		if(isset($_POST['submit']))
		{
			$prefix = Input::post('prefix');
			$row->label = Input::post('label');

			if($row->prefix != $prefix)
			{
				DB::query('RENAME TABLE ' . $row->prefix . '_site TO ' . $prefix . '_site')->execute();
				DB::query('RENAME TABLE ' . $row->prefix . '_content TO ' . $prefix . '_content')->execute();
				DB::query('RENAME TABLE ' . $row->prefix . '_navigation TO ' . $prefix . '_navigation')->execute();
				DB::query('RENAME TABLE ' . $row->prefix . '_news TO ' . $prefix . '_news')->execute();
			}

			File::rename(DOCROOT . 'uploads/' . $row->prefix,DOCROOT . 'uploads/' . $prefix);

			$row->prefix = $prefix;
			$row->save();

			Response::redirect('admin/language');
		}
		
		$data['label'] = $row->label;
		$data['prefix'] = $row->prefix;
		$data['mode'] = 'edit';
		$data['id'] = $row->id;

		$this->data['content'] = View::factory('admin/columns/languages',$data);
	}

	public function action_delete()
	{
		$row = model_db_language::find($this->id);
		DB::query('DROP TABLE ' . $row->prefix . '_site')->execute();
		DB::query('DROP TABLE ' . $row->prefix . '_content')->execute();
		DB::query('DROP TABLE ' . $row->prefix . '_navigation')->execute();
		DB::query('DROP TABLE ' . $row->prefix . '_news')->execute();

		model_permission::removeLangFromPermissionList($this->id);

		$row->delete();
		File::delete_dir(DOCROOT . 'uploads/' . $row->prefix);

		Response::redirect('admin/language');
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}