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

class Cache
{

	private static $_rootPath;

	private static function _logging($filename)
	{
		$path = DOCROOT . 'cache/';

		$content = '#----- Overwritten: ' . $filename . ' -------' . PHP_EOL . '#-> at ' . \Date::time() . PHP_EOL;

		if(!file_exists($path . 'log.txt'))
			\File::create($path,'log.txt',$content);
		else
			\File::append($path,'log.txt',$content);
	}

	private static function _getFileName($path)
	{
		$split = explode('/',$path);
		$file = $split[count($split)-1];
		return $file;
	}

	private static function _checkUpCache($file,$rootFile)
	{
		$path = DOCROOT . 'cache/' . $file . '.cache';

		if(file_exists($path))
		{
			if(filemtime(DOCROOT . 'cache/' . $file . '.cache') < filemtime($rootFile))
			{
				$content = false;
			}
			else
			{
				$content = \File::read($path);
			}
		}
		else
		{
			self::setFile($path,'');
			$content = false;
		}
		return $content;
	}

	public static function getFile($file,$rootFile)
	{
		$file = self::_getFileName($file);
		$cache = self::_checkUpCache($file,$rootFile);
		return $cache;
	}

	public static function setFile($file,$content)
	{
		$filename = self::_getFileName($file);
		$filename = str_replace('.cache','',$filename);

		$path = DOCROOT . 'cache/';
		
		if(!file_exists($path))
			\File::create($path,$filename . '.cache',$content);
		else
			\File::update(DOCROOT . 'cache/',$filename . '.cache',$content);

		self::_logging($filename);

		return \File::read($path . $filename . '.cache');
	}
}