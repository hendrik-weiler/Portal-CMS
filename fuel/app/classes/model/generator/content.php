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
class model_generator_content extends model_db_site
{

	private static $_tempLang = null;

	private static $_renderSpecial = false;

	private static $_width_counter = 0;

	private static function _viewSite($site,$directly=false)
	{
		if(!$directly)
		{
			if($site == null && Uri::segment(2) != 'news')
				return false;
				
			if(Uri::segment(2) == 'news' && !self::$_renderSpecial)
			{
				if(Uri::segment(3) == '')
				{
					Response::redirect(model_generator_preparer::$lang);
				}
				if(Uri::segment(3) == 'archive')
				{
						$options = model_generator_preparer::$options;

						$news = model_db_news::find()
						->limit(32700)
						->offset($options['show_last'])
						->order_by(array('creation_date'=>'DESC'))
						->get();

						$data = array();
						$data['entries'] = self::_showMultipleNews($news);

                        if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_archive.php'))
                                return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_archive.php',$data);
						else
                            return View::factory('public/template/news_archive',$data);
				}
				$news = model_db_news::find(Uri::segment(3));

                if(empty($news))
					Response::redirect(model_generator_preparer::$lang);
					
				return self::_showSingleNews($news,true);
			}
		}


		$return = '';

		$contents = model_db_content::find('all',array(
			'where' => array('site_id'=>$site->id),
			'order_by' => array('sort'=>'ASC')
		));

		foreach($contents as $content)
			$return .= self::_viewContent($content);

		return $return;
	}

