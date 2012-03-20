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

    private static function _asd()
    {


            

    }

  public function action_index()
  {
    #$this->response->set_header('Content-Type', 'text/xml; charset=utf-8');

    $sitemap = new SimpleXMLElement("<urlset></urlset>");
    $sitemap->addAttribute('xmlns', 'http://www.google.com/schemas/sitemap/0.90');

    foreach(model_db_language::find('all') as $lang)
    {
      model_db_navgroup::setLangPrefix($lang->prefix);
      model_db_site::setLangPrefix($lang->prefix);
      model_generator_navigation::setLangPrefix($lang->prefix);
      
      foreach(model_db_navgroup::find('all') as $navgroup)
      {
            $group_id = $navgroup->id;
            foreach(model_generator_navigation::getNaviAsArray($lang->id, $group_id) as $key => $nav)
            {
                    $search = model_db_site::find('first',array(
                            'where' => array('navigation_id'=>$nav['id'])
                    ));

                    if(empty($search) && !isset($nav['sub']))
                            continue;

                    $data = array();
                    $data['active_class'] = '';
                    $data['target'] = '_self';
                    $data['label'] = $nav['label'];
                    $data['link'] = Uri::create($lang->prefix . '/' . $nav['url_title']);
                    if(isset($nav['sub']))
                    {
                            $data['link'] = Uri::create($lang->prefix . '/' . $nav['url_title'] . '/' . $nav['sub'][0]['url_title']);
                    }

                    if(!empty($search->redirect)) 
                    {
                            $data['target'] = '_blank';
                            $data['link'] = $search->redirect;
                    }

                    if($nav['active'] == true)
                            $data['active_class'] = 'active';

                    $navObj = model_db_site::find('first',array('where'=>array('group_id'=>$group_id,'navigation_id'=>$nav['id'])));

                    $url = $sitemap->addChild('url');
                        $log = $url->addChild('loc',$data['link']);
                        $lastmod = $url->addChild('lastmod', Date::forge( strtotime($navObj->changed) )->format("%m/%d/%Y") );
                        $changefreq = $url->addChild('changefreq','weekly');
                        $priority = $url->addChild('priority',0.5);

                    if(isset($nav['sub']))
                    {


                            $innerHTML = array();

                            foreach($nav['sub'] as $subKey => $sub)
                            {
                                    $search = model_db_site::find('first',array(
                                            'where' => array('navigation_id'=>$sub['id'])
                                    ));
                                    if(empty($search))
                                            continue;

                                    $subData = array();
                                    $subData['active_class'] = '';
                                    $subData['target'] = '_self';
                                    $subData['label'] = $sub['label'];
                                    $subData['link'] = Uri::create($lang->prefix . '/' . $nav['url_title'] . '/' . $sub['url_title']);
                                    $subData['target'] = '_self';

                                    if($sub['active'] == true)
                                            $subData['active_class'] = 'active';

                                    if(!empty($search->redirect))
                                    {
                                            $subData['target'] = '_blank';
                                            $subData['link'] = $search->redirect;
                                    }
                                    
                                    $navObj = model_db_site::find('first',array('where'=>array('group_id'=>$group_id,'navigation_id'=>$sub['id'])));
                                    
                                    $url = $sitemap->addChild('url');
                                        $log = $url->addChild('loc',$subData['link']);
                                        $lastmod = $url->addChild('lastmod', Date::forge( strtotime($navObj->changed) )->format("%m/%d/%Y") );
                                        $changefreq = $url->addChild('changefreq','weekly');
                                        $priority = $url->addChild('priority',0.5);

                                    $subData['active_class'] = '';
                            }

                    }

                }
      }
      
    }

    $this->response->body = $sitemap->asXML();
  }
}