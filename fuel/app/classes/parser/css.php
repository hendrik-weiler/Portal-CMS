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

class Css
{

	private static $_vars;

	private static $_css;

	private static $_file;

	private static $_mode = 'full';

	private static function _sortVars()
	{
		$sortVars = array();
		$varLength = array();

		foreach(self::$_vars as $var => $value)
			$varLength[$var] = strlen($var);

		arsort($varLength);

		foreach($varLength as $key => $value)
			$sortVars[$key] = self::$_vars[$key]; 

		self::$_vars = $sortVars;
	}

	private static function _replacePlaceholders()
	{
		$mode = self::$_mode == 'full' ? PHP_EOL : '';
		$css = implode($mode,self::$_css);

		foreach(self::$_vars as $var => $value)
		{
			$css = str_replace($var,$value,$css);
		}

		return $css;
	}

	private static function _collectData($file)
	{
		
		if(preg_match('#views/public/layouts/#i',$file))
			$path = $file;
		else
			$path = DOCROOT . 'assets/css/' . $file;
	
		if(file_exists($path))
		{
			self::$_file = file($path,FILE_SKIP_EMPTY_LINES);

			#---------- get cache

			if(preg_match('#views/public/layouts/#i',$file))
			{
				$path_2 = $file;
				$path_2_2 = $file;
			}	
			else
			{
				$path_2 = 'css/' . $file;
				$path_2_2 = 'assets/css/' . $file;
			}	


			$cache = Cache::getFile($path_2,DOCROOT . $path_2_2);

			if($cache != false)
				return $cache;

			#------------------------

			$vars = array();
			$css = array();

			$inVar = false;
			$inObj = false;

			$currObj = array();

			foreach(self::$_file as $line)
			{
				if(preg_match('#/\*>#i',trim($line))) 
				{
					$inVar = true;
					continue;
				}
					
				if(preg_match('#<\*/#i',trim($line))) 
				{
					$inVar = false;
					continue;
				}

				if($inVar) 
				{
					$line = trim($line);

					if(preg_match('#^;#i',$line))
						continue;

					if(empty($line)) continue;

					if(preg_match('#^obj [\w]+#i',$line)) 
					{
						$inObj = true;
						$split = explode(' ',$line);
						$currObj[] = $split[1] . '.';
						continue;
					}

					if(preg_match('#^end#i',trim($line))) 
					{
						$inObj = false;
						array_pop($currObj);
						continue;
					}

					$varSplit = explode('=',$line);

					$key = trim($varSplit[0]);

					$url=substr(\Uri::create('/'), 0, -1);
					$piePath = \Uri::create('assets/htc/PIE.php');
                                        $include = \Uri::create('assets/img/include');

					$value = str_replace(array('"',"'",'DOCROOT','PIEPATH','INCLUDE'),array('','',$url,$piePath,$include),trim($varSplit[1]));
					if(!empty($key))
						$vars[implode('',$currObj) . trim($varSplit[0])] = $value;
				}
				else
				{
					$css[] = trim($line);
				}
			}

			self::$_vars = $vars;
			self::$_css = $css;
			self::_sortVars();

			#---------- set cache

			$cache = Cache::setFile($file,self::_replacePlaceholders());

			return $cache;
		}
		else
		{
			return false;
		}
	}

	public static function parse($file,$mode='full')
	{
		self::$_mode = $mode;

		$data = self::_collectData($file);

		if(!$data)
			return false;

		return $data;


	}
}