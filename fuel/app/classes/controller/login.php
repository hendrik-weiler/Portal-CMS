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
class Controller_Login extends Controller
{

	private $data = array();

	public function before()
	{
		$this->data['error'] = null;
		$this->data['username'] = null;
		Lang::load('login');



		if(model_auth::check() && Uri::segment(2) != 'logout' && Uri::segment(2) != 'clear_cache')
			Response::redirect('admin/navigation');

	}

	public static function clear_cache()
	{
		// CSS Cache Public
		File::delete_dir(DOCROOT . 'cache',true);
		File::create_dir(DOCROOT, 'cache');

		// CSS Cache APPPath
		File::delete_dir(APPPATH . 'cache',true);
		File::create_dir(APPPATH, 'cache');
		Cache::delete_all();
	}

	public function action_clear_cache()
	{
		static::clear_cache();

		Response::redirect(Input::get('return'));
	}

	public function action_index()
	{
		$this->response->body = View::factory('admin/login/index',$this->data);
	}

	public function action_login()
	{
		$val = Validation::factory('my_validation');
		$val->add_field('username', Input::post('username'), 'required|min_length[3]|max_length[100]');
		$val->add_field('password', Input::post('password'), 'required|min_length[3]|max_length[100]');

		$this->data['username'] = Input::post('username');

		if($val->run())
		{
			$user = model_db_accounts::find('first',array(
				'where' => array(
					'username'=>Input::post('username'),
					'password'=>md5(Input::post('password'))
				)
			));

			if(isset($user))
			{
				if(preg_match('#logout_#i',$user->session))
				{
					$session = md5(strtotime(Date::time()) . rand(0,999999));

					Session::create();
					Session::set('session_id', $session);

					$user->session = $session;
					$user->save();
				}
				else
				{
					Session::set('session_id', $user->session);
				}
				Session::set('lang_prefix',model_permission::getValidLanguage());

				Response::redirect('admin/dashboard');
			}
			else 
			{
				$this->data['error'] = '<div class="error">' . __('error') . '</div>';
			}
		}
		else
		{
			$this->data['error'] = '<div class="alert-message error"><p>' . __('error') . '</p></div>';
		}
		$this->response->body = View::factory('admin/login/index',$this->data);
	}

	public function action_logout()
	{
		model_auth::logout();
		Response::redirect('admin');
	}
}