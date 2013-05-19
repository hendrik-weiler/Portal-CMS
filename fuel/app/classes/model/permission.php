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
class model_permission extends model_db_accounts
{
  public static $permissions;

  private static $currentCategory;

  private static $user;

  private static $language;

  private static $mainNavigation = array(
    0,1,2,3,4,5
  );

  private static $mainNavigationValue = array(
    'navigation','sites','news','settings','language'
  );

  private static function _setUpPermissions()
  {
    if(!is_array(self::$user))
    {
      self::$user = self::find('first',array(
        'where' => array('session'=>Session::get('session_id'))
      ));
    }

    if(!is_array(self::$permissions))
    {
      self::$permissions = Format::factory( self::$user->permissions,'json')->to_array();
    }

    if(!is_array(self::$language))
    {
      self::$language = model_db_language::find('all',array(
        'order_by' => array('sort'=>'ASC')
      ));
    }
  }

  public static function getValidLanguages()
  {
    $langs = model_db_language::getLanguages();

    foreach($langs as $key => $prefix)
    {
      if(!in_array($key,model_permission::$permissions['language']) && !self::$user['admin'])
        unset($langs[$key]);
    }

    return $langs;
  }

  public static function getNavigationRights()
  {
    $data = array();
    $data['admin'] = 1;
    $data['data'] = model_permission::$permissions['navigation_' . model_db_language::prefixToId(Session::get('lang_prefix'))];

    return $data;
  }

  public static function addLangToPermissionList($lang)
  {
    self::_setUpPermissions();

    foreach(model_db_accounts::find('all') as $user)
    {
      $permission = Format::factory( $user['permissions'],'json')->to_array();
      
      $lang = model_db_language::find('last');

      $permission['navigation_' . $lang->id] = array();
      $permission['categories_' . $lang->id] = array();

      $user->permissions = Format::factory($permission)->to_json();
      $user->save();
    }

  }

  public static function removeLangFromPermissionList($id)
  {
    self::_setUpPermissions();

    foreach(model_db_accounts::find('all') as $user)
    {
      $permission = Format::factory( $user['permissions'],'json')->to_array();
      
      $lang = model_db_language::find($id);

      unset($permission['navigation_' . $lang->id]);
      unset($permission['categories_' . $lang->id]);

      $user->permissions = Format::factory($permission)->to_json();
      $user->save();
    }
  }


  public static function addNavigationToPermissionList($nav_id)
  {
    self::_setUpPermissions();

    $permission = Format::factory( self::$user['permissions'],'json')->to_array();
    $id = model_db_language::prefixToId(Session::get('lang_prefix'));
    $permission['navigation_' . $id][] = $nav_id;

    self::$user->permissions = Format::factory($permission)->to_json();
    self::$user->save();
  }

  public static function removeNavigationFromPermissionList($nav_id)
  {
    self::_setUpPermissions();

    $permission = Format::factory( self::$user['permissions'],'json')->to_array();
    $id = model_db_language::prefixToId(Session::get('lang_prefix'));
    foreach($permission['navigation_' . $id] as $key => $value)
      if($permission['navigation_' . $id][$key] == $nav_id)
        unset($permission['navigation_' . $id][$key]);

    self::$user->permissions = Format::factory($permission)->to_json();
    self::$user->save();
  }

  public static function getNavsFromSiteId($id)
  {
    $result = array(
      'sub' => null,
      'main' => null
    );

    $site = model_db_site::find($id);

    $navi = model_db_navigation::find('first',array(
      'where' => array('id'=>$site->navigation_id)
    ));
    if(empty($navi))
      return false;
      
    if($navi->parent != 0)
    {
      $main_navi = model_db_navigation::find('first',array(
        'where' => array('id'=>$navi->parent)
      ));
      $result['main'] = $main_navi->id;
      $result['sub'] = $navi->id;
    }
    else
    {
      $result['main'] = $navi->id;
    }

    return $result;
  }

  public static function getValidRedirect()
  {
    self::_setUpPermissions();
    $lang = self::getValidLanguage();

    $language = model_db_language::find('first',array(
      'where' => array('prefix'=>$lang)
    ));
    model_db_navgroup::setLangPrefix($language->prefix);

    $key = self::$permissions['categories_' . $language->id][0];

    $return = self::$mainNavigationValue[$key];

    return $return;
  }

  public static function currentLangValid()
  {
    self::_setUpPermissions();

    if(self::$user->admin)
      return true;

    $lang_prefix = Session::get('lang_prefix');

    $result = false;
    $id = model_db_language::prefixToId($lang_prefix);
    
    if(in_array($id,array_keys(model_db_language::getLanguages())))
      $result = true;

    return $result;
  }

  public static function getValidLanguage()
  {
    self::_setUpPermissions();

    $result = null;

    if(self::$user->admin)
    {
      $lang = array_values(self::$language);
      $result = $lang[0]->prefix;
    }
    else
    {
      $result = self::$language[self::$permissions['language'][0]]->prefix;
    }

    return $result;
  }

  public static function mainNavigation()
  {
    self::_setUpPermissions();

    $result = null;

    if(self::$user->admin)
    {
      foreach(self::$language as $lang)
      {
        $result[$lang->prefix] = array(
          0 => array(
            'valid' => true,
          ),
          1 => array(
            'valid' => true,
          ),
          2 => array(
            'valid' => true,
          ),
          3 => array(
            'valid' => true,
          ),
          4 => array(
            'valid' => true,
          ),
          5 => array(
            'valid' => true,
          ),
        );
      }
    }
    else
    {
      foreach(self::$language as $lang)
      {
        $result[$lang->prefix] = array(
          0 => array(
            'valid' => false,
          ),
          1 => array(
            'valid' => false,
          ),
          2 => array(
            'valid' => false,
          ),
          3 => array(
            'valid' => false,
          ),
          4 => array(
            'valid' => false,
          ),
          5 => array(
            'valid' => false,
          ),
        );

        foreach(self::$permissions['categories_' . $lang->id] as $id)
        {
          $result[$lang->prefix][$id]['valid'] = true;
        }
      }
    }
    return $result;
  }


}