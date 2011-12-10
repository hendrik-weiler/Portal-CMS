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
namespace Parser;

class Js
{
  private static $_file;

  private static $_mode = 'full';

  private static function _minifyFile()
  {
    $content = file(self::$_file);

    if(self::$_mode == 'min')
    {
      foreach($content as $key => $value)
      {
        $content[$key] = trim($value);
      }
    }

    $content = implode('',$content);

    return $content;
  }

  public static function parse($file,$mode='full')
  {
    self::$_mode = $mode;

    self::$_file = DOCROOT . 'assets/js/' . $file;

    return self::_minifyFile();
  }
}