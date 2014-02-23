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
class Controller_Advanced_Import extends Controller
{
	private $data = array();

	private $_ajax = false;

	private $_base_url = '';

	private $id;

	private $fsock_able;

	public function before()
	{
		if(Uri::segment(4) == 'check') {

			$username = Input::get('username');
			$password = Input::get('password');
			$user = model_db_accounts::find('first',array(
				'where' => array(
					'username'=>$username,
					'password'=>md5($password)
				)
			));

				if(!is_object($user)) {
					$cb = Input::get('callback');
					$result = array('status'=>'FAIL');
					print $cb . '(' . json_encode($result) . ');';
					exit;
				}

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
		}

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

		$language_version = Session::get('lang_prefix');
		model_db_content::setLangPrefix($language_version);
		model_db_site::setLangPrefix($language_version);
		model_db_news::setLangPrefix($language_version);
		model_db_navigation::setLangPrefix($language_version);
		model_db_navgroup::setLangPrefix($language_version);
	}

	public function action_index()
	{
		$data = array();

		$this->data['content'] = View::factory('admin/columns/import',$data);
	}

	public function changeLangPrefix($language_version)
	{
		model_db_content::setLangPrefix($language_version);
		model_db_site::setLangPrefix($language_version);
		model_db_news::setLangPrefix($language_version);
		model_db_navigation::setLangPrefix($language_version);
		model_db_navgroup::setLangPrefix($language_version);
	}

	public function action_check()
	{
		$this->_ajax = true;

		$cb = Input::get('callback');

		$result = array();
		$result['status'] = 'OK';
		$result['version'] = model_about::$version . ' ' . model_about::$status;

		$export = new model_exchange_export();

		$result['accounts'] = array();
		foreach (model_db_accounts::find('all') as $model) {
			$result['accounts'][] = array(
				'name' => $model->username,
				'data' => $export->createDBStringFromModel('account',$model)
			);
		}

		$result['languages'] = array();
		foreach (model_db_language::find('all') as $language) {
			$result['languages'][] = array(
				'name' => $language->label,
				'data' => $export->createDBStringFromModel('language',$language)
			);
			$this->changeLangPrefix($language->prefix);
			$result['news_' . $language->label] = array();
			foreach (model_db_news::find('all') as $model) {
				$result['news_' . $language->label][] = array(
					'name' => $model->title,
					'data' => $export->createDBStringFromModel('news',$model)
				);
			}
			$result['site_' . $language->label] = array();
			foreach (model_db_site::find('all') as $model) {
				$group = model_db_navgroup::find($model->group_id);
				$navigation = model_db_navigation::find($model->navigation_id);
				$result['site_' . $language->label][] = array(
					'name' => $model->label,
					'data' => array(
						$export->createDBStringFromModel('site',$model),
						$export->createDBStringFromModel('group',$group),
						$export->createDBStringFromModel('navigation',$navigation)
					)
				);
			}
		}

		$result['articles'] = array();
		foreach (model_db_article::find('all') as $model) {
			$label = Format::forge($model->label,'json')->to_array();
			$result['articles'][] = array(
				'name' => array_shift($label),
				'data' => $export->createDBStringFromModel('article',$model)
			);
		}

		$result['articlegroup'] = array();
		foreach (model_db_article_group::find('all') as $model) {
			$label = Format::forge($model->label,'json')->to_array();
			$result['articlegroup'][] = array(
				'name' => array_shift($label),
				'data' => $export->createDBStringFromModel('articlegroup',$model)
			);
		}

		$taxes = array();
		foreach (model_db_tax_group::find('all') as $model) {
			$taxes[] = $export->createDBStringFromModel('taxgroup',$model);
		}
		$result['taxgroup'] = array(
			'name' => $model->label,
			'data' => $taxes
		);

		print $cb . '(' . json_encode($result) . ');';
	}

	public function after($response)
	{

		if(!$this->_ajax)
			$this->response->body = View::factory('admin/index',$this->data);
	}
}