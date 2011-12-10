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
class model_generator_module
{
  public static $content = false;

  public static $navigation = false;

  public static $seo_head = false;

  public static $seo_analytics = false;

  public static $language_switcher = false;

  public static function checkStatus()
  {
    $option = model_db_option::getKey('module_content')->value;
    self::$content = $option;

    $option = model_db_option::getKey('module_navigation')->value;
    self::$navigation = $option;
      
    $option = model_db_option::getKey('module_seo_head')->value;
    self::$seo_head = $option;
      
    $option = model_db_option::getKey('module_seo_analytics')->value;
    self::$seo_analytics = $option;
      
    $option = model_db_option::getKey('module_language_switcher')->value;
    self::$language_switcher = $option;

    # assets

    if(!model_db_option::getKey('asset_jquery')->value)
      Asset\Manager::$usedAssetGroups[] = '1_jquery';

    if(!model_db_option::getKey('asset_modernizr')->value)
      Asset\Manager::$usedAssetGroups[] = '0_modernizr';

    if(!model_db_option::getKey('asset_nivo_slider')->value)
      Asset\Manager::$usedAssetGroups[] = '3_nivo-slider';
      
    if(!model_db_option::getKey('asset_colorbox')->value)
      Asset\Manager::$usedAssetGroups[] = '2_colorbox';

    if(!model_db_option::getKey('asset_swfobject')->value)
      Asset\Manager::$usedAssetGroups[] = '4_swfobject';

    if(!model_db_option::getKey('asset_custom')->value)
      Asset\Manager::$usedAssetGroups[] = '100_custom';
  }
}