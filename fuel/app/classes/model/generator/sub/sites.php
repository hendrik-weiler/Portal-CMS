<?php
/*
 * Portal Content Management System
 * Copyright (C) 2012  Hendrik Weiler
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
 * @copyright  2012 Hendrik Weiler
 */
class model_generator_sub_sites extends model_db_navigation
{
	private static function _render_sub_navigation()
	{
		$returnObj = new \stdClass;

		if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/subsites_outer.php'))
			$outerSub = View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/subsites_outer.php');
		else
			$outerSub = View::factory('public/template/subsites_outer');

		$main_navs = static::find('all',array(
			'where' => array('url_title' => model_generator_preparer::$main,'parent'=>0)
		));

		if(empty($main_navs))
			return false;

		foreach ($main_navs as $main_nav) 
		{
			$sub = static::find('first',array(
				'where' => array('parent'=>$main_nav->id,'url_title'=>model_generator_preparer::$sub)
			));	

			if(!empty($sub)) break;
		}

		$returnObj->mode = $main_nav->show_sub;

		$navigations = static::find('all',array(
			'where' => array('parent' => $main_nav->id),
			'order_by' => array('sort'=>'ASC')
		));

		if(count($navigations) == 0 || $main_nav->show_sub == 0)
			return false;

		$innerSub = '';

		foreach ($navigations as $navigation) 
		{
			if(!$navigation->show_in_navigation) continue;

			$data = array();
			$data['active_class'] = '';
			$data['target'] = '_self';
			$data['label'] = $navigation->label;
			$data['link'] = Uri::create(model_generator_preparer::$lang . '/' . $main_nav->url_title . '/' . $navigation->url_title);
			$site = model_db_site::find('first',array(
				'where' => array('navigation_id'=>$navigation->id)
			));

			if(!empty($site->redirect)) 
			{
				$data['target'] = '_blank';
				$data['link'] = $site->redirect;
			}

			$current_nav = static::find(model_generator_preparer::$currentSite->navigation_id);
			
			if($navigation->url_title == $current_nav->url_title)
				$data['active_class'] = 'active_' . $site->group_id;

			if(file_exists(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/subsites_inner.php'))
				$innerSub .= View::factory(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/subsites_inner.php',$data);
			else
				$innerSub .= View::factory('public/template/subsites_inner',$data);
		}

		$returnObj->body = str_replace('{{INNER}}', $innerSub, $outerSub);

		if(empty($innerSub)) return false;

		return $returnObj;
	}

	public static function render($content)
	{
		$sub_navigation = static::_render_sub_navigation();

		$sub_navigation_class = '';
		$content_class = '';

		if(is_object($sub_navigation) && $sub_navigation->mode != 0)
		{
			$content_class = ' sub_navigation_content';
			switch ($sub_navigation->mode) 
			{
				case 1:
					$sub_navigation_class = 'sub_navigation_left';
					break;
				
				case 2:
					$sub_navigation_class = 'sub_navigation_right';
					break;
			}
		}	

		$html = '<div class="' . $sub_navigation_class . '">';

		if(is_object($sub_navigation))
			$html .= $sub_navigation->body;

		$html .= '</div>';

		$html .= '<div class="content' . $content_class . '">';
		$html .= $content;
		$html .= '</div>';

		return $html;
	}
}