	private static function _viewContent($content)
	{
			if($content == null)
				return false;

			$return = '';

			$style = model_db_content::genStyleFromClassname($content->classname);

			$data_inline_edit_content_id = '';
			$data_inline_edit_site_id = '';
			$data_inline_edit_type_id = '';
			if(model_db_option::getKey('inline_edit')->value && model_auth::check())
			{
				if($content->type != 4)
				{
					$data_inline_edit_content_id = ' data-inline-edit-content-id="' . $content->id . '"';
					$data_inline_edit_site_id = ' data-inline-edit-site-id="' . $content->site_id . '"';
					$data_inline_edit_type_id = ' data-inline-edit-type-id="' . $content->type . '"';
				}
			}
				
			$return .= '<div class="width_' . $style->type . '" ' . $style->style . $data_inline_edit_content_id . $data_inline_edit_site_id . $data_inline_edit_type_id . '>';
			switch($content->type)
			{
				case 7:
				case 6:
				case 1:
					$return .= self::_showTextcontainer($content);
					break;
				case 2:
					$return .= self::_showContactform($content);
					break;
				case 3:
					$return .= self::_showGallery($content);
					break;
				case 4:
					$options = model_generator_preparer::$options;

					$news = model_db_news::find('all',array(
						'limit' => $options['show_last'],
						'order_by' => array('creation_date'=>'DESC')
					));
					$return .= self::_showMultipleNews($news);
					break;
				case 5:
					$cols = Format::factory($content->refer_content_id,'json')->to_array();

					$col_1 = model_db_content::find($cols['col_1']);
					$data = array();
					if($col_1 != 0)
					{
						$data['text'] = '';
						$data['group'] = 'group_' . model_generator_preparer::$lang . '_' . $content->id;
						if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_1column.php'))
	                    {
							$data['text'] = self::_viewContent($col_1);
	                    	$return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_1column.php',$data);
	                    }    
	                    else
	                    {
	                    	$return .= View::factory('public/template/content_reference_1column',$data);
	                    }   
                    }
					break;
				case 8:
					$cols = Format::factory($content->refer_content_id,'json')->to_array();

					$col_1 = model_db_content::find($cols['col_1']);
					$col_2 = model_db_content::find($cols['col_2']);
					$data = array();
					$data['text'] = '';
					$data['text2'] = '';
					$cols['col_1'] != 0 and $data['text'] = self::_viewContent($col_1);
					$cols['col_2'] != 0 and $data['text2'] = self::_viewContent($col_2);
					$data['group'] = 'group_' . model_generator_preparer::$lang . '_' . $content->id;
					
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_2column.php'))
                        $return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_2column.php',$data);
                    else
                        $return .= View::factory('public/template/content_reference_2column',$data);
					break;
				case 9:
					$cols = Format::factory($content->refer_content_id,'json')->to_array();

					$col_1 = model_db_content::find($cols['col_1']);
					$col_2 = model_db_content::find($cols['col_2']);
					$col_3 = model_db_content::find($cols['col_3']);
					$data = array();
					$data['text'] = '';
					$data['text2'] = '';
					$data['text3'] = '';
					$data['text'] != 0 and $data['text'] = self::_viewContent($col_1);
					$data['text2'] != 0 and $data['text2'] = self::_viewContent($col_2);
					$data['text3'] != 0 and $data['text3'] = self::_viewContent($col_3);
					$data['group'] = 'group_' . model_generator_preparer::$lang . '_' . $content->id;
                                        
                                        if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_3column.php'))
                                            $return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/content_reference_3column.php',$data);
                                        else
                                            $return .= View::factory('public/template/content_reference_3column',$data);
					break;
				case 10:
                                        $return .= self::_viewFlash($content);
                                        break;
                                case 11:

					$data = array();
					$data['html'] = str_replace(array(
                                            'DOCROOT',
                                            'INCLUDE',
                                        ),array(
                                            \Uri::create('/'),
                                            \Uri::create('/assets/img/include'),
                                        ),$content->text);

					$parameter = json_decode($content->parameter,true);
					if(is_array($parameter))
					{
						foreach ($parameter as $placeholder) 
						{
							$data['html'] = str_replace(
								$placeholder['name'],
								$placeholder['text'],
								$data['html']);
						}
					}
                                        
                                        if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/html.php'))
                                            $return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/html.php',$data);
                                        else
                                            $return .= View::factory('public/template/html',$data);
                                break;
			case 12:
			$params = json_decode($content->parameter,true);

			$split = explode('\\',$params['active']);
			$data = array();

			if(empty($params['active']))
			{
				$data['content'] = 'No plugin selected.';
			}
			else
			{
				require_once APPPATH . '../../plugin/' . $split[0] .'/' . $split[1] . '.php';
				$plugin = new $params['active']();
				$plugin->param = $params;
				$data['content'] = $plugin->render();
			}
			$data['title'] = $content->label;
            if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/plugin.php'))
                $return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/plugin.php',$data);
            else
                $return .= View::factory('public/template/plugin',$data);
			break;

			case 13:

			$data = array();

			$content->text == '' and $content->text = '[]';
			$data += Format::forge($content->text,'json')->to_array();
			$data = array_map(function($key) {
				return str_replace(array('\"',"\'"),array('"',"'"),$key);
			}, $data);
			$data['content_id'] = $content->id;

			$filepath = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/custom/' . $content->label;
			
            if(file_exists($filepath ) && !is_dir($filepath))
            {
            	$return .= View::factory($filepath,$data);
            }
            else
            	$return .= '<strong>' . $content->label . '</strong> not found.';

			break;

			case 14:

			$content->parameter == '' and $content->parameter = '[]';
			$data = Format::forge($content->parameter,'json')->to_array();

			$data['content_id'] = $content->id;
			$data['title'] = $content->label;

			if(!isset($data['video_name'])) $data['video_name'] = '';
			if(!isset($data['video_preview'])) $data['video_preview'] = '';
			if(!isset($data['video_file'])) $data['video_file'] = '';

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

			if(!isset($data['height']))
			{
				$data['height'] = 300;
			}

			if(!isset($data['width']))
			{
				$data['width'] = 600;
			}

			if($data['video_name'] != 'none')
			{
				$data['filepath'] = Uri::create('player/serve/video/' . $data['video_name']);
			}

			if($data['video_file'] != '')
			{
				if(model_generator_preparer::$lang == '') model_generator_preparer::$lang = Session::get('lang_prefix');
				$data['filepath'] = Uri::create('uploads/' . model_generator_preparer::$lang . '/video/' . $content->id . '/' . $data['video_file']);
			}

			$data['filepath'] .= '?' . time();

			$data['previewpath'] = '';
			if($data['video_preview'] != '')
			{
				if(model_generator_preparer::$lang == '') model_generator_preparer::$lang = Session::get('lang_prefix');
				$data['previewpath'] = Uri::create('uploads/' . model_generator_preparer::$lang . '/video/' . $content->id . '/' . $data['video_preview']);
			}

			if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/player.swf'))
			{
				$data['videoplayerpath'] = Uri::create('player/serve/player'); 
			}
			else
			{
				$data['videoplayerpath'] = Uri::create('assets/swf/player.swf');
			}

            if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/flvvideoplayer.php'))
                $return .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/flvvideoplayer.php',$data);
            else
                $return .= View::factory('public/template/flvvideoplayer',$data);

			break;


			}

			$return .= '</div>';

			static::$_width_counter += $style->value;
			if(static::$_width_counter >= 96)
			{
				$return .= '<br style="clear:left;" />';
				static::$_width_counter = 0;
			}

			return $return;
	}

