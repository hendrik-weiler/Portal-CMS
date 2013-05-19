<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */


Autoloader::add_classes(array(
	'elfinder\\elFinderLogger'				   	=> __DIR__.'/classes/elFinderLogger.php',
	'elfinder\\elFinder'					   	=> __DIR__.'/classes/elFinder.php',
));

/* End of file bootstrap.php */