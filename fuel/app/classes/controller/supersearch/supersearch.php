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
class Controller_Supersearch_Supersearch extends Controller
{

	private $data = array();

	private $id;

	private $searchterms = array();

	private $hash_value = '';

	private $option = '';

	private $type = array();

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!model_permission::currentLangValid())
			Response::redirect('admin/logout');

		Lang::load('admin');
		Lang::load('tasks');

		$this->searchterms = explode(' ', str_replace('*','',Input::get('searchterm')));
		$this->type = Input::get('type');
		$this->hash_value = Input::get('hash') == '' ? '' : '#' . Input::get('hash');
		$this->option = Input::get('option');
	}

	private function _filter_db_results($current_results, $search)
	{
		$end_results = array();

		foreach($current_results as $result)
		{
			$counter = 0;
			for($i = 0; $i < count($this->searchterms); $i++)
			{
				foreach($search as $column)
				{
					if(preg_match('#' . $this->searchterms[$i] . '#i', $result->$column))
					{
						$counter++;
					}
				}

				if(count($this->searchterms) == $counter)
					$end_results[] = $result;
			}
		}

		return $end_results;
	}

	private function _filter_task_results()
	{
		$end_results = array();
			
		foreach(__('questions') as $key => $result)
		{
			$counter = 0;

			for($i = 0; $i < count($this->searchterms); $i++)
			{
				if(preg_match('#' . $this->searchterms[$i] . '#i', $result))
				{
					$counter++;
				}

				if(count($this->searchterms) == $counter && preg_match('#shortcut#i',__('question_links_show.' . $key)))
				{
					$end_results[$key] = new \stdClass;
					$end_results[$key]->label = $result;
					$end_results[$key]->link = \Uri::create(__('question_links.' . $key));
				}	
			}
		}

		foreach(__('questions') as $key => $result)
		{
			$counter = 0;

			for($i = 0; $i < count($this->searchterms); $i++)
			{
				if(preg_match('#' . $this->searchterms[$i] . '#i', $result . ' (Tour)'))
				{
					$counter++;
				}

				if(count($this->searchterms) == $counter && preg_match('#tour#i',__('question_links_show.' . $key)))
				{
					$end_results[$key . '_tour'] = new \stdClass;
					$end_results[$key . '_tour']->label = $result . ' (Tour)';
					$end_results[$key . '_tour']->link = \Uri::create(__('question_links.' . $key));
				}	
			}
		}

		return $end_results;
	}

	private function _generate_news_results()
	{
		$results = model_db_news::find('all',array(
			'where' => array(array('title','like','%' . $this->searchterms[0] . '%'))
		));

		return $this->_filter_db_results($results,array('title'));
	}

	private function _generate_accounts_results()
	{
		$results = model_db_accounts::find('all',array(
			'where' => array(array('username','like','%' . $this->searchterms[0] . '%'))
		));

		return $this->_filter_db_results($results,array('username'));
	}

	private function _generate_sites_results()
	{

		if($this->option == 'no_main' || $this->option == 'main_points')
		{
			$navis = model_db_navigation::find('all', array(
				'where' => array(array('label','like','%' . $this->searchterms[0] . '%'))
			));

			$results = array();

			foreach($navis as $navi)
			{	
				$has_subs = model_db_navigation::find('all', array(
					'where' => array('parent' => $navi->id)
				));

				if($this->option == 'main_points')
				{
					if(count($has_subs) != 0)
					{
						$results[] = model_db_site::find('first',array(
							'where' => array('navigation_id' => $navi->id)
						));
					}
				}
				else
				{
					if(count($has_subs) == 0)
					{
						$results[] = model_db_site::find('first',array(
							'where' => array('navigation_id' => $navi->id)
						));
					}
				}
			}
		}
		else
		{
			$results = model_db_site::find('all', array(
				'where' => array(array('label','like','%' . $this->searchterms[0] . '%'))
			));
		}

		return $this->_filter_db_results($results,array('label'));
	}

	private function _generate_content_results()
	{
		$results = model_db_content::find('all',array(
			'where' => array(array('label','like','%' . $this->searchterms[0] . '%'))
		));

		return $this->_filter_db_results($results,array('label'));
	}

	private function _generate_tasks_results()
	{
		return $this->_filter_task_results();
	}

	public function _display_results($results)
	{
		$html = '<div class="result-block row">';

		$html .= '<div class="span3 type">';

		$html .= '<h6>' . __('supersearch.' . $this->type) . '</h6>';

		foreach($results as $result)
			$html .= '<div>&nbsp;</div>';

		$html .= '</div>';

		$html .= '<div class="span10">';

		$html .= '<ul>';

		foreach($results as $result)
		{
			$name = '';

			if(isset($result->title))
				$name = $result->title;
			else if(isset($result->username))
				$name = $result->username;
			else
				$name = $result->label;

			$link = '';
			switch($this->type)
			{
				case 'tasks':
				if(preg_match('#(Tour)#i', $result->label))
					$link = $result->link;
				else
					$link = preg_replace('/\#([\w\=]+)/i','',$result->link);

				break;
				case 'accounts':
				$link = \Uri::create('admin/accounts/edit/' . $result->id);
				break;
				case 'sites':

				$sub_navs = model_db_navigation::find('all',array(
					'where' => array('parent'=>$result->navigation_id)
				));
				if(count($sub_navs) == 0)
					$link = \Uri::create('admin/sites/edit/' . $result->id);
				else
					$link = \Uri::create('admin/navigation/edit/' . $result->id);

				break;
				case 'content':

				$site = model_db_site::find($result->site_id);

				$name = '<span class="content-pre-site">' . $site->label . '/</span>' . $name;

				$link = \Uri::create('admin/content/' . $result->site_id . '/edit/' . $result->id . '/type/' . $result->type);
				break;
				case 'news':
				$link = \Uri::create('admin/news/edit/' . $result->id);
				break;
			}
			
			$html .= '<li><a href="' . $link . $this->hash_value . '">' . $name . '</a></li>';
		}

		if(count($results) == 0)
		{
			$html .= '<li class="nothing_found">' . __('supersearch_results.nothing_found') . '</li>';
		}

		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

	public function action_accounts()
	{
		$results = $this->_generate_accounts_results();
		$this->response->body = $this->_display_results($results);
	}

	public function action_news()
	{
		$results = $this->_generate_news_results();
		$this->response->body = $this->_display_results($results);
	}

	public function action_sites()
	{
		$results = $this->_generate_sites_results();
		$this->response->body = $this->_display_results($results);
	}

	public function action_content()
	{
		$results = $this->_generate_content_results();
		$this->response->body = $this->_display_results($results);
	}

	public function action_tasks()
	{
		$results = $this->_generate_tasks_results();
		$this->response->body = $this->_display_results($results);
	}

	public function action_all()
	{
		$html = '';

		$this->type = 'content';
		$html .= $this->_display_results( $this->_generate_content_results() );

		$this->type = 'sites';
		$html .= $this->_display_results( $this->_generate_sites_results() );

		$this->type = 'news';
		$html .= $this->_display_results( $this->_generate_news_results() );

		$this->type = 'accounts';
		$html .= $this->_display_results( $this->_generate_accounts_results() );

		$this->type = 'tasks';
		$html .= $this->_display_results( $this->_generate_tasks_results() );

		$this->response->body = $html;
	}
}