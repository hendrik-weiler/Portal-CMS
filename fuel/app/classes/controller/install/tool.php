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
class Controller_Install_Tool extends Controller
{

	private $_data = array();

	private function _getAllLang()
	{
		$html = '<ul>';

		$lang_dir = File::read_dir(APPPATH . '/lang',1);

		foreach($lang_dir as $lang => $under)
		{
			$file = \File::get(APPPATH.'lang/' . $lang . 'description.txt');

			$html .= '<li><a href="' . Uri::create('admin/install/lang/' . str_replace('\\','',$lang)) . '">' . $file->read(true) . '</a></li>';
		}

		$html .= '</ul>';

		return $html;
	}

	private function _updateDBConfig($host,$username,$password,$database,$online_host,$online_username,$online_password,$online_database)
	{
        if(!file_exists(APPPATH . 'config/db.php.bak')) {
            File::copy(APPPATH . 'config/db.php', APPPATH . 'config/db.php.bak');
            $file_content = File::read(APPPATH . 'config/db.php', true);
            $file_content = str_replace(array(
                '[host]',
                '[user]',
                '[pass]',
                '[db]',
                '[online_host]',
                '[online_db]',
                '[online_user]',
                '[online_pass]',
            ),array(
                $host,
                $username,
                $password,
                $database,
                $online_host,
                $online_database,
                $online_username,
                $online_password
            ),$file_content);
            File::update(APPPATH, 'config/db.php', $file_content);
        }
	}

	public function before()
	{
		if(!Session::instance())
			Response::redirect('admin/install');

		Session::delete('lang_prefix');

		if(file_exists(APPPATH . 'INSTALL_TOOL_DISABLED'))
		{
			print 'Install tool disabled';
			exit;
		}

		$this->_data['step_1'] = '';
		$this->_data['step_2'] = '';
		$this->_data['step_3'] = '';
		$this->_data['content'] = '';
		$this->_data['languages'] = $this->_getAllLang();

		Config::set('language',Session::get('lang'));

		Lang::load('install');

	}

    public function cleanUploadsFolder()
    {
        File::delete_dir(DOCROOT.'/uploads',true);
        File::create_dir(DOCROOT, 'uploads', 0777);
        File::create_dir(DOCROOT.'/uploads', 'shop', 0777);
        File::create_dir(DOCROOT.'/uploads/shop', 'article', 0777);
        File::create_dir(DOCROOT.'/uploads/shop', 'logo', 0777);
    }

	public function action_index()
	{
		if(file_exists(APPPATH . 'config/db.php.bak'))
			Response::redirect('admin/install/2');
		
		$this->_data['step_1'] = 'active';

		Session::create();
		Session::set('step',1);

		$data = array();
		$data['errors'] = Session::get('error');

		$this->_data['content'] = View::factory('install/content/step_1',$data);
	}

	public function action_lang()
	{
		Session::set('lang',$this->param('lang'));
		Response::redirect('admin/install/' . Session::get('step'));
	}

	public function action_step2()
	{
		if(isset($_POST['submit']))
		{
			$link = mysqli_connect(Input::post('host'),Input::post('username'), Input::post('password'));
			if ($link->connect_error)
			{
			    Session::set('error',__('steps.1.error_no_login'));
			    Response::redirect('admin/install/' . Session::get('step'));
			}
            $mysqli = @new mysqli(Input::post('host'),Input::post('username'), Input::post('password'),Input::post('database'));
			if(!$mysqli)
			{
				$create = $link->query('create database ' . Input::post('database'));
				if(!$create)
				{
					Session::set('error',Session::get('steps.1.error_no_login') . __('steps.1.error_no_rights'));
					Response::redirect('admin/install/' . Session::get('step'));
				}
			}
			$this->_updateDBConfig(Input::post('host'),Input::post('username'),Input::post('password'),Input::post('database'),
                                   Input::post('online_host'),Input::post('online_username'),Input::post('online_password'),Input::post('online_database'));
			Session::set('error','');
		}

		Session::set('step',2);
		$this->_data['step_2'] = 'active';
		$data = array();
		$data['errors'] = Session::get('error');

		$this->_data['content'] = View::factory('install/content/step_2',$data);
	}
	
	public function action_step3()
	{
		if(isset($_POST['redirect']))
		{
			@fopen(APPPATH . 'INSTALL_TOOL_DISABLED', "w");
			Session::destroy();
			Response::redirect('admin');
		}

		if(isset($_POST['submit_2']))
		{
			$val = Validation::factory('my_validation');
			$val->add_field('username', Input::post('username'), 'required|min_length[3]');
			$val->add_field('password', Input::post('password'), 'required|min_length[3]');

            if(!is_writable(DOCROOT.'/uploads')) {
                print 'Your project folder needs to be set to 777 (writeable). If you gave them needed rights reload the page.';
                exit;
                return $this->response;
            }

			if ($val->run())
			{
                $this->cleanUploadsFolder();

				Migrate::latest();
		        $account = new model_db_accounts();
		        $account->username = Input::post('username');
		        $account->password = md5(Input::post('password'));
		        $account->session = 'logout_' . md5(rand() * 203939920);
		        $account->admin = true;
		        $account->language = 'en';
		        $account->permissions = '{"language":[],"navigation_1":[],"categories_1":[]}';
		        $account->save();

		        $language = new model_db_language();
		        $language->label = 'English';
		        $language->prefix = 'en';
		        $language->sort = 0;
		        $language->save();

                $group = new model_db_navgroup();
                $group->title = 'Main';
                $group->save();

		        Controller_Advanced_Advanced::initializeOptions();

		        if(is_dir(DOCROOT . 'uploads/en'))
		        {
		        	File::delete_dir(DOCROOT . 'uploads/en');
		        	File::create_dir(DOCROOT . 'uploads', 'en');
		        	File::create_dir(DOCROOT . 'uploads/en', 'news');
		        	File::create_dir(DOCROOT . 'uploads/en', 'content');
		        	File::create_dir(DOCROOT . 'uploads/en', 'flash');
		        	File::create_dir(DOCROOT . 'uploads/en', 'gallery');
		        }
			}
			else
			{
			    Session::set('error',__('steps.2.error_required'));
			    Response::redirect('admin/install/2');
			}
		}

		Session::set('step',3);
		$this->_data['step_3'] = 'active';
		$this->_data['content'] = View::factory('install/content/step_3');
		\model_helper_management_content::remove_unneeded_content();
	}

	public function after($response)
	{
		$this->response->body = View::factory('install/installer',$this->_data);
	}
}