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
class Controller_Advanced_Accounts extends Controller
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
		if(!$this->data['permission'][5]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');
	}

	public function action_add()
	{
		if(isset($_POST['back']))
			Response::redirect('admin/advanced');

		if(isset($_POST['submit']))
		{
			$username = Input::post('username');
			$password = Input::post('password');
			$admin = Input::post('admin');

			$account = new model_db_accounts();
			$account->username = empty($username) ? __('constants.untitled_element') : $username;
			$account->password = empty($password) ? md5(__('constants.untitled_element')) : md5($password);
			$account->session = 'logout_' . md5(rand(1,500000000));
			$account->admin = empty($admin) ? 0 : 1;
			$account->language = Input::post('language');

			$permissions = array();
			$permissions['language'] = array();
                        
			$languages = model_db_language::getLanguages();

			$permissions = array();
			$permissions['language'] =is_null(Input::post('global_language')) ? array() : Input::post('global_language');
			foreach($languages as $key => $language)
			{
				$permissions['navigation_' . $key] = Input::post('navigation_' . $key);
				$permissions['categories_' . $key] = Input::post('categories_' . $key);

				$permissions['navigation_' . $key] = ($permissions['navigation_' . $key] == null) ? array() : $permissions['navigation_' . $key];
				$permissions['categories_' . $key] = ($permissions['categories_' . $key] == null) ? array() : $permissions['categories_' . $key];
			}

			$account->permissions = Format::factory( $permissions )->to_json();

			$account->save();

			Response::redirect('admin/advanced');
		}

		$data = array();
		$data['mode'] = 'add';
		$data['username'] = '';
		$data['language'] = 0;
		$data['admin'] = array();

		$data['intern'] = array();
		$data['global_language'] = array();

		$this->data['content'] = View::factory('admin/columns/accounts',$data);

	}

	public function action_edit()
	{
		$account = model_db_accounts::find($this->id);
		
		if(isset($_POST['back']))
			Response::redirect('admin/advanced');

		if(isset($_POST['submit']))
		{
			$username = Input::post('username');
			$password = Input::post('password');
			$admin = Input::post('admin');
			$account->username = empty($username) ? __('constants.untitled_element') : $username;
			$account->admin = empty($admin) ? 0 : 1;

			$languages = model_db_language::getLanguages();

			$permissions = array();
			$permissions['language'] =is_null(Input::post('global_language')) ? array() : Input::post('global_language');
			foreach($languages as $key => $language)
			{
				$permissions['navigation_' . $key] = Input::post('navigation_' . $key);
				$permissions['categories_' . $key] = Input::post('categories_' . $key);

				if(!in_array($permissions['categories_' . $key],array(1)))
					$permissions['categories_' . $key][] = 1;

				$permissions['navigation_' . $key] = ($permissions['navigation_' . $key] == null) ? array() : $permissions['navigation_' . $key];
				$permissions['categories_' . $key] = ($permissions['categories_' . $key] == null) ? array() : $permissions['categories_' . $key];
			}

			$account->permissions = Format::factory( $permissions )->to_json();

			if(!empty($password))
				$account->password = md5($password);


			$account->language = Input::post('language');
			$account->save();
			Response::redirect(Uri::current());
		}

		$data = array();
		$data['mode'] = 'edit';
		$data['language'] = $account->language;

		$data['username'] = $account->username;

		$data['admin'] = !$account->admin ? array() : array('checked'=>'checked');

		$permission = Format::factory( $account->permissions,'json' )->to_array();

		$languages = model_db_language::getLanguages();
		
		$data['intern'] = array();

		foreach($languages as $key => $language)
		{
			$data['intern']['navigation_' . $key] = $permission['navigation_' . $key];
			$data['intern']['categories_' . $key] = $permission['categories_' . $key];
		}

		$data['global_language'] = isset($permission['language']) ? $permission['language'] : array();

		$this->data['content'] = View::factory('admin/columns/accounts',$data);
	}

	public function action_delete()
	{
		$account = model_db_accounts::find($this->id);
		$account->delete();

		Response::redirect('admin/advanced');
	}

	public function after($response)
	{
		$this->response->body = View::factory('admin/index',$this->data);
	}
}