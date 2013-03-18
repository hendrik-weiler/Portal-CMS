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
class Controller_Pages_Content_Type14 extends Controller
{
	private $data = array();

	private $id;

	private $content_id;

	private $content;

	private $_ajax = false;

	public function before()
	{
		if(Uri::segment(2) == 'serve') return;

		model_auth::check_startup();
		$this->data['title'] = 'Admin - ' . ucfirst(Uri::segment(2));
		$this->id = $this->param('id');

		$permissions = model_permission::mainNavigation();
		$this->data['permission'] = $permissions[Session::get('lang_prefix')];
		if(!$this->data['permission'][1]['valid'] || !model_permission::currentLangValid())
			Response::redirect('admin/logout');

		$this->content_id = Uri::segment(5);
		model_db_content::setLangPrefix(Session::get('lang_prefix'));
		model_db_navigation::setLangPrefix(Session::get('lang_prefix'));
		model_db_site::setLangPrefix(Session::get('lang_prefix'));
		$this->content = model_db_content::find($this->content_id);
	}

	public function action_index()
	{

		$path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/video';

		if(!is_dir($path)) File::create_dir(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value, 'video');
		if(!is_dir($path . '/skin')) File::create_dir($path, 'skin');

		$data = array();

		$data['content_id'] = $this->content_id;

		$this->content->parameter == '' and $this->content->parameter = '[]';
		$data += Format::forge($this->content->parameter,'json')->to_array();

		if($this->content->label == null)
		{
			$data['label'] = __('constants.untitled_element');
		}
		else
		{
			$data['label'] = $this->content->label;
		}

		if(!isset($data['autohide']))
		{
			$data['autohide'] = 0;	
		}

		if(!isset($data['autoplay']))
		{
			$data['autoplay'] = 0;	
		}

		if(!isset($data['fullscreen']))
		{
			$data['fullscreen'] = 0;	
		}

		if(!isset($data['color_text']))
		{
			$data['color_text'] = '#FFFFFF';
			$data['color_seekbar'] = '#13ABEC';
			$data['color_loadingbar'] = '#828282';
			$data['color_seekbarbg'] = '#333333';
			$data['color_button_out'] = '#333333';
			$data['color_button_over'] = '#000000';
			$data['color_button_highlight'] = '#ffffff';
		}

		if(!isset($data['height']))
		{
			$data['height'] = 300;
		}

		if(!isset($data['width']))
		{
			$data['width'] = 600;
		}

		if(!isset($data['video_name']))
		{
			$data['video_name'] = 'none';
		}

		$data['video_path'] = '/layout/' . model_db_option::getKey('layout')->value . '/video';
		$data['skin_path'] = '/layout/' . model_db_option::getKey('layout')->value . '/video/skin';

		if(!isset($data['selected_skin']))
		{
			$data['selected_skin'] = 'none';	
		}


		$skins = File::read_dir($path . '/skin',1);
		$skins_select = array('none'=>__('types.14.none'));

		foreach ($skins as $folder => $skin) {
			if($skin != false) {
				$skin = str_replace('.xml', '', $skin);
				$skins_select[$skin] = $skin;
			}
		}

		$data['skins'] = $skins_select;

		$videos = File::read_dir($path,1);
		$videos_select = array('none'=>__('types.14.none'));

		foreach ($videos as $folder => $video) {
			if($video != false) {
				$videos_select[$video] = $video;
			}
		}

		$data['videos'] = $videos_select;
		
		$this->data['content'] = View::factory('admin/type/flvvideoplayer',$data);
	}

	public function action_edit()
	{
		$this->_ajax = true;

		if(Input::post('back') != '')
		{
			Response::redirect('admin/sites/edit/' . Uri::segment(3));
		}

		$old_data = Format::forge($this->content->parameter, 'json')->to_array();

		$data = array(
			'selected_skin' => Input::post('skin'),
			'video_file' => '',
			'video_preview' => '',
			'video_name' => Input::post('video_name'),
			'color_text' => Input::post('color_text'),
			'color_seekbar' => Input::post('color_seekbar'),
			'color_loadingbar' => Input::post('color_loadingbar'),
			'color_seekbarbg' => Input::post('color_seekbarbg'),
			'color_button_out' => Input::post('color_button_out'),
			'color_button_over' => Input::post('color_button_over'),
			'color_button_highlight' => Input::post('color_button_highlight'),
			'width' => Input::post('width'),
			'height' => Input::post('height'),
			'autoplay' => Input::post('autoplay') == 1 ? 'true' : 'false',
			'autohide' => Input::post('autohide') == 1 ? 'true' : 'false',
			'fullscreen' => Input::post('fullscreen') == 1 ? 'true' : 'false',
		);

		if($old_data['video_preview'] != '')
		{
			$data['video_preview'] = $old_data['video_preview'];
		}

		if($old_data['video_file'] != '')
		{
			$data['video_file'] = $old_data['video_file'];
		}

		if(Input::post('skin_saved') == 1) 
		{
			$data['selected_skin'] = $old_data['selected_skin'];
		}

		$video_dir = DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video';

		if(!is_dir($video_dir)) File::create_dir(DOCROOT . 'uploads/' . Session::get('lang_prefix'), 'video');
		if(!is_dir($video_dir . '/' . $this->content_id)) File::create_dir($video_dir, $this->content_id);

		$config = array(
		    'path' => DOCROOT . 'uploads/' . Session::get('lang_prefix') . '/video/' . $this->content_id,
		    'randomize' => true,
		    'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png','flv'),
		);

