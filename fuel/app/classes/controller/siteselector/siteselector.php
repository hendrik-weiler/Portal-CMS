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
class Controller_Siteselector_Siteselector extends Controller
{

	private $data = array();

	private $id;

	private $searchterms = array();

	private $hash_value = '';

	private $option = array();

	private $type = array();

	private $permissions;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!model_permission::currentLangValid())
			Response::redirect('admin/logout');

	}

	public function action_get_data()
	{

		$result = array();
		$lang_and_groups = array();

        $default_lang = Config::get('language');
        $default_prefix = Session::get('lang_prefix');

		model_generator_module::$navigation = new stdClass;

		foreach (model_db_language::find('all',array(
			'order_by' => array('sort'=>'ASC')
		)) as $lang) {

			model_db_navigation::setLangPrefix($lang->prefix);
			model_db_navgroup::setLangPrefix($lang->prefix);
            Config::set('language',$lang->prefix);
            Session::set('lang_prefix',$lang->prefix);

			$lang_and_groups[$lang->prefix] = array();
		
			foreach (model_db_navgroup::find('all') as $navgroup_obj) {

				$lang_and_groups[$lang->prefix][] = $navgroup_obj->title;

				$navigation = new model_generator_navigation();
				$result[$lang->prefix] = $navigation->render($navgroup_obj->id,true);
			}

		}

        Config::set('language',$default_lang);
        Session::set('lang_prefix',$default_prefix);

		return json_encode(array('langgroups'=>$lang_and_groups,'navigations'=>$result));
	}
	
}