	private static function _viewFlash($content)
	{
		$params = $content->parameter;
		$params = explode(PHP_EOL,$params);
		$params_parsed = array();
		foreach($params as $line)
		{
			if(preg_match('#(.*)=(.*)#i',$line))
			{
				$split = explode('=',$line);
				$value = trim($split[1]);
				if(preg_match_all('#\$([\w]+)\[([\w]+)\]#i',$value,$matches))
				{
					$ext = $matches[2][0];
					if($matches[1][0] == 'language')
						$value = model_generator_preparer::$lang . '.' . $ext;

					if($matches[1][0] == 'sitename')
						$value = model_generator_preparer::$currentSite->label . '.' . $ext;
				}					

				$param_parsed[trim($split[0])] = $value;
			}
		}

		$data = array();

		if(!isset($param_parsed))
			$param_parsed = array();
			
		$data['params'] = Format::factory($param_parsed)->to_json();
		$data['group'] = 'group_' . $content->id;
		$data['wmode'] = $content->wmode;
		
		$dimensions = explode(';',$content->dimensions);
		$data['height'] = (!isset($dimensions[1])) ? 640 : $dimensions[0];
		$data['width'] = (!isset($dimensions[1])) ? 480 : $dimensions[1];

		$path = 'uploads/' . model_generator_preparer::$lang . '/flash/' . $content->id . '/';
		$data['picture'] = Uri::create($path . $content->pictures);
		$data['swfPath'] = Uri::create($path . $content->flash_file);
		$data['title'] = $content->label;

                if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/flash.php'))
                    return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/flash.php',$data);
                else
                    return View::factory('public/template/flash',$data);
	}

	private static function _showContactform($content)
	{
		$data = array();
		$data += Format::factory( $content->form,'json')->to_array();
		if(isset($_POST['contact_submit']))
		{
			$required = array();
			$val = Validation::instance( );
			foreach($data as $key => $value)
			{
				if(preg_match('#([\w\_\-\.]+)_required#i',$key))
				{
					$required[] = $key;
					$val->add_field(str_replace('_required','_text',$key), Input::post(str_replace('_required','_text',$key)), 'required|min_length[3]');
				}
			}

			if ($val->run())
			{
				$time = date(__('news.dateformat'),strtotime(Date::time()));
				$regard = $content->label . ' - ' . $time;
				$mail_content = array();
				$mail_content['title'] = $content->label;
				$mail_content['data'] = $_POST;
				$mail_content['time'] = $time;
				unset($mail_content['data']['contact_submit']);
				$message = View::factory('public/template/contactform_email',$mail_content);
			  mail($data['sendTo'], $regard, $message);
			  $data['success'] = __('contactform.success');
			}
			else
			{
					$errors = $val->errors();
			    foreach($required as $key)
			    {
			    	if(isset($errors[str_replace('_required','_text',$key)]))
			    		$data[str_replace('_required','_text',$key) . '_error'] = array('class'=>'error');
			    }
			}

			$data += $_POST;
		}
                
                if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/contactform.php'))
                    return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/contactform.php',$data);
                else
                    return View::factory('public/template/contactform',$data);
	}

	private static function _showMultipleNews($news,$full_view=false)
	{
		$result = '';

		foreach($news as $new)
		{
			$result .= self::_showSingleNews($new,$full_view);
		}

		if(count($news) == 0)
			$result .= __('news.no_data');

		return $result;
	}

