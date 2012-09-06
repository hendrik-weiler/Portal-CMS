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
class model_generator_layout
{

  public static $assets = array();

  public static $name;

  public static function render()
  {
    $layout = model_db_option::getKey('layout');

    $settings = file_get_contents(LAYOUTPATH . '/' . $layout->value . '/settings.json');
    $settings = Format::forge($settings,'json')->to_array();

    self::$name = $layout->value;
    self::$assets = $settings['assets'];

    $data = $settings['components'];
    
    if(!empty(model_generator_preparer::$currentSite->template))
        $my_layout = View::forge(LAYOUTPATH . '/' . $layout->value . '/' . model_generator_preparer::$currentSite->template . '.php',$data);
    else
        $my_layout = View::forge(LAYOUTPATH . '/' . $layout->value . '/default.php',$data);
    
    return $my_layout;
  }

  public static function insertAsset($asset)
  {
    $asset = str_replace('->','/',$asset);

    $path = LAYOUTPATH . '/' . $asset;
    $info = pathinfo($path);
    $content = file($path);

    foreach($content as $key => $con)
      $content[$key] = trim($con);

    $content = implode('',$content);

    $html = '';
    switch ($info['extension']) 
    {
      case 'css':
        $html .= '<style>' . $content . '</style>';
        break;
      
      case 'js':
        $html .= '<script>' . $content . '</script>';
        break;
      case 'jpg':
      case 'png':
      case 'gif':
        $html .= '<img src="' . Uri::create('layout/picture/' . $asset) . '" />';
        break;
    }

    return $html;
  }
}