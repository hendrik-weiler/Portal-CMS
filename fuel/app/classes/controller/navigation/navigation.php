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
class Controller_Navigation_Navigation extends Controller
{
	private $data = array();

	private $_ajax = false;

	private $id;

    private function _set_landing_page($id)
    {
        $lprefix = Session::get('lang_prefix');
        
        $lid = model_db_language::prefixToId($lprefix);
        
        $landing_page = model_db_option::getKey('landing_page');
        
        $format = Format::forge($landing_page->value,'json')->to_array();
        $format[$lid] = $id;
        
        $landing_page->value = Format::forge($format)->to_json();
        $landing_page->save();
    }

	private function _getParentArray()
	{
		if(preg_match('#[0-9]#i',Uri::segment(3)))
			$id = Uri::segment(3);
		else
		{
			$num = Uri::segment(4);
			if(!empty($num))
			{
				$nav = model_db_navigation::find(Uri::segment(4));
				$id = $nav->group_id;
			}
			else
			{
				$nav = model_db_navigation::find('first');
				$id = $nav->group_id;
			}
		}	
		
		$navis = model_db_navigation::find('all',array(
			'where' => array('parent'=>0,'group_id'=>$id)
		));

		$result = array('0'=>__('navigation.none_parent'));

		foreach($navis as $key => $navipoint)
		{
				$result[$key] = $navipoint['label'];
		}
		return $result;
	}

