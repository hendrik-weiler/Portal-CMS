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
class Controller_Pages_Pages extends Controller
{
	private $data = array();

	private $id;

	private $_ajax = false;
        
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
				$nav = model_db_site::find(Uri::segment(4));
				$id = $nav->group_id;
			}
			else
			{
				$nav = model_db_site::find('first');
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

	public function before()
	{
		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');
		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
		model_db_content::setLangPrefix(Session::get('lang_prefix'));
		model_db_navgroup::setLangPrefix(Session::get('lang_prefix'));

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][1]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		if(Uri::segment(3) == '')
		{
			$search = model_db_navgroup::find('first');
			Response::redirect('admin/sites/' . $search->id);
		}
	}

	public function action_index()
	{
		$data = array();
		$data['label'] = '';
		$data['redirect'] = '';
		$data['keywords'] = '';
		$data['site_title'] = '';
		$data['description'] = '';
                $data['current_template'] = 'default';
		$data['navigation_id'] = 0;
                $data['site_id'] = 0;
		$data['mode'] = 'add';

		$this->data['content'] = View::factory('admin/columns/sites',$data);
	}

	public function action_add()
	{
		if(isset($_POST['submit']))
		{
			$label = Input::post('label');

			$site = new model_db_site();
			$site->label = empty($label) ? __('constants.untitled_element') : $label;
			$site->url_title = model_generator_seo::friendly_title($site->label);
			$site->site_title = Input::post('site_title');
			$site->redirect = Input::post('redirect');
			$site->keywords = Input::post('keywords');
                        $site->template = Input::post('current_template');
			$site->navigation_id = Input::post('navigation_id');
			$site->description = Input::post('description');
			$site->group_id = Input::post('id');

			$query = DB::query('SELECT MAX( sort ) +1 AS maxsort FROM  `' . Session::get('lang_prefix') . '_site`')->execute();
			$row = $query->as_array();

			$site->sort = ($row[0]['maxsort'] == null) ? 0 : $row[0]['maxsort'];
			$site->save();
                        
                        $last = model_db_site::find('last');
                        
                        if(Input::post('landing_page') == 1)
                            $this->_set_landing_page($last->id);

			Response::redirect('admin/sites');
		}
	}

	public static function update_site($site_id)
	{
		$site = model_db_site::find($site_id);
		if(is_object($site)) {
			$site->changed = date('Y-m-d H:i:s',time());
			$site->save();
		}
	}

    public function got_sub_points($site)
    {
        $nav = model_db_navigation::find($site->navigation_id);
        $subpoints = model_db_navigation::find('first',array(
           'where' => array('parent'=>$nav->id)
        ));

        return is_object($subpoints);
    }

    public function action_delete_image()
    {

        $nav_point = model_db_site::find($this->id);
        $real_nav_point = model_db_navigation::find($nav_point->navigation_id);

        $navigation_id = $real_nav_point->id;

        if($real_nav_point->image != '') {
            if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id)) {
                if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview')) {
                    if(file_exists(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $real_nav_point->image)) {
                        File::delete(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview/' . $real_nav_point->image);
                    }
                }
                if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs')) {
                    if(file_exists(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $real_nav_point->image)) {
                        File::delete(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs/' . $real_nav_point->image);
                    }
                }
                if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original')) {
                    if(file_exists(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $real_nav_point->image)) {
                        File::delete(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original/' . $real_nav_point->image);
                    }
                }
            }
        }

        Response::redirect("admin/sites/edit/" . $nav_point->id);
    }

	public function action_edit()
	{		
		$perm = model_permission::getNavigationRights();
		$rights = model_permission::getNavsFromSiteId($this->id);
		$label = Input::post('label');
		$proof = array();
		if($rights['main'] != null)
			$proof[] = $rights['main'];

		if($rights['sub'] != null)
			$proof[] = $rights['sub'];
			
		if(!in_array($proof,$perm) && !$perm['admin'])
				Response::redirect('admin/sites');

		$nav_point = model_db_site::find($this->id);

		if(isset($_POST['submit']))
		{
			$nav_point->label = empty($label) ? __('constants.untitled_element') : $label;
			$nav_point->url_title = model_generator_seo::friendly_title($nav_point->label);
			$nav_point->redirect = Input::post('redirect');
			$nav_point->site_title = Input::post('site_title');
			$nav_point->changed = date('Y-m-d H:i:s',time());

			$real_nav_point = model_db_navigation::find($nav_point->navigation_id);
			if($nav_point->navigation_id != 0)
			{
				$real_nav_point->show_in_navigation = Input::post('show_in_navigation') != '';
				$real_nav_point->parent = Input::post('parent');
				$real_nav_point->group_id = Input::post('group_id');
                $real_nav_point->show_sub = Input::post('show_sub');
				$real_nav_point->label = empty($label) ? __('constants.untitled_element') : $label;
				$real_nav_point->url_title = model_generator_seo::friendly_title($nav_point->label);
			}

			$navigation_id = $nav_point->id;

			$config = array(
			    'path' => DOCROOT.'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original',
			    'randomize' => true,
			    'auto_rename' => false,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

            if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images')) {
                File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix'), "navigation_images", 0777);
            }
            if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id)) {
                File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images' , $navigation_id, 0777);
            }
            if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/preview')) {
                File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'preview', 0777);
            }
            if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/thumbs')) {
                File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'thumbs', 0777);
            }
            if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id . '/original')) {
                File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation_id, 'original', 0777);
            }

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

			$real_nav_point->image = empty($image_file) ? $real_nav_point->image : $image_file;
			$real_nav_point->image_is_shown = Input::post('image_is_shown');

			$real_nav_point->parameter = json_encode(array(

				'text_color' => Input::post('text_color'),
				'use_default_styles' => Input::post('use_default_styles') != '',
				'background_color' => Input::post('background_color'),
				'description' => Input::post('navi_description'), 

			));

			$real_nav_point->save();

			$nav_point->keywords = Input::post('keywords');
            $nav_point->template = Input::post('current_template');
			$nav_point->navigation_id = Input::post('navigation_id');
			$nav_point->description = Input::post('description');
			$nav_point->group_id = Input::post('group_id');
			$nav_point->save();

			Controller_Login::clear_cache();
                        
            if(Input::post('landing_page') == 1)
                $this->_set_landing_page($nav_point->id);

			Response::redirect('admin/sites/edit/' . Input::post('site_id'));
		}

		$navigation = model_db_navigation::find($nav_point->navigation_id);

		$data = array();
		$data['label'] = !is_object($navigation) ? $nav_point->label : $navigation->label;
        $data['current_template'] = $nav_point->template;
		$data['redirect'] = $nav_point->redirect;
		$data['site_title'] = $nav_point->site_title;
		$data['keywords'] = $nav_point->keywords;
		$data['description'] = $nav_point->description;
		$data['navigation_id'] = $nav_point->navigation_id;
		$data['id'] = $this->id;
        $data['site_id'] = $nav_point->id;

		$data['image'] = Uri::create('uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation->id . '/preview/' . $navigation->image);
		$data['image_exists'] = is_file(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/navigation_images/' . $navigation->id . '/preview/' . $navigation->image);
		$data['image_is_shown'] = $navigation->image_is_shown;

		$data['parent'] = $navigation->parent;
		$data['show_sub'] = $navigation->show_sub;
		$data['show_in_navigation'] = $navigation->show_in_navigation;

		empty($navigation->parameter) and $navigation->parameter = '[]';
		$parameter = Format::forge($navigation->parameter,'json')->to_array();

		$data['navi_description'] = !isset($parameter['description']) ? '' : $parameter['description'];
		$data['use_default_styles'] = !isset($parameter['use_default_styles']) ? 1 : $parameter['use_default_styles'];
		$data['text_color'] = !isset($parameter['text_color']) ? '#FFFFFF' : $parameter['text_color'];
		$data['background_color'] = !isset($parameter['background_color']) ? '#000000' : $parameter['background_color'];

		$site = model_db_navigation::find('first',array(
			'where' => array('parent'=>$navigation->id)
		));
		$data['show_sub_field'] = (is_object($site));

                $data['group_id'] = $navigation->group_id;
		$data['parent_array'] = $this->_getParentArray();

		if(isset($data['parent_array'][$navigation->id]))
			unset($data['parent_array'][$navigation->id]);

        $data['got_sub_points'] = false;
        if($this->got_sub_points($nav_point)) {
            $data['got_sub_points'] = true;
        }

		$data['mode'] = 'edit';

		$this->data['content'] = View::factory('admin/columns/sites',$data);
	}

	public function action_delete()
	{
		$nav_point = model_db_site::find($this->id);
                
                $lprefix = Session::get('lang_prefix');

                $lid = model_db_language::prefixToId($lprefix);
                
                $format = Format::forge(model_db_option::getKey('landing_page')->value,'json')->to_array();
                
                if($format[$lid] == $nav_point->id)
                    $this->_set_landing_page(0);
                
		$nav_point->delete();

		$contents = model_db_content::find()->where('site_id',$this->id)->get();

		foreach($contents as $content)
		{
			$content->delete();
			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id);

			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id);

			if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video/' . $content->id))
				File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video/' . $content->id);
		}

		Response::redirect('admin/sites');
	}

	public function action_order()
	{
		$order = Input::post('order');

		foreach($order as $position => $id)
		{
			$row = model_db_sites::find($id);
			$row->sort = $position;
			$row->save();
		}
	}

	public function action_classnames()
	{
		$this->_ajax = true;

		$class_id = Input::post('classname');

		$ids = Input::post('ids');

		$contents = array();

		foreach($ids as $id)
			$contents[] = model_db_content::find($id);

		foreach($contents as $content)
		{
			$content->classname = $class_id;
			$content->save();
		}

		Controller_Login::clear_cache();

		return true;
	}

	public static function generateUrl($id)
	{
		$site = model_db_site::find($id);
		$main = model_db_navigation::find($site->navigation_id);
		$sub = '';

		if($main->parent != 0)
		{
			$sub = $main->url_title;
			$main = model_db_navigation::find($main->parent);
		}

		return Uri::create(Session::get('lang_prefix') . '/' . $main->url_title . '/' . $sub);
	}

	public function after($response)
	{
		if(!$this->_ajax)
		$this->response->body = View::factory('admin/index',$this->data);
	}
}