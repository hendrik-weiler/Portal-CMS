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
class Controller_Sitemap extends Controller
{

  private function _getUrl($lang,$nav,$subNav)
  {
    $url_parts = array();
    $url_parts[] = $lang;
    $url_parts[] = $nav->url_title;
    if(is_object($subNav))
      $url_parts[] = $subNav->url_title;

    $url = Uri::create(implode('/',$url_parts));

    return $url;
  }

  public function action_index()
  {
    $this->response->set_header('Content-Type', 'text/xml; charset=utf-8');

    $sitemap = new SimpleXMLElement("<urlset></urlset>");
    $sitemap->addAttribute('xmlns', 'http://www.google.com/schemas/sitemap/0.90');

    foreach(model_db_language::find('all') as $lang)
    {
      model_db_navigation::setLangPrefix($lang->prefix);
      foreach(model_db_navigation::find('all') as $navObj)
      {
          $url = $sitemap->addChild('url');
            $log = $url->addChild('loc',$this->_getUrl($lang->prefix,$navObj,false));
            $lastmod = $url->addChild('lastmod', Date::forge( strtotime($navObj->changed) )->format("%m/%d/%Y") );
            $changefreq = $url->addChild('changefreq','weekly');
            $priority = $url->addChild('priority',0.5);
      }
    }

    $this->response->body = $sitemap->asXML();
  }
}