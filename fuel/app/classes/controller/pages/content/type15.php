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
class Controller_Pages_Content_Type15 extends Controller
{
	private $data = array();

	private $id;

	private $content_id;

	private $content;

	private $_ajax = false;

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][1]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		$this->content_id = $this->param('content_id');
		model_db_content::setLangPrefix(Session::get('lang_prefix'));
		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
		$this->content = model_db_content::find($this->content_id);
	}

	public function action_index()
	{
		$data = array();

		$data['content_id'] = $this->content_id;

		$data['article_groups'] = model_db_article_group::find('all');

		empty($this->content->parameter) or $this->content->parameter == 'null' and $this->content->parameter = '[]';
		$data['selected_checkbox'] = Format::forge($this->content->parameter,'json')->to_array();
		
		$this->data['content'] = View::factory('admin/type/shop',$data);
	}

	public function action_edit()
	{
		$this->_ajax = true;

		if(Input::post('back') != '')
		{
			Response::redirect('admin/sites/edit/' . Uri::segment(3));
		}

		Controller_Pages_Pages::update_site($this->content->site_id);

		$labels = array();
		foreach (Input::post('group') as $id) {
			$group = model_db_article_group::find($id);
			$labels[] = $group->label;
		}

		$this->content->label = implode(', ',$labels);
		$this->content->parameter = Format::forge(Input::post('group'))->to_json();
		$this->content->save();

		Response::redirect(substr_replace(Uri::current() ,"",-5));
	}

	public function after($response)
	{
		if(!$this->_ajax)
		$this->response->body = View::factory('admin/index',$this->data);
	}
}