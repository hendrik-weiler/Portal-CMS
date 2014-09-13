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
class Controller_Pages_Content_Type13 extends Controller
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

		$template_path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value;

		$content_template_path =  $template_path . '/content_templates';

		$custom_path = $content_template_path . '/custom';

		if(!is_dir($content_template_path)) {
			File::create_dir($template_path, 'content_templates', 0777);
		}

		if(!is_dir($custom_path)) {
			File::create_dir($content_template_path, 'custom', 0777);
		}

		$template_folder = \File::read_dir($custom_path,1);
		$data['templates'] = array();
		foreach ($template_folder as $r)
		  $data['templates'][$r] = $r;

		$this->content->text == '' and $this->content->text = '[]';
		$data += Format::forge($this->content->text,'json')->to_array();

		$data['selected_template'] = $this->content->label == __('constants.untitled_element') ? 0 : $this->content->label;

		$data['template_variables'] = array();
		if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/custom/' . $this->content->label))
		{
			$template_content = file_get_contents(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/custom/' . $this->content->label);
			$data['template_variables'] = model_helper_content_template::parse_template($template_content);
		}

		$data['content_id'] = $this->content_id;
		$file = $this->param('file');
		$data['code'] = '';
		$data['currentFile'] = $file;
		if($file != null) {
			$layout = model_db_option::getKey('layout');
			$path = APPPATH . '../../layouts/'.$layout->value.'/content_templates/custom/'.$file;

			$content = file_get_contents($path);
			$data['code'] = $content;
		}
		
		$this->data['content'] = View::factory('admin/type/template',$data);
	}

	public function action_edit()
	{
		$this->_ajax = true;

		if(Input::post('back') != '')
		{
			Response::redirect('admin/sites/edit/' . Uri::segment(3));
		}

		Controller_Pages_Pages::update_site($this->content->site_id);

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template'))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') , 'template');

		if(!is_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template/' . $this->content_id))
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template' , $this->content_id);

		$config = array(
		    'path' => DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template/' . $this->content_id,
		    'randomize' => true,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png','flv','swf'),
		);

		$variables = Format::forge($this->content->text, 'json')->to_array();

		// process the uploaded files in $_FILES
		Upload::process($config);

		// if there are any valid files
		if (Upload::is_valid())
		{
		    // save them according to the config
		    Upload::save();

			foreach(Upload::get_files() as $file)
			{
			    $variables[$file['field']] = ('uploads/' . Session::get('lang_prefix') . '/template/' . $this->content_id . '/' . $file['saved_as']);
			}
		}


		foreach ($_POST as $key => $value) 
		{
    		if(preg_match('#(tpl_text_[\w]+)#i', $key)
    			|| preg_match('#(tpl_rawtext_[\w]+)#i', $key)
    			|| preg_match('#(tpl_siteselector_[\w]+)#i', $key))
    		{
    			$variables[$key] = Input::post($key);
    		}
		}


		$this->content->text = Format::forge($variables)->to_json();

		if(Input::post('template') != $this->content->label)
		{
			File::delete_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template/' . $this->content_id, true);
			File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/template' , $this->content_id);
			$this->content->text = '';
		}

		$this->content->label = Input::post('template');
		$this->content->save();

		Response::redirect(substr_replace(Uri::current() ,"",-5));
	}

	public function action_preview()
	{
		$this->_ajax = true;

		$layout = model_db_option::getKey('layout');

		include_once APPPATH.'views/public/procedural_helpers.php';

		model_generator_layout::$name = $layout->value;

	    $settings = file_get_contents(LAYOUTPATH . '/' . $layout->value . '/settings.json');
	    $settings = Format::forge($settings,'json')->to_array();

	    model_generator_layout::$assets = $settings['assets'];

		print Asset\Manager::insert('all');

		model_generator_module::$content = true;
		print model_generator_content::renderContent($this->content_id, Session::get('lang_prefix')) ;
	}

	public function action_add_file()
	{
		$this->_ajax = true;

		$layout = model_db_option::getKey('layout');

		$ppath = APPPATH . '../../layouts/'.$layout->value.'/content_templates/custom/';
		$filename = Input::post('filename');
		
		File::create($ppath, $filename, '// Good luck!');

		Response::redirect('admin/content/'.$this->param('id').'/edit/'.$this->param('content_id').'/type/13/file/'.$filename);
	}

	public function action_save_file()
	{
		$this->_ajax = true;

		$file = $this->param('file');
		$file = explode('/', $file);
		$file = $file[0];
		$layout = model_db_option::getKey('layout');
		$ppath = APPPATH . '../../layouts/'.$layout->value.'/content_templates/custom/';
		$path = $ppath.$file;
		$code = Input::post('code');


		if(isset($_POST['delete'])) {
			unlink($path);

			Response::redirect('admin/content/'.$this->param('id').'/edit/'.$this->param('content_id').'/type/13');
		} else {
			$filename = Input::post('filename');
			if($file != $filename) {
				File::rename($path, $ppath.$filename);
				if(file_exists($path)) {
					File::delete($path);
				}
				$file = $filename;
			}

			file_put_contents($path, $code);

			Response::redirect('admin/content/'.$this->param('id').'/edit/'.$this->param('content_id').'/type/13/file/'.$file);
		}

		
	}

	public function after($response)
	{
		if(!$this->_ajax)
		$this->response->body = View::factory('admin/index',$this->data);
	}
}