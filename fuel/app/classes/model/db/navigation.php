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
class model_db_navigation extends Orm\Model
{

	protected static $_table_name = 'en_navigation';

	protected static $_properties = array('id', 'label', 'group_id','url_title', 'parent', 'show_in_navigation','show_sub', 'sort', 'image', 'image_is_shown');

	public static function setLangPrefix($prefix)
	{
		self::$_table_name = $prefix . '_navigation';
	}

	public static function asSelectBox($group_id='unused')
	{
		$result = array();
                
                if($group_id == 'unused')
                    $main = self::find()->where(array('parent'=>0))->order_by(array('sort'=>'ASC'))->get();
                else
                    $main = self::find()->where(array('parent'=>0,'group_id'=>$group_id))->order_by(array('sort'=>'ASC'))->get();

		foreach($main as $key => $point)
		{
			$sub = self::find()->where('parent',$point['id'])->get();

			if(empty($sub))
			{
				$result[$key] = $point['label'];
			}
			else
			{
				foreach($sub as $subKey => $subPoint)
				{
					$result[$point['label']][$subKey] = $subPoint['label'];
				}
			}
		}

		return $result;
	}
}