	private function _setSitesToNull($nav_id)
	{
		#$sites = model_db_site::find('all',array(
		#	'where' => array('navigation_id'=>$nav_id)
		#));

		#foreach($sites as $site)
		#{
			#$site->navigation_id = 0;
			#$site->save();
		#}
	}

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][0]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
		model_db_navgroup::setLangPrefix(Session::get('lang_prefix'));

		if(preg_match('#admin/navigation/([0-9]+)#i',Uri::current()) || Uri::segment(3) == '')
		{
			try {

				$search = model_db_navgroup::find('first',array('where'=>array('id'=>Uri::segment(3))));

			}
			catch(Exception $e)
			{
				Controller_Language_Language::add_language(Session::get('lang_prefix'),'',true);
			}

			if(empty($search))
			{
				$search = model_db_navgroup::find('first');
				Response::redirect('admin/navigation/' . $search->id);
			}
		}
	}

	public function action_index()
	{
		$data = array();
		$data['label'] = '';
		$data['parent'] = 0;
		$data['show_sub'] = 0;
                $data['group_id'] = 0;
		$data['parent_array'] = $this->_getParentArray();		
		$data['mode'] = 'add';
		
		$this->data['content'] = View::factory('admin/columns/navigation',$data);
	}

	public function action_add()
	{
		if(isset($_POST['submit']))
		{	
			$label = Input::post('label');
			$nav_point = new model_db_navigation();
			$nav_point->label = (empty($label)) ? __('constants.untitled_element') : $label;
			$nav_point->url_title = model_generator_seo::friendly_title($nav_point->label);
			$nav_point->group_id = Input::post('id');
			$nav_point->show_in_navigation = Input::post('show_in_navigation') == 1;
			$nav_point->parent = Input::post('parent');
			$nav_point->show_sub = 0;
			$nav_point->image_is_shown = Input::post('image_is_shown');

			if(!empty($nav_point->parent))
				self::_setSitesToNull($nav_point->parent);

			$sort = DB::query('SELECT max(`sort`) + 1 as maxsort FROM ' . Session::get('lang_prefix') . '_navigation')->execute();
			$sort = $sort->as_array();

			$nav_point->sort = ($sort[0]['maxsort'] == null) ? 0 : $sort[0]['maxsort'];

			$nav_point->save();

			$last = model_db_navigation::find('last');
			$navigation_id = $last->id;

			if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images'))
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix'), 'navigation_images');

			if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id))
			{
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images', $navigation_id);
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'original');
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'preview');
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'thumbs');
			}

			if(!empty($nav_point->image))
			{
				File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $nav_point->image);
				File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $nav_point->image);
				File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $nav_point->image);
			}	

			$config = array(
			    'path' => DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original',
			    'randomize' => true,
			    'auto_rename' => false,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

			if (Upload::is_valid())
			{
				$options = \Controller_Advanced_Advanced::getOptions();
				Upload::save();
				foreach(Upload::get_files() as $file)
				{
					$resizeObj = new image\resize(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as']);
					$size = Image::sizes(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as']);
					
					if($size->width >= 1280)
						$size->width = 1280;

					if($size->height >= 720)
						$size->height = 720;

					$resizeObj -> resizeImage($size->width, $size->height, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage(60, 60, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage($options['navigation_image_width'], $options['navigation_image_height'], 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $file['saved_as'], 100);
				}
			}

			$last->image = $file['saved_as'];

			$last->save();

			$last_nav = model_db_navigation::find('last');

			$label = Input::post('label');

			$site = new model_db_site();
			$site->label = $nav_point->label;
			$site->url_title = model_generator_seo::friendly_title($nav_point->label);
			$site->site_title = '';
			$site->redirect = '';
			$site->keywords = '';
            $site->template = '';
			$site->navigation_id = $last_nav->id;
			$site->description = '';
			$site->group_id = Input::post('id');

			$site->sort = 0;
			$site->save();

			$added_nav = model_db_navigation::find('last');
			model_permission::addNavigationToPermissionList($added_nav->id);

			Response::redirect('admin/navigation/' . Input::post('id'));
		}
	}

	public function action_edit()
	{		
		$rights = model_permission::getNavigationRights();
		if(!in_array($this->id,$rights['data']) && !$rights['admin'])
			Response::redirect('admin/navigation');

		$nav_point = model_db_navigation::find($this->id);

		if(isset($_POST['submit']))
		{
			$nav_point->label = Input::post('label');
			$nav_point->url_title = model_generator_seo::friendly_title($nav_point->label);
            $nav_point->group_id = Input::post('group_id');
            $nav_point->show_sub = Input::post('show_sub');
            $nav_point->show_in_navigation = Input::post('show_in_navigation') != '';

            $site_point = model_db_site::find('first',array(
            	'where' => array('navigation_id' => $nav_point->id)
            ));
            $site_point->label = Input::post('label');
            
   			if(is_object($site_point))
            {
            	$site_point->group_id = $nav_point->group_id;
            }
            
            $site_point->save();

			$nav_point->parent = Input::post('parent');

			$navigation_id = $nav_point->id;

			$config = array(
			    'path' => DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original',
			    'randomize' => true,
			    'auto_rename' => false,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

			$image_file = '';

			if (Upload::is_valid())
			{
				if(!empty($real_nav_point->image) 
					&& file_exists(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $real_nav_point->image))
				{
					File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $real_nav_point->image);
					File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $real_nav_point->image);
					File::delete(DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $real_nav_point->image);
				}	

				$options = \Controller_Advanced_Advanced::getOptions();
				Upload::save();
				foreach(Upload::get_files() as $file)
				{
					$resizeObj = new image\resize(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as']);
					$size = Image::sizes(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as']);
					
					if($size->width >= 1280)
						$size->width = 1280;

					if($size->height >= 720)
						$size->height = 720;

					$resizeObj -> resizeImage($size->width, $size->height, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage(60, 60, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage($options['navigation_image_width'], $options['navigation_image_height'], 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $file['saved_as'], 100);
				}
				$image_file = $file['saved_as'];
			}
			$nav_point->image = empty($image_file) ? $nav_point->image : $image_file;
			$nav_point->image_is_shown = Input::post('image_is_shown');

			if(Input::post('parent') >= 1)
			{
				$navis = model_db_navigation::find('all',array(
					'where' => array('parent'=>$nav_point->id)
				));

				foreach($navis as $navi)
				{
					$navi->parent = 0;
					$navi->save();
				}
			}
			if(Input::post('parent') == 0)
			{
				$navis = model_db_navigation::find('all',array(
					'where' => array('parent'=>$nav_point->id)
				));

				foreach($navis as $navi)
				{
					$navi->group_id = $nav_point->group_id;
					$navi->save();
					$site = model_db_site::find('first',array(
						'where' => array('navigation_id'=>$navi->id)
					));
					$site->group_id = $nav_point->group_id;
					$site->save();
				}
			}

			$nav_point->save();

			Response::redirect('admin/navigation/edit/' . $nav_point->id);
		}

		$data = array();
		$data['label'] = $nav_point->label;
		$data['parent'] = $nav_point->parent;
		$data['show_sub'] = $nav_point->show_sub;
		$data['show_in_navigation'] = $nav_point->show_in_navigation;

		$site = model_db_navigation::find('first',array(
			'where' => array('parent'=>$nav_point->id)
		));
		$data['show_sub_field'] = (is_object($site));

                $data['group_id'] = $nav_point->group_id;
		$data['parent_array'] = $this->_getParentArray();

		if(isset($data['parent_array'][$this->id]))
			unset($data['parent_array'][$this->id]);

		$data['mode'] = 'edit';
		$data['image'] = Uri::create('uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $nav_point->id . '/preview/' . $nav_point->image);
		$data['image_exists'] = is_file(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $nav_point->id . '/preview/' . $nav_point->image);
		$data['image_is_shown'] = $nav_point->image_is_shown;

		$this->data['content'] = View::factory('admin/columns/navigation',$data);
	}

	public function action_delete()
	{
        $lprefix = Session::get('lang_prefix');

		//$language_version = Input::get('language_version');
		model_db_content::setLangPrefix($lprefix);
		model_db_site::setLangPrefix($lprefix);
		model_db_news::setLangPrefix($lprefix);
		model_db_navigation::setLangPrefix($lprefix);
		model_db_navgroup::setLangPrefix($lprefix);

		
		$nav_point = model_db_navigation::find($this->id);

		$sub_points = model_db_navigation::find('all',array(
			'where' => array('parent'=>$this->id)
		));

		if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $nav_point->id))
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $nav_point->id);

		if(!empty($nav_point->parent))
			self::_setSitesToNull($this->id);

		model_permission::removeNavigationFromPermissionList($this->id);

		

		$site_point = model_db_site::find('first',array(
			'where' => array('navigation_id' => $nav_point->id)
		));

		$nav_point->delete();

        $lid = model_db_language::prefixToId($lprefix);
        
        $format = Format::forge(model_db_option::getKey('landing_page')->value,'json')->to_array();
        
        if($format[$lid] == $site_point->id)
            $this->_set_landing_page(0);

		$contents = model_db_content::find()->where('site_id',$site_point->id)->get();

		$site_point->delete();

		model_helper_management_content::remove_unneeded_content();

		foreach($contents as $content)
		{
			#$content->delete();
			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id);

			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id);
		}

		Response::redirect('admin/navigation');
	}

	public function action_order()
	{
		$order = Input::post('order');

		$last_main_entry = 0;

		foreach($order as $position => $id)
		{
			$row = model_db_navigation::find($id);
			if($row->parent != 0)
			{
				$row->parent = $last_main_entry;
			}
			$row->sort = $position;
			$row->save();

			if($row->parent == 0)
				$last_main_entry = $id;
		}
	}

	public function action_group_delete()
	{
		$this->_ajax = true;

                if(count(model_db_navgroup::find('all')) == 1)
                    return;
                
		$group = model_db_navgroup::find($_GET['id']);
		$group->delete();

		$navs = model_db_navigation::find('all',array(
			'where' => array('group_id'=>$_GET['id']),
		));
                
                $group = model_db_navgroup::find('first');

		if(!empty($navs))
		{
                    foreach($navs as $nav)
                    {
                        $nav->group_id = $group->id;
                        $nav->save();
                    }
		}

		$sites = model_db_site::find('all',array(
			'where' => array('group_id'=>$_GET['id']),
		));
		

		if(!empty($sites))
		{
			foreach($sites as $site)
			{
				$site->group_id = $group->id;
				$site->save();
			}
		}

		$this->response->body = 'true';
	}

	public function action_group_edit()
	{
		$this->_ajax = true;

		$group = model_db_navgroup::find($_GET['id']);
		$group->title = $_GET['name'];
		$group->save();

		$this->response->body = 'true';
	}

	public function action_group_new()
	{
		$this->_ajax = true;

		$group = new model_db_navgroup();
		$group->title = $_GET['group'];
		$group->save();

		$group = model_db_navgroup::find('last');
		$this->response->body = $group->id;
	}

	public function after($response)
	{
		if(!$this->_ajax)
			$this->response->body = View::factory('admin/index',$this->data);
	}
}