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
class Controller_Generator_File extends Controller
{

  public function action_index()
  {
    $this->response->set_header('Content-Type', 'text/css; charset=utf-8');

    $file = $this->param('path');

    $this->response->body = Parser\Css::parse($file,'min');
  }

  public function action_css()
  {
    $this->response->set_header('Content-Type', 'text/css; charset=utf-8');

    $cmd = $this->param('cmd');

    if(isset($_GET['exclude']))
      $usedGroups = explode(',',$_GET['exclude']);
    else
      $usedGroups = array();

    if(isset($_GET['layout_asset']))
      $layout_asset = explode(',',$_GET['layout_asset']);
    else
      $layout_asset = array();

    if(empty($cmd))
    {
      foreach(scandir(DOCROOT . '/assets/css/include',1) as $dir)
      {
        if(is_dir(DOCROOT . '/assets/css/include/' . $dir) 
          && !in_array($dir,array('.','..')) 
          && !in_array($dir,$usedGroups))
        {
          foreach(File::read_dir(DOCROOT . '/assets/css/include/' . $dir,1) as $file)
            $this->response->body .= Parser\Css::parse('include/' . $dir . '/' . $file,'min');
        }
      }
      foreach($layout_asset as $asset)
      {
        $path = LAYOUTPATH . '/' . $_GET['layout_name'] . '/' . $asset;

        $info = pathinfo($path);

        if($info['extension'] == 'css')
          $this->response->body .= Parser\Css::parse($path,'min');
      }
    }
    else
    {
        if(is_dir(DOCROOT . '/assets/css/include/' . $cmd))
        {
          foreach(File::read_dir(DOCROOT . '/assets/css/include/' . $cmd,1) as $file)
            $this->response->body .= Parser\Css::parse('include/' . $dir . '/' . $file,'min');
        }
    }
  }
  
  public function action_js()
  {
    $this->response->set_header('Content-Type', 'application/javascript; charset=utf-8');
    $cmd = $this->param('cmd');

    if(isset($_GET['exclude']))
      $usedGroups = explode(',',$_GET['exclude']);
    else
      $usedGroups = array();

    if(isset($_GET['layout_asset']))
      $layout_asset = explode(',',$_GET['layout_asset']);
    else
      $layout_asset = array();

    if(empty($cmd))
    {
      foreach(scandir(DOCROOT . '/assets/js/include') as $dir)
      {
        if(is_dir(DOCROOT . '/assets/js/include/' . $dir) 
        && !in_array($dir,array('.','..'))
        && !in_array($dir,$usedGroups))
        {
          foreach(File::read_dir(DOCROOT . '/assets/js/include/' . $dir,1) as $file)
          {
            $this->response->body .= PHP_EOL . '// --------------- ' . $file . ' ----------------- ' . PHP_EOL;
            $this->response->body .= Parser\Js::parse('include/' . $dir . '/' . $file,'full');
          }  
        }
      }
      foreach($layout_asset as $asset)
      {
        $path = LAYOUTPATH . '/' . $_GET['layout_name'] . '/' . $asset;
        $info = pathinfo($path);

        if($info['extension'] == 'js')
          $this->response->body .= Parser\Js::parse($path,'min');
      }
    }
    else
    {
        if(is_dir(DOCROOT . '/assets/js/include/' . $cmd))
        {
          foreach(File::read_dir(DOCROOT . '/assets/js/include/' . $cmd,1) as $file)
          {
            $this->response->body .= PHP_EOL . '// --------------- ' . $file . ' ----------------- ' . PHP_EOL;
            $this->response->body .= Parser\Js::parse('include/' . $cmd . '/' . $file,'full');
          }
        }
    }
  }
}