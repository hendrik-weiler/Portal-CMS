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
class Controller_Shop_Tax extends Controller
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
			$group = new model_db_tax_group();
			$group->label = Input::post('label');
			$group->value = Input::post('value');
			$group->save();

			$last_article = model_db_tax_group::find('last');
			Response::redirect('admin/shop/tax/edit/' . $last_article->id);
		}

	}

	public function action_delete()
	{
		$group_id = $this->param('id');
		$group = model_db_tax_group::find($group_id);
		$group->delete();

		Response::redirect('admin/shop/tax');
	}

	public function action_edit()
	{
		$id = $this->param('id');
		$data = array();
		$data['group'] = model_db_tax_group::find($id);

		$labels = array();

		if(Input::post('edit_article') != '')
		{
			$data['group']->label = Input::post('label');
			$data['group']->value = Input::post('value');
			$data['group']->save();

			Response::redirect(Uri::current());
		}

		$this->data['content'] = View::factory('admin/shop/columns/tax_edit',$data);

        if(Input::post('back') != '') {
            Response::redirect('admin/shop/tax');
        }
	}

	public function action_index()
	{
		$data = array();

		$data['taxes'] = model_db_tax_group::find('all');
		
		$this->data['content'] = View::factory('admin/shop/columns/tax',$data);
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}