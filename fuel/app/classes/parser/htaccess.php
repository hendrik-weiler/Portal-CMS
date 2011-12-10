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

class Htaccess
{
    private static function _changeTpl($hostname)
    {
      $file = file(DOCROOT . '/.htaccess.tpl');
      $file[0] = '# domain:' . $hostname;

      if(isset($file[10]))
      {
        $file[10] = str_replace('|domain|',$hostname,$file[10]);
        $file[11] = str_replace('|domain|',$hostname,$file[11]);
      }

      return implode('', $file);
    }

    private static function _readHostname($file)
    {
      $file = file(DOCROOT . '/' . $file);

      preg_match('/^#(.*)\:(.*)/i',$file[0],$founds);

      return $founds[2];
    }

    public static function check()
    {
      $host = $_SERVER['HTTP_HOST'];
      $htaccess = self::_readHostname('.htaccess');

      if($host != $htaccess)
        \File::update(DOCROOT, '/.htaccess', self::_changeTpl($host));
    }

    public static function changeToBackup()
    {
      $file = file(DOCROOT . '/.htaccess.bak');
      \File::update(DOCROOT, '/.htaccess', implode('',$file));
    }
}
?>