		// process the uploaded files in $_FILES
		Upload::process($config);

		// if there are any valid files
		if (Upload::is_valid())
		{
			Upload::save();

		    foreach(Upload::get_files() as $file)
		    {
		    	if($file['extension'] == 'flv')
		    	{
		    		$filepath = $video_dir . '/' . $this->content_id . '/' . $old_data['video_file'];
		    		if(!is_dir($filepath) && file_exists($filepath))
		    		{
		    			File::delete($filepath);
		    		}

		    		$data['video_file'] = $file['saved_as'];
		    		$data['video_name'] = '';
		    	}
		    	else
		    	{
		    		$filepath = $video_dir . '/' . $this->content_id . '/' . $old_data['video_preview'];
		    		if(!is_dir($filepath) && file_exists($filepath))
		    		{
		    			File::delete($filepath);
		    		}

		    		$data['video_preview'] = $file['saved_as'];
		    	}
		    }
		}

		$this->content->label = Input::post('label');
		$this->content->parameter = json_encode($data);
		$this->content->save();

		if(Input::post('video_preview_delete') == __('types.14.preview_pic_delete'))
		{
    		$filepath = $video_dir . '/' . $this->content_id . '/' . $old_data['video_preview'];
    		if(!is_dir($filepath) && file_exists($filepath))
    		{
    			File::delete($filepath);
    		}
		}

		Response::redirect(substr_replace(Uri::current() ,"",-5));
	}

	public function action_serve_video()
	{
		$this->_ajax = true;

		$this->response->set_header('Content-Type','video/x-flv');

		$this->response->body = file_get_contents(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/video/' . $this->param('videoname'));

		return $this->response;
	}

	public function action_serve_player()
	{
		$this->_ajax = true;

		$this->response->set_header('Content-Type','application/x-shockwave-flash');

		$this->response->body = file_get_contents(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/player.swf');

		return $this->response;
	}

	public function action_serve_skin()
	{
		$this->_ajax = true;

		$this->response->set_header('Content-Type','application/xml');

		$this->response->body = file_get_contents(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/video/skin/' . $this->param('skinname') . '.xml');

		return $this->response;
	}

	public function action_save_skin()
	{
		function formatXmlString($xml) {  
		  
		  // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
		  $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
		  
		  // now indent the tags
		  $token      = strtok($xml, "\n");
		  $result     = ''; // holds formatted version as it is built
		  $pad        = 0; // initial indent
		  $matches    = array(); // returns from preg_matches()
		  
		  // scan each line and adjust indent based on opening/closing tags
		  while ($token !== false) : 
		  
		    // test for the various tag states
		    
		    // 1. open and closing tags on same line - no change
		    if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
		      $indent=0;
		    // 2. closing tag - outdent now
		    elseif (preg_match('/^<\/\w/', $token, $matches)) :
		      $pad--;
		    // 3. opening tag - don't pad this one, only subsequent tags
		    elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
		      $indent=1;
		    // 4. no indentation needed
		    else :
		      $indent = 0; 
		    endif;
		    
		    // pad the line with the required number of leading spaces
		    $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
		    $result .= $line . "\n"; // add to the cumulative result, with linefeed
		    $token   = strtok("\n"); // get the next token
		    $pad    += $indent; // update the pad size for subsequent lines    
		  endwhile; 
		  
		  return $result;
		}


		$this->_ajax = true;

		$name = $this->param('skinname');

		$skinpath = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/video/skin';

		$styling = Input::get('styling');

		$skin_xml = new SimpleXMLElement("<flvvideoplayer></flvvideoplayer>");

		foreach ($styling as $key => $value) {
			$skin_xml->addChild($key,$value);
		}

		$data = $skin_xml->asXML();

		$content_data = Format::forge($this->content->parameter, 'json')->to_array();
		$content_data['selected_skin'] = $name;
		$this->content->parameter = Format::forge($content_data)->to_json();
		$this->content->save();

		if(file_exists($skinpath . '/' . $name . '.xml')) File::delete($skinpath . '/' . $name . '.xml');
		File::create($skinpath, $name . '.xml', formatXmlString($data));
	}

	public function action_preview()
	{
		$this->_ajax = true;

		$layout = model_db_option::getKey('layout');

		model_generator_layout::$name = $layout->value;

	    $settings = file_get_contents(LAYOUTPATH . '/' . $layout->value . '/settings.json');
	    $settings = Format::forge($settings,'json')->to_array();

	    model_generator_layout::$assets = $settings['assets'];

		print Asset\Manager::insert('all');

		model_generator_module::$content = true;
		print model_generator_content::renderContent($this->content_id, Session::get('lang_prefix')) ;
	}

	public function after($response)
	{
		if(!$this->_ajax)
		$this->response->body = View::factory('admin/index',$this->data);
	}
}