	private static function _showSingleNews($new,$full_view=false)
	{
            
			$options = model_generator_preparer::$options;

			$data = array();
			$data['title'] = $new->title;
			$data['full_text'] = $new->text;

			$new->text = strip_tags($new->text,'<span><h1><h2><h3><h4><h5><h6><p><a><br>');
			#$short_text = explode("\n", wordwrap($new->text, $options['show_max_token'], "\n"));
			$short_text = substr( $new->text, 0, @strpos( $new->text, ".", $options['show_max_token'] )+1 );
			$data['short_text'] = $short_text;#$short_text[0];

			$date = new DateTime($new->creation_date);
			$data['time'] = $date->format(__('news.dateformat'));

			$data['fullview_link'] = Uri::create(model_generator_preparer::$lang . '/news/' . $new->id . '/' . Inflector::friendly_title($new->title));

			if(strlen($new->text) <= $options['show_max_token'])
			{
				//$data['fullview_link'] = '';
				$data['short_text'] = $new->text;
			}	

			$pictures = Format::factory( $new->picture , 'json')->to_array();

			if(isset($pictures['picture_1'])) {
				$data['picture_1'] = Uri::create(str_replace('/original/','/thumb/',$pictures['picture_1']));
				$data['picture_1_big'] = Uri::create(str_replace('/original/','/big/',$pictures['picture_1']));
				$data['picture_1_original'] = Uri::create($pictures['picture_1']);
			}
				
			if(isset($pictures['picture_2'])) {
				$data['picture_2'] = Uri::create(str_replace('/original/','/thumb/',$pictures['picture_2']));
				$data['picture_2_big'] = Uri::create(str_replace('/original/','/big/',$pictures['picture_2']));
				$data['picture_2_original'] = Uri::create($pictures['picture_2']);
			}
				
			if(isset($pictures['picture_3'])) {
				$data['picture_3'] = Uri::create(str_replace('/original/','/thumb/',$pictures['picture_3']));
				$data['picture_3_big'] = Uri::create(str_replace('/original/','/big/',$pictures['picture_3']));
				$data['picture_3_original'] = Uri::create($pictures['picture_3']);
			}

			if($full_view)
			{
				$html = '';
                                if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_full.php'))
                                    $html .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_full.php',$data);
                                else
                                    $html .= View::factory('public/template/news_full',$data);
				
				if(!empty($new->attachment) || $new->attachment != 0)
					$html .= self::_viewSite(model_db_site::find($new->attachment),true);
				return $html;
			}
			else
			{
                            if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_short.php'))
                                return View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/news_short.php',$data);
                            else
                                return View::factory('public/template/news_short',$data);
                        }
	}

	private static function _showTextcontainer($content)
	{
		$data = array();
		$data['label'] = stripslashes($content->label);
		$data['text'] = stripslashes($content->text);
		$data['text2'] = stripslashes($content->text2);
		$data['text3'] = stripslashes($content->text3);
		$data['group'] = 'group_' . model_generator_preparer::$lang . '_' . $content->id;

		if($content->type == 7)
		{
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/3columns.php'))
                        $tpl = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/3columns.php';
                    else
                        $tpl = 'public/template/3columns';
                }
		else if($content->type == 6)
		{
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/2columns.php'))
                        $tpl = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/2columns.php';
                    else
                        $tpl = 'public/template/2columns';
                }
		else
		{
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/1columns.php'))
                        $tpl = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/1columns.php';
                    else
                        $tpl = 'public/template/1columns';
                }

		return View::factory($tpl,$data);
	}

