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
namespace Asset;

class Manager 
{
	private static $assetPath;

	private static $cssTemplate = '<link %attr% rel="stylesheet" href="%link%" />';

	private static $jsTemplate = '<script %attr% src="%link%"></script>';

	private static $imgTemplate = '<img %attr% src="%link%" />';

	public static $lastAsset;

	private static function _writeAttributes($attr)
	{
		$html = '';

		foreach($attr as $key => $value)
			$html .= $key . '="' . $value . '" ';

		return $html;
	}

    private static function _findFile($file)
    {
    	$construct = explode('/',$file);
    	$file = $construct[count($construct)-1];
    	unset($construct[count($construct)-1]);
    	$path = implode('/',$construct);

    	$return = false;

    	if(!is_dir(self::$assetPath . $path))
    		return $return;

    	foreach(\File::read_dir(self::$assetPath . $path,1) as $filename)
    	{
    		if(preg_match('#' . $file . '#i',$filename))
    		{
    			$return = true;
    			self::$lastAsset = pathinfo(self::$assetPath . $path . '/' . $filename);
    			self::$lastAsset['fullpath'] = \Uri::create('assets/' . $path . '/' . $filename );
    			self::$lastAsset['path'] = $path;
    			break;
    		}
    	}

    	return $return;
    }

  private static function _getDir($type)
  {
    $html = '';
    foreach(\File::read_dir(DOCROOT . 'assets/' . $type . '/include',1) as $file)
    {
      $html .= self::get($type . '->include->' . $file);
    }

    return $html;
  }

  public static function insert($type='all')
  {
    $html = '';

    switch($type)
    {
      case 'all':
        $html .= self::_getDir('css');
        $html .= self::_getDir('js');
      break;
      case 'js':
      case 'css':
        $html .= self::_getDir($type);
      break;
    }
    return $html;
  }

	public static function get($search,$attr=array())
	{
		self::$assetPath = DOCROOT . 'assets/';

		if(preg_match('#([\w\.\-]+)#i',$search))
		{
			$search = str_replace('->','/',$search);

			if(self::_findFile($search))
			{
				if(in_array(strtolower(self::$lastAsset['extension']),array('jpg','jpeg','bmp','png','gif')))
					$tpl_key = 'img';
				else
					$tpl_key = self::$lastAsset['extension'];
				
				$template = strtolower($tpl_key) . 'Template';

				if(self::$lastAsset['extension'] == 'css')
				{
					$dirname = str_replace('css/','',self::$lastAsset['path']);
					$path = \Uri::create('parse/file/' . $dirname . '/' . self::$lastAsset['filename'] . '.' . self::$lastAsset['extension']);
				}
				else
					$path = self::$lastAsset['fullpath'];

				$output = str_replace('%link%',$path,self::$$template);
				$output = str_replace('%attr%',self::_writeAttributes($attr),$output);
				return $output;
			}
			else
			{
				return 'File not found in ' . $search;
			}
		}
	}
}