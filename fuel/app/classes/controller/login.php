<?php

class Controller_Login extends Controller
{

	private $data = array();

	public function before()
	{
		$this->data['error'] = null;
		$this->data['username'] = null;
		Lang::load('login');



		if(model_auth::check() && Uri::segment(2) != 'logout')
			Response::redirect('admin/navigation');

	}

	public function action_index()
	{
		$this->response->body = View::factory('admin/login/index',$this->data);
	}

	public function action_login()
	{
		$val = Validation::factory('my_validation');
		$val->add_field('username', Input::post('username'), 'required|min_length[3]|max_length[15]');
		$val->add_field('password', Input::post('password'), 'required|min_length[3]|max_length[10]');

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