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

	private static function _viewSite($site)
	{
		if($site == null && Uri::segment(2) != 'news')
			return false;
			
		if(Uri::segment(2) == 'news')
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
					return View::factory('public/template/news_archive',$data);
			}
			$news = model_db_news::find(Uri::segment(3));
			if(empty($news))
				Response::redirect(model_generator_preparer::$lang);
				
			return self::_showSingleNews($news,true);
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
					$data['text'] = self::_viewContent($col_1);
					$data['group'] = 'group_' . $content->id;
					$return .= View::factory('public/template/1columns',$data);
					break;
				case 8:
					$cols = Format::factory($content->refer_content_id,'json')->to_array();

					$col_1 = model_db_content::find($cols['col_1']);
					$col_2 = model_db_content::find($cols['col_2']);
					$data = array();
					$data['text'] = self::_viewContent($col_1);
					$data['text2'] = self::_viewContent($col_2);
					$data['group'] = 'group_' . $content->id;
					$return .= View::factory('public/template/2columns',$data);
					break;
				case 9:
					$cols = Format::factory($content->refer_content_id,'json')->to_array();

					$col_1 = model_db_content::find($cols['col_1']);
					$col_2 = model_db_content::find($cols['col_2']);
					$col_3 = model_db_content::find($cols['col_3']);
					$data = array();
					$data['text'] = self::_viewContent($col_1);
					$data['text2'] = self::_viewContent($col_2);
					$data['text3'] = self::_viewContent($col_3);
					$data['group'] = 'group_' . $content->id;
					$return .= View::factory('public/template/3columns',$data);
					break;
					case 10:
						$return .= self::_viewFlash($content);
						break;
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
		$data['params'] = Format::factory($param_parsed)->to_json();
		$data['group'] = 'group_' . $content->id;
		$data['wmode'] = $content->wmode;
		
		$dimensions = explode(';',$content->dimensions);
		$data['height'] = (!isset($dimensions[1])) ? 640 : $dimensions[0];
		$data['width'] = (!isset($dimensions[1])) ? 480 : $dimensions[1];

		$path = 'uploads/' . model_generator_preparer::$lang . '/flash/' . $content->id . '/';
		$data['picture'] = Uri::create($path . $content->pictures);
		$data['swfPath'] = Uri::create($path . $content->flash_file);

		return View::factory('public/template/flash',$data);
	}

	private static function _showContactform($content)
	{
		$data = array();
		$data += Format::factory( $content->form,'json')->to_array();
		if(isset($_POST['contact_submit']))
		{
			$required = array();

			$val = Validation::factory('my_validation');
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

		return View::factory('public/template/contactform',$data);
	}

	private static function _showMultipleNews($news,$full_view=false)
	{
		$result = '';

		foreach($news as $new)
		{
			$result .= self::_showSingleNews($new,$full_view);
		}
		return $result;
	}

	private static function _showSingleNews($new,$full_view=false)
	{
			$options = model_generator_preparer::$options;

			$data = array();
			$data['title'] = $new->title;
			$data['full_text'] = $new->text;

			$short_text = explode("\n", wordwrap($new->text, $options['show_max_token'], "\n"));
			$data['short_text'] = $short_text[0];

			$date = new DateTime($new->creation_date);
			$data['time'] = $date->format(__('news.dateformat'));

			$data['fullview_link'] = Uri::create(model_generator_preparer::$lang . '/news/' . $new->id . '/' . Inflector::friendly_title($new->title));

			$pictures = Format::factory( $new->picture , 'json')->to_array();

			if(isset($pictures['picture_1'])) {
				$data['picture_1'] = Uri::create(str_replace('/original/','/',$pictures['picture_1']));
				$data['picture_1_original'] = Uri::create($pictures['picture_1']);
			}
				
			if(isset($pictures['picture_2'])) {
				$data['picture_2'] = Uri::create(str_replace('/original/','/',$pictures['picture_2']));
				$data['picture_2_original'] = Uri::create($pictures['picture_2']);
			}
				
			if(isset($pictures['picture_3'])) {
				$data['picture_3'] = Uri::create(str_replace('/original/','/',$pictures['picture_3']));
				$data['picture_3_original'] = Uri::create($pictures['picture_3']);
			}

			if($full_view)
				return View::factory('public/template/news_full',$data);
			else
				return View::factory('public/template/news_short',$data);
	}

	private static function _showTextcontainer($content)
	{
		$data = array();
		$data['label'] = $content->label;
		$data['text'] = $content->text;
		$data['text2'] = $content->text2;
		$data['text3'] = $content->text3;
		$data['group'] = 'group_' . $content->id;

		if($content->type == 7)
			$tpl = 'public/template/3columns';
		else if($content->type == 6)
			$tpl = 'public/template/2columns';
		else
			$tpl = 'public/template/1columns';

		return View::factory($tpl,$data);
	}

	private static function _showGallery($content)
	{
		$data = array();
		$data['label'] = $content->label;
		$pictures = array();
		$description = explode(PHP_EOL,$content->text);

		$counter = 0;

		$images = File::read_dir(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/thumbs',1);

		$info = getimagesize(DOCROOT . 'uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/original/' . $images[0]);
		$data['slideshow_height'] = $info[1];
		$data['slideshow_width'] = $info[0];

		foreach($images as $pic)
		{
			$pictures[$counter] = array();
			$pictures[$counter]['thumb'] = Uri::create('uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/thumbs/' . $pic);
			
			if(isset($description[$counter]))
				$pictures[$counter]['description'] = $description[$counter];
			else
				$pictures[$counter]['description'] = '';
			
			$pictures[$counter]['original'] = Uri::create('uploads/' . self::$_tempLang . '/gallery/' . $content->id . '/original/' . $pic);
			$counter++;
		}

		$data['pictures'] = $pictures;
		$data['group'] = 'group_' . $content->id;
		if($content->pictures == 'lightbox')
			$path = 'public/template/gallery_lightbox';
		else if($content->pictures == 'slideshow')
			$path = 'public/template/gallery_slideshow';
		else {
			$path = 'public/template/' . $content->pictures;
		}
			
		return View::factory($path,$data);
	}

	public static function render()
	{

		$current_site = model_generator_preparer::$currentSite;

		$site = self::_viewSite($current_site);

		if(!$site)
			Response::redirect(model_generator_preparer::$lang);

		return $site;
	}

	public static function renderSite($sitename,$lang='auto')
	{
		if(preg_match('#[0-9]+#i',$sitename))
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

		$current_site = DB::select('*')->from(self::$_tempLang . '_site')->where(array($search['key']=>$search['value']))->execute();
		$current_site = array_values($current_site->as_array());
		$current_site = (object)$current_site[0];

		$site = self::_viewSite($current_site);

		if(!$site)
			$site = 'Site couldnt be found.';

		self::$_tempLang = model_generator_preparer::$lang;

		return $site;
	}

	public static function renderContent($contentname,$lang='auto')
	{

		if(preg_match('#[0-9]+#i',$contentname))
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

		$content = DB::select('*')->from(self::$_tempLang . '_content')->where(array($search['key']=>$search['value']))->execute();
		$content = array_values($content->as_array());
		$content = (object)$content[0];
		
		$site = self::_viewContent($content);

		model_db_content::setLangPrefix(model_generator_preparer::$lang);

		if(!$site)
			$site = 'Site couldnt be found.';

		self::$_tempLang = model_generator_preparer::$lang;

		return $site;
	}
}