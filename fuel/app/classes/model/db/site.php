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
class model_db_site extends Orm\Model
{

	protected static $_table_name = 'en_site';

	protected static $_properties = array('id', 'navigation_id', 'group_id', 'label', 'url_title', 'site_title', 'template','keywords', 'description','redirect', 'sort', 'changed');

	public static function setLangPrefix($prefix)
	{
		self::$_table_name = $prefix . '_site';
	}

  public static function asSelectBox($lang)
  {
    self::setLangPrefix($lang);

    $result = array();

    $main = self::find('all');

    foreach($main as $key => $point)
      $result[$key] = $point->label;

    return $result;
  }
  
  public static function getLayoutFromFolder()
  {
    $return = array();
    foreach(File::read_dir(
            APPPATH . 'views/public/layouts/' . model_db_option::getKey('layout')->value,
            1,
            array('php')) as $file)
    {
        if($file != 'default.php')
            $return[str_replace('.php','',$file)] = $file;
    }
    
    return $return;
            
  }
}