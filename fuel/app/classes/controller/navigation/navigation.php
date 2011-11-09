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
class Controller_Navigation_Navigation extends Controller
{
	private $data = array();

	private $id;

	private function _getParentArray()
	{
		$navis = model_db_navigation::find('all',array(
			'where' => array('parent'=>0)
		));
		$result = array('0'=>__('navigation.none_parent'));

		foreach($navis as $key => $navipoint)
		{
				$result[$key] = $navipoint['label'];
		}
		return $result;
	}

	private function _setSitesToNull($nav_id)
	{
		$sites = model_db_site::find('all',array(
			'where' => array('navigation_id'=>$nav_id)
		));

		foreach($sites as $site)
		{
			$site->navigation_id = 0;
			$site->save();
		}
	}

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][0]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
	}

	public function action_index()
	{
		$data = array();
		$data['label'] = '';
		$data['parent'] = 0;
		$data['parent_array'] = $this->_getParentArray();		
		$data['mode'] = 'add';
		
		$this->data['content'] = View::factory('admin/columns/navigation',$data);
	}

	public function action_add()
	{
		if(isset($_POST['submit']))
		{	
			$label = Input::post('label');
			$nav_point = new model_db_navigation();
			$nav_point->label = (empty($label)) ? __('constants.untitled_element') : $label;
			$nav_point->url_title = Inflector::friendly_title($nav_point->label);
			$nav_point->parent = Input::post('parent');

			if(!empty($nav_point->parent))
				self::_setSitesToNull($nav_point->parent);

			$sort = DB::query('SELECT max(`sort`) + 1 as maxsort FROM ' . Session::get('lang_prefix') . '_navigation')->execute();
			$sort = $sort->as_array();

			$nav_point->sort = ($sort[0]['maxsort'] == null) ? 0 : $sort[0]['maxsort'];

			$nav_point->save();

			$added_nav = model_db_navigation::find('last');
			model_permission::addNavigationToPermissionList($added_nav->id);

			Response::redirect('admin/navigation');
		}
	}

	public function action_edit()
	{		
		$rights = model_permission::getNavigationRights();
		if(!in_array($this->id,$rights['data']) && !$rights['admin'])
			Response::redirect('admin/navigation');

		$nav_point = model_db_navigation::find($this->id);

		if(isset($_POST['submit']))
		{
			$nav_point->label = Input::post('label');
			$nav_point->url_title = Inflector::friendly_title($nav_point->label);
			$nav_point->parent = Input::post('parent');
			if(Input::post('parent') >= 1)
			{
				$sites = model_db_site::find('all',array(
					'where' => array('navigation_id'=>$nav_point->parent)
				));

				foreach($sites as $site)
				{
					$site->navigation_id = 0;
					$site->save();
				}
			}
			$nav_point->save();

			Response::redirect('admin/navigation');
		}

		$data = array();
		$data['label'] = $nav_point->label;
		$data['parent'] = $nav_point->parent;
		$data['parent_array'] = $this->_getParentArray();

		if(isset($data['parent_array'][$this->id]))
			unset($data['parent_array'][$this->id]);

		$data['mode'] = 'edit';

		$this->data['content'] = View::factory('admin/columns/navigation',$data);
	}

	public function action_delete()
	{
		$nav_point = model_db_navigation::find($this->id);

		$sub_points = model_db_navigation::find('all',array(
			'where' => array('parent'=>$this->id)
		));

		if(!empty($nav_point->parent))
			self::_setSitesToNull($this->id);

		if(!empty($sub_points))
		{
			foreach($sub_points as $point)
				$point->delete();
		}

		model_permission::removeNavigationFromPermissionList($this->id);

		$nav_point->delete();

		Response::redirect('admin/navigation');
	}

	public function action_order()
	{
		$order = Input::post('order');

		$last_main_entry = 0;

		foreach($order as $position => $id)
		{
			$row = model_db_navigation::find($id);
			if($row->parent != 0)
			{
				$row->parent = $last_main_entry;
			}
			$row->sort = $position;
			$row->save();

			if($row->parent == 0)
				$last_main_entry = $id;
		}
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}