	private static function _showGallery($content)
	{
		$data = array();
		$data['label'] = stripslashes($content->label);
		$pictures = array();
		$description = explode(PHP_EOL,$content->text);

		$counter = 0;

		self::$_tempLang = !empty(self::$_tempLang) ? self::$_tempLang : model_generator_preparer::$lang;

		if(is_dir(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id)
			&& is_dir(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/thumbs')
			&& is_dir(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/original'))
		{

                    $images = File::read_dir(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/thumbs',1);
                    if(!empty($images))
                    {
                        $info = getimagesize(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/big/' . $images[0]);
                        $data['slideshow_height'] = $info[1];
                        $data['slideshow_width'] = $info[0];
                    }

                    foreach($images as $pic)
                    {
                            $pictures[$counter] = array();
                            $pictures[$counter]['thumb'] = Uri::create('uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/thumbs/' . $pic);

                            if(isset($description[$counter]))
                                    $pictures[$counter]['description'] = stripslashes($description[$counter]);
                            else
                                    $pictures[$counter]['description'] = '';

                            $pictures[$counter]['original'] = Uri::create('uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/original/' . $pic);
                            $pictures[$counter]['big'] = Uri::create('uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/big/' . $pic);
                            $counter++;
                    }
                
                }

		$data['pictures'] = $pictures;
		$data['group'] = 'group_' . model_generator_preparer::$lang . '_' . $content->id;
		if($content->pictures == 'lightbox')
		{
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/gallery_lightbox.php'))
                        $path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/gallery_lightbox.php';
                    else
                        $path = 'public/template/gallery_lightbox';
                }
		else if($content->pictures == 'slideshow')
		{
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/gallery_slideshow.php'))
                        $path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/gallery_slideshow.php';
                    else
                        $path = 'public/template/gallery_slideshow';
                }
		else 
                {
                    if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/' . $content->pictures . '.php'))
                        $path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/' . $content->pictures . '.php';
                    else 
			$path = 'public/template/' . $content->pictures;

			if($content->pictures == 'custom/')
				$path = 'public/template/gallery_lightbox';
		}
			
		return View::factory($path,$data);
	}

	public static function render()
	{
		if(!model_generator_module::$content)
			return;
		
		$current_site = model_generator_preparer::$currentSite;

                if($current_site == null && Uri::segment(2) != 'news')
                {
                    return View::forge('public/errors/error_no_site');
                }
                
		$site = self::_viewSite($current_site);

                model_db_content::setLangPrefix(model_generator_preparer::$lang);
                if(is_object($current_site) && count(model_db_content::find('first',array('where'=>array('site_id'=>$current_site->id)))) == 0 && Uri::segment(2) != 'news')
                    return View::forge('public/errors/error_no_content');
               
		if(!$site && $site != '')
            Response::redirect(model_generator_preparer::$lang);

		return $site;
	}

	public static function renderSite($sitename,$lang='auto')
	{
		if(!model_generator_module::$content)
			return;

		if(preg_match('#^[0-9]+$#i',$sitename))
		{
			$search = array(
				'key' => 'id',
				'value' => $sitename
			);
		}
		else
		{
			$search = array(
				'key' => 'label',
				'value' => $sitename
			);
		}

		if($lang == 'auto')
			self::$_tempLang = model_generator_preparer::$lang;
		else 
			self::$_tempLang = $lang;

		self::$_renderSpecial = true;
			
		$current_site = DB::select('*')->from(self::$_tempLang . '_site')->where(array($search['key']=>$search['value']))->execute();
        $current_site = array_values($current_site->as_array());
        if(isset($current_site[0]))
		$current_site = (object)$current_site[0];

		$site = self::_viewSite($current_site);

		if(!$site)
			$site = 'Site couldnt be found.';

		self::$_tempLang = model_generator_preparer::$lang;
		self::$_renderSpecial = false;

		return $site;
	}

	public static function renderContent($contentname,$lang='auto')
	{

		if(!model_generator_module::$content)
			return;
			
		if(preg_match('#^[0-9]+$#i',$contentname))
		{
			$search = array(
				'key' => 'id',
				'value' => $contentname
			);
		}
		else
		{
			$search = array(
				'key' => 'label',
				'value' => $contentname
			);
		}

		if($lang == 'auto')
			self::$_tempLang = model_generator_preparer::$lang;
		else
			self::$_tempLang = $lang;

		self::$_renderSpecial = true;

		$content = DB::select('*')->from(self::$_tempLang . '_content')->where(array($search['key']=>$search['value']))->execute();
		$content = array_values($content->as_array());
		$content = (object)$content[0];
		
		$site = self::_viewContent($content);

		model_db_content::setLangPrefix(model_generator_preparer::$lang);

		if(!$site)
			$site = 'Site couldnt be found.';

		self::$_tempLang = model_generator_preparer::$lang;
		self::$_renderSpecial = false;

		return $site;
	}
}
