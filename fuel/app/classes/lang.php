<?php

class Lang extends Fuel\Core\Lang {
	public static function load_path($file, $group = null, $language = null)
	{
		$languages = static::$fallback;
		array_unshift($languages, $language ?: \Config::get('language'));

		$lines = \Fuel::load($file);

		if ($group === null)
		{
			static::$lines = \Arr::merge(static::$lines, $lines);
		}
		else
		{
			$group = ($group === true) ? $file : $group;
			if ( ! isset(static::$lines[$group]))
			{
				static::$lines[$group] = array();
			}
			static::$lines[$group] = \Arr::merge($lines, static::$lines[$group]);
		}
	}
}