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
class Controller_Pages_Pages extends Controller
{
	private $data = array();

	private $id;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');
		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
		model_db_content::setLangPrefix(Session::get('lang_prefix'));

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][1]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

	}

	public function action_index()
	{
		$data = array();
		$data['label'] = '';
		$data['redirect'] = '';
		$data['keywords'] = '';
		$data['site_title'] = '';
		$data['description'] = '';
		$data['navigation_id'] = 0;
		$data['mode'] = 'add';
		
		$this->data['content'] = View::factory('admin/columns/sites',$data);
	}

	public function action_add()
	{
		if(isset($_POST['submit']))
		{
			$label = Input::post('label');

			$site = new model_db_site();
			$site->label = empty($label) ? __('constants.untitled_element') : $label;
			$site->url_title = Inflector::friendly_title($site->label);
			$site->site_title = Input::post('site_title');
			$site->redirect = Input::post('redirect');
			$site->keywords = Input::post('keywords');
			$site->navigation_id = Input::post('navigation_id');
			$site->description = Input::post('description');

			$query = DB::query('SELECT MAX( sort ) +1 AS maxsort FROM  `' . Session::get('lang_prefix') . '_site`')->execute();
			$row = $query->as_array();

			$site->sort = ($row[0]['maxsort'] == null) ? 0 : $row[0]['maxsort'];
			$site->save();

			Response::redirect('admin/sites');
		}
	}

	public function action_edit()
	{		
		$perm = model_permission::getNavigationRights();
		$rights = model_permission::getNavsFromSiteId($this->id);
		$label = Input::post('label');
		$proof = array();
		if($rights['main'] != null)
			$proof[] = $rights['main'];

		if($rights['sub'] != null)
			$proof[] = $rights['sub'];
			
		if(!in_array($proof,$perm) && !$perm['admin'])
				Response::redirect('admin/sites');

		$nav_point = model_db_site::find($this->id);

		if(isset($_POST['submit']))
		{
			$nav_point->label = empty($label) ? __('constants.untitled_element') : $label;
			$nav_point->url_title = Inflector::friendly_title($site->label);
			$nav_point->redirect = Input::post('redirect');
			$nav_point->site_title = Input::post('site_title');
			$nav_point->keywords = Input::post('keywords');
			$nav_point->navigation_id = Input::post('navigation_id');
			$nav_point->description = Input::post('description');
			$nav_point->save();

			Response::redirect('admin/sites');
		}

		$data = array();
		$data['label'] = $nav_point->label;
		$data['redirect'] = $nav_point->redirect;
		$data['site_title'] = $nav_point->site_title;
		$data['keywords'] = $nav_point->keywords;
		$data['description'] = $nav_point->description;
		$data['navigation_id'] = $nav_point->navigation_id;
		$data['id'] = $this->id;

		$data['mode'] = 'edit';

		$this->data['content'] = View::factory('admin/columns/sites',$data);
	}

	public function action_delete()
	{
		$nav_point = model_db_site::find($this->id);
		$nav_point->delete();

		$contents = model_db_content::find()->where('site_id',$this->id)->get();

		foreach($contents as $content)
		{
			$content->delete();
			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id);

			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id);
		}

		Response::redirect('admin/sites');
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

	public static function generateUrl($id)
	{
		$site = model_db_site::find($id);
		$main = model_db_navigation::find($site->navigation_id);
		$sub = '';

		if($main->parent != 0)
		{
			$sub = $main->url_title;
			$main = model_db_navigation::find($main->parent);
		}

		return Uri::create(Session::get('lang_prefix') . '/' . $main->url_title . '/' . $sub);
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}