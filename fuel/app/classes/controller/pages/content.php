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
class Controller_Pages_Content extends Controller
{
	private $data = array();

	private $id;

	private $content_id;

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
	}

	public function action_index()
	{
		$data = array();
		
		$this->data['content'] = View::factory('admin/columns/sites',$data);
	}

	public function action_add()
	{
		if(isset($_POST['addContent']))
		{
			$content = new model_db_content();
			$content->label = __('constants.untitled_element');
			$content->site_id = $this->id;
			$content->form = '{
												   "sendTo":"",
												   "company_label":"' . __('types.2.label.company') . '",
												   "first_name_label":"' . __('types.2.label.first_name') . '",
												   "last_name_label":"' . __('types.2.label.last_name') . '",
												   "postal_code_label":"' . __('types.2.label.postal_code') . '",
												   "city_label":"' . __('types.2.label.city') . '",
												   "email_label":"' . __('types.2.label.email') . '",
												   "phone_label":"' . __('types.2.label.phone') . '",
												   "text_label":"' . __('types.2.label.text') . '"
												}';
			$content->pictures = 'lightbox';
			$content->text = '';
			$content->text2 = '';
			$content->text3 = '';
			$content->wmode = 'window';
			$content->refer_content_id = '{"col_1":0,"col_2":0,"col_3":0}';
			$content->type = Input::post('type');
			$content->save();

			Controller_Pages_Pages::update_site($this->id);

			Response::redirect('admin/sites/edit/' . $this->id);
		}
	}    

	public function action_type1()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['submit']))
		{
			$content->label = Input::post('label');
			$content->text = stripslashes(Input::post('editor'));
			$content->save();

			Controller_Pages_Pages::update_site($this->id);
			Controller_Login::clear_cache();
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['label'] = $content->label;
		$data['text'] = $content->text;
		
		$this->data['content'] = View::factory('admin/type/textcontainer',$data);
	}

	public function action_type6()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['submit']))
		{
			$content->label = Input::post('label');
			$content->text = stripslashes(Input::post('editor'));
			$content->text2 = stripslashes(Input::post('editor2'));
			$content->save();

			Controller_Pages_Pages::update_site($this->id);
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['label'] = $content->label;
		$data['text'] = $content->text;
		$data['text2'] = $content->text2;
		
		$this->data['content'] = View::factory('admin/type/2colums',$data);
	}

	public function action_type7()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['submit']))
		{
			$content->label = Input::post('label');
			$content->text = stripslashes(Input::post('editor'));
			$content->text2 = stripslashes(Input::post('editor2'));
			$content->text3 = stripslashes(Input::post('editor3'));
			$content->save();

			Controller_Pages_Pages::update_site($this->id);
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['label'] = $content->label;
		$data['text'] = $content->text;
		$data['text2'] = $content->text2;
		$data['text3'] = $content->text3;
		
		$this->data['content'] = View::factory('admin/type/3colums',$data);
	}

	public function action_type2()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['submit']))
		{
			$content->label = Input::post('label');

			$data = $_POST;
			unset($data['submit']);
			unset($data['label']);

			$content->form = Format::factory( $data)->to_json();

			$content->save();

			Controller_Pages_Pages::update_site($this->id);
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['label'] = $content->label;
		$data = $data + Format::factory( $content->form, 'json')->to_array();
		
		$this->data['content'] = View::factory('admin/type/form',$data);
	}
	
	public function action_type3()
	{
		$content = model_db_content::find($this->content_id);

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' , $content->id,0777);

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id. '/original'))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' , $content->id . '/original',0777);

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id. '/big'))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' , $content->id . '/big',0777);

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id. '/thumbs'))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' , $content->id . '/thumbs',0777);
                
		if(isset($_POST['submit']))
		{
			$content->label = Input::post('label');
			$content->text = Input::post('text');

			if(Input::post('mode') == 'slideshow' || Input::post('mode') == 'lightbox')
				$nr = '';
			else
				$nr = '/' . Input::post('nr');

			$content->pictures = Input::post('mode') . $nr;

			Controller_Pages_Pages::update_site($this->id);

			$config = array(
			    'path' => DOCROOT.'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id . '/original',
			    'randomize' => true,
			    'auto_rename' => false,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);
			Upload::process($config);

			empty($content->parameter) and $content->parameter = '[]';
			$content->parameter = json_decode($content->parameter,true);

			if (Upload::is_valid())
			{
				$options = \Controller_Advanced_Advanced::getOptions();
				Upload::save();
				foreach(Upload::get_files() as $file)
				{
					$resizeObj = new image\resize(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id . '/original/' . $file['saved_as']);
					$size = Image::sizes(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id . '/original/' . $file['saved_as']);
					
					if($size->width >= 1280)
						$size->width = 1280;

					if($size->height >= 720)
						$size->height = 720;

					$content->parameter[] = $file['saved_as'];

					$resizeObj -> resizeImage($size->width, $size->height, 'auto');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id . '/big/' . $file['saved_as'], 100);

					$resizeObj -> resizeImage($options['gallery_thumbs_width'], $options['gallery_thumbs_height'], 'crop');
					$resizeObj -> saveImage(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $content->id . '/thumbs/' . $file['saved_as'], 100);
				}
			}

			$content->parameter = json_encode($content->parameter);

			$content->save();
			
			Response::redirect(Uri::current());
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['label'] = $content->label;
		$data['text'] = $content->text;
		$data['id'] = $content->id;
		$data['mode'] = $content->pictures;
		$data['customFile'] = '';

		if($data['mode'] != 'slideshow' && $data['mode'] != 'lightbox') 
		{
			$custom = explode('/',$content->pictures);
			$data['customFile'] = $custom[1];
		}

		$data['pictures'] = !empty($content->parameter) ? json_decode($content->parameter) : array();
		
		$this->data['content'] = View::factory('admin/type/gallery',$data);
	}

	public function action_type5()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}
		if(isset($_POST['submit']))
		{
			$x = array(
				'col_1'=>Input::post('col_1'),
			);
			$y = Format::factory($x)->to_json();
			
			$content->refer_content_id = $y;
			$content->save();

			Controller_Pages_Pages::update_site($this->id);

			Response::redirect(Uri::current());
		}
		$data = array();

		$array = Format::factory($content->refer_content_id ,'json')->to_array();
		$data['col_1_selected'] = $array['col_1'];

		$this->data['content'] = View::factory('admin/type/content_linking_1column',$data);
	}

	public function action_type8()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}
		if(isset($_POST['submit']))
		{
			Controller_Pages_Pages::update_site($this->id);

			$x = array(
				'col_1'=>Input::post('col_1'),
				'col_2'=>Input::post('col_2'),
			);
			$y = Format::factory($x)->to_json();
			
			$content->refer_content_id = $y;
			$content->save();

			Response::redirect(Uri::current());
		}
		$data = array();

		$array = Format::factory($content->refer_content_id ,'json')->to_array();
		$data['col_1_selected'] = $array['col_1'];
		$data['col_2_selected'] = $array['col_2'];

		$this->data['content'] = View::factory('admin/type/content_linking_2column',$data);
	}

	public function action_type9()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}
		if(isset($_POST['submit']))
		{
			Controller_Pages_Pages::update_site($this->id);

			$x = array(
				'col_1'=>Input::post('col_1'),
				'col_2'=>Input::post('col_2'),
				'col_3'=>Input::post('col_3')
			);
			$y = Format::factory($x)->to_json();

			$content->refer_content_id = $y;
			$content->save();

			Response::redirect(Uri::current());
		}
		$data = array();

		$array = Format::factory($content->refer_content_id ,'json')->to_array();
		$data['col_1_selected'] = $array['col_1'];
		$data['col_2_selected'] = $array['col_2'];
		$data['col_3_selected'] = $array['col_3'];

		$this->data['content'] = View::factory('admin/type/content_linking_3column',$data);
	}

	public function action_type10()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}
		if(isset($_POST['submit']))
		{
			Controller_Pages_Pages::update_site($this->id);

			if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash'))
			{
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') , 'flash',0755);
			}

			if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id))
			{
				File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash' , $content->id,0755);
			}

			$config = array(
		    'path' => DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id,
		    'randomize' => true,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png','swf'),
			);

			// process the uploaded files in $_FILES
			Upload::process($config);

			// if there are any valid files
			if (Upload::is_valid())
			{
					Upload::save();

			    foreach(Upload::get_files() as $file)
			    {
			    	if($file['extension'] == 'swf')
			    	{
			    		if(!empty($content->flash_file) && file_exists(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id . '/' . $content->flash_file))
			    			File::delete(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id . '/' . $content->flash_file);

			    		$content->flash_file = $file['saved_as'];
			    	}
			    	else
			    	{
			    		if($content->pictures != 'lightbox' && file_exists(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id . '/' . $content->flash_file))
			    			File::delete(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id . '/' . $content->pictures);

			    		$content->pictures = $file['saved_as'];
			    	}
			    }
			}
			$content->dimensions = Input::post('height') . ';' . Input::post('width');
			$content->parameter = Input::post('params');
			$content->wmode = Input::post('wMode');
			$content->label = Input::post('label');
			$content->save();

			Response::redirect(Uri::current());
		}
		$data = array();
		$data['params'] = $content->parameter;
		$data['label'] = $content->label;
		$data['wMode'] = $content->wmode;

		$dimensions = explode(';',$content->dimensions);
		$data['height'] = (!isset($dimensions[1])) ? 640 : $dimensions[0];
		$data['width'] = (!isset($dimensions[1])) ? 480 : $dimensions[1];

		if($content->pictures == 'lightbox')
			$data['picture'] = Uri::create('assets/img/admin/350x100.gif');
		else
			$data['picture'] = Uri::create('uploads/' . Session::get('lang_prefix') . '/flash/' . $content->id . '/' . $content->pictures);

		if(empty($content->flash_file))
			$data['flash'] = '<img src="' . Uri::create('assets/img/admin/350x100.gif') . '" />';
		else 
		{
			model_generator_preparer::$lang = Session::get('lang_prefix');
			model_generator_preparer::$currentSite = model_db_site::find(Uri::segment(3));
			model_generator_module::$content = true;
			$data['flash'] = model_generator_content::renderContent($content->id,Session::get('lang_prefix'));
		}
		
		$this->data['content'] = View::factory('admin/type/flash',$data);
	}
        
	public function action_type11()
	{
		$content = model_db_content::find($this->content_id);

		if(isset($_POST['submit']))
		{
			Controller_Pages_Pages::update_site($this->id);

			$placeholder_name = Input::post('placeholder_name');
			$placeholder_text = Input::post('placeholder_text');
			$parameter = array();

			for($i=0;$i<count($placeholder_text);++$i)
			{
				$parameter[] = array(
					'name' => $placeholder_name[$i],
					'text' => trim($placeholder_text[$i])
				);
			}

			$content->label = 'HTML';
			$content->text = Input::post('html');
			$content->parameter = json_encode($parameter);
			$content->save();
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data = array();
		$data['text'] = $content->text;
		$data['parameter'] = json_decode(
			empty($content->parameter) ? '{}' : $content->parameter
		,true);
		
		$this->data['content'] = View::factory('admin/type/html',$data);
	}

	public function action_type12()
	{
		$content = model_db_content::find($this->content_id);
		empty($content->parameter) and $content->parameter = "{active:\"\"}";
		$params = json_decode($content->parameter,true);


		$data = array();
		if(isset($params['active']))
		{
			$split = explode('\\',$params['active']);
			require_once APPPATH . '../../plugin/' . $split[0] .'/' . $split[1] . '.php';
			$plugin = new $params['active']();
			$data['option_form'] = $plugin->get_options();
		}

		if(isset($_POST['change_options']))
		{
			$params['options'] = array();
			foreach ($data['option_form']['form'] as $name => $x) 
			{
				$postdata = Input::post($name);
				$params['options'][$name] = $postdata == null ? 0 : $postdata;
			}
			$content->parameter = json_encode($params);
			$content->save();
		}

		if(isset($_POST['submit']))
		{
			Controller_Pages_Pages::update_site($this->id);

			$params['active'] = Input::post('active_plugin');
			!isset($params['options']) and $params['options'] = array();
			$content->parameter = json_encode($params);
			$content->label = $params['active'];
			$content->save();
			Response::redirect(Uri::current());
		}

		if(isset($_POST['back']))
		{
			Response::redirect('admin/sites/edit/' . $this->id);
		}

		$data['folder_plugin'] = \File::read_dir(APPPATH . '../../plugin',2);
		$data['active_plugin'] = $params['active'];
		$data['active_options'] = array();

		is_array($params['options']) and $data['active_options'] = $params['options'];

		$this->data['content'] = View::factory('admin/type/plugin',$data);
	}

	public function action_delete()
	{
		$delete = model_db_content::find($this->id);
                
        if(in_array('delete',get_class_methods($delete)))
            $delete->delete();

		if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $this->id))
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $this->id);

		if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $this->id))
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/flash/' . $this->id);

		if(is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video/' . $this->id))
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video/' . $this->id);

		Controller_Pages_Pages::update_site($this->id);

		Response::redirect('admin/sites/edit/' . $delete->site_id);
	}

	public function action_delete_gal_picture()
	{
		$file = Input::post('filename');
		$content = model_db_content::find(Input::post('content_id'));

		$path = DOCROOT . 'uploads/'. Session::get('lang_prefix') . '/gallery/' . $content->id;

		if(file_exists($path . '/original/' . $file))
		{
			File::delete($path . '/original/' . $file);
			File::delete($path . '/big/' . $file);
			File::delete($path . '/thumbs/' . $file);
		}



		$pictures = json_decode($content->parameter,true);
		$content->parameter = array_filter($pictures, function($value) use ($file) {
			return ($value != $file);
		});
		$content->parameter = json_encode($content->parameter);
		$content->save();
	}

	public function action_update_gal_order()
	{
		$this->_ajax = true;

		$order = Input::post('order');

		$content = model_db_content::find($this->param('id'));

		$content->parameter = json_encode($order);
		$content->save();

		Controller_Pages_Pages::update_site(Input::post('site_id'));
	}

	public function action_order()
	{
		$order = Input::post('order');

		foreach($order as $position => $id)
		{
			$row = model_db_content::find($id);
			$row->sort = $position;
			$row->save();
		}
		Controller_Pages_Pages::update_site(Input::post('site_id'));
	}

	public function after($response)
	{
		if(!$this->_ajax)
		$this->response->body = View::factory('admin/index',$this->data);
	}
}