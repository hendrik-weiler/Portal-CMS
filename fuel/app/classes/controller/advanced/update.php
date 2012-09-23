<?php
/*
 * Portal Content Management System
 * Copyright (C) 2012  Hendrik Weiler
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
 * @copyright  2012 Hendrik Weiler
 */
class Controller_Advanced_Update extends Controller
{
	private $data = array();

	private $_ajax = false;

	private $_base_url = '';

	private $id;

	private $fsock_able;

	public function before()
	{
		model_auth::check_startup();
		model_db_news::setLangPrefix(Session::get('lang_prefix'));
		model_db_content::setLangPrefix(Session::get('lang_prefix'));
		model_db_navgroup::setLangPrefix(Session::get('lang_prefix'));
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');
		$this->fsock_able = function_exists('fsockopen');

		Config::load('updater');

		$this->_base_url = Config::get('url');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][5]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');
	}

	private function _calc_versions(&$updates)
	{
		$updates = array_reverse($updates);
		$current_version = model_about::$version;

		$next_update = false;

		if(!isset($updates['updates']['update'])) return false;

		foreach(array_reverse($updates['updates']['update'],true) as $key => $update)
		{

			$updates['updates']['update'][$key]['done'] = (floatval($update['version']) <= $current_version);

			$updates['updates']['update'][$key]['is_updateable'] = false;

			if(floatval($update['version']) > $current_version && $next_update == false)
			{
				$updates['updates']['update'][$key]['is_updateable'] = true;
				$next_update = true;
			}
		}
	}

	public function check_for_new_updates()
	{
		$results = array();

		if($this->fsock_able)
		{
			$req = new \Sutra\sHTTPRequest('http://' . $this->_base_url . '/' . '/updates.xml', 'GET');
			$req_result = $req->getData();
			$data = Format::forge($req_result,'xml')->to_array();

			if(isset($data['updates']['update']['version']))
			{
				$update = $data['updates']['update'];
				$data['updates']['update'] = array();
				$data['updates']['update'][] = $update;
			}

			$current_date = strtotime ("+23 hour") + (60 * 59);

			$current_version = model_about::$version;

			foreach($data['updates']['update'] as $update)
			{
				is_array($update['release_date']) and $update['release_date'] = time('+1 day');
				$release_time = strtotime($update['release_date'] . ' 00:00:00');

				if($current_date >= $release_time && $update['released'] && floatval($update['version']) > $current_version)
				{
					$results[] = $update;
				}
			}
			
		}

		return $results;
	}

	public function action_index()
	{
		$data = array();

		$data['use_fsock'] = $this->fsock_able;

		$data['update_list'] = array();

		$data['user_lang'] = str_replace(array('/','\\'),'',model_db_accounts::getCol(Session::get('session_id'),'language'));

		if($this->fsock_able)
		{
			$req = new \Sutra\sHTTPRequest('http://' . $this->_base_url . '/' . '/updates.xml', 'GET');
    		$results = $req->getData();
			$data['update_list'] = Format::forge($results,'xml')->to_array();

			if(isset($data['update_list']['updates']['update']['version']))
			{
				$update = $data['update_list']['updates']['update'];
				$data['update_list']['updates']['update'] = array();
				$data['update_list']['updates']['update'][] = $update;
			}

			$this->_calc_versions($data['update_list']);

			$this->data['content'] = View::factory('admin/columns/update',$data);
		}
		else
		{
			$this->data['content'] = View::factory('admin/columns/update_manually',$data);
		}
		
	}

	public function action_execute()
	{
		$this->_ajax = true;

		$update_version = explode(':',Input::post('update_to'));
		$update_version = trim($update_version[1]);

		$filename = Input::post(str_replace('.','_',$update_version) . '_filename');

		if($this->fsock_able)
		{
			$req = new \Sutra\sHTTPRequest('http://' . $this->_base_url . '/' . $filename, 'GET');
			$results = $req->getData();
			file_exists(DOCROOT . '../update.zip') and File::delete(DOCROOT . '../update.zip');
			File::create(DOCROOT . '../', 'update.zip', $results);

			$zip = new ZipArchive;
			if ($zip->open(DOCROOT . '../update.zip') === TRUE) {
			    $zip->extractTo(DOCROOT . '../');
			    $zip->close();

			    if(is_dir(DOCROOT . '../__MACOSX'))
			    	File::delete_dir(DOCROOT . '../__MACOSX');

			    File::delete(DOCROOT . '../update.zip');
			    Migrate::latest();
			    $result = 'success';
			} else {
				$result = 'failure';
			}
		}

		Response::redirect('admin/advanced/update?result=' . $result);
	}

	public function action_execute_manually()
	{
		$this->_ajax = true;

		$config = array(
		    'path' => DOCROOT . '../',
		    'randomize' => true,
		    'ext_whitelist' => array('zip'),
		);

		Upload::process($config);

		if (Upload::is_valid())
		{
		    Upload::save();

		    foreach (Upload::get_files() as $file) 
		    {
				$zip = new ZipArchive;
				if ($zip->open(DOCROOT . '../' . $file['saved_as']) === TRUE) {
				    $zip->extractTo(DOCROOT . '../');
				    $zip->close();

				    if(is_dir(DOCROOT . '../__MACOSX'))
				    	File::delete_dir(DOCROOT . '../__MACOSX');

				    File::delete(DOCROOT . '../' . $file['saved_as']);
				    Migrate::latest();
				    $result = 'success';
				} else {
					$result = 'failure';
				}
		    }
		}
		else
		{
			$result = 'failure';
		}

		Response::redirect('admin/advanced/update?result=' . $result);
	}

	public function after($response)
	{

		if(!$this->_ajax)
			$this->response->body = View::factory('admin/index',$this->data);
	}
}