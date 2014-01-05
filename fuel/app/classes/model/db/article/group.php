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
class model_db_article_group extends Orm\Model
{

	protected static $_table_name = 'article_group';

	protected static $_properties = array('id', 'label');

	public static function to_selectbox() 
	{
		$return = array(
			0 => __('shop.articles.not_set')
		);
		foreach (static::find('all') as $id => $group) {
			$return[$id] = $group->label;
		}

		return $return;
	}

	public function get_label_group() 
	{
		return Format::forge($this->label,'json')->to_array();
	}

	public function get_label($lang_prefix) 
	{
		$labels = Format::forge($this->label,'json')->to_array();

		if($lang_prefix == '') {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		if(!isset($labels[$lang_prefix]) or $labels[$lang_prefix] == '') {
			$lang_prefix = model_db_language::find('first',array(
				'where' => array('sort'=>0)
			))->prefix;
		}

		return $labels[$lang_prefix];
	}

}