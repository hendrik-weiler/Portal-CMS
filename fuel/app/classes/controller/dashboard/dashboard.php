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
class Controller_Dashboard_Dashboard extends Controller
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
		if(!model_permission::currentLangValid())
			Response::redirect('admin/logout');

		Lang::load('tasks');
	}

	public function action_index()
	{

		$data = array();
		try 
		{
			$update = new Controller_Advanced_Update($this->request, $this->response);
			$update->before();
			$data['new_updates'] = @$update->check_for_new_updates();
		}
		catch(Exception $e)
		{
			$data['new_updates'] = array();
		}

		$data['permissions'] = $this->data['permission'];
		$this->data['content'] = View::factory('admin/columns/dashboard',$data);
	}

	public function after($response)
	{

		$this->response->body = View::factory('admin/index',$this->data);
	}
}