<?php

// Load in the Autoloader
require COREPATH.'classes'.DIRECTORY_SEPARATOR.'autoloader.php';
class_alias('Fuel\\Core\\Autoloader', 'Autoloader');

// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';


Autoloader::add_classes(array(
	'Asset\\Manager' => APPPATH . 'classes/asset/manager.php',
  'Parser\\Css' => APPPATH . 'classes/parser/css.php',
  'Parser\\Cache' => APPPATH . 'classes/parser/cache.php',
  'Parser\\Htaccess' => APPPATH . 'classes/parser/htaccess.php',
  'Parser\\Js' => APPPATH . 'classes/parser/js.php',
  'Lang' => APPPATH.'classes/lang.php',
));

// Register the autoloader
Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGE
 * Fuel::PRODUCTION
 */
Fuel::$env = (isset($_SERVER['FUEL_ENV']) ? $_SERVER['FUEL_ENV'] : Fuel::DEVELOPMENT);

// Initialize the framework with the config file.
Fuel::init('config.php');
