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

return array(
	'active' => Config::get('environment'),

	Fuel::DEVELOPMENT => array(
		'type'			=> 'mysqli',
		'connection'	=> array(
			'hostname'   => '[host]',
			'database'   => '[db]',
			'username'   => '[user]',
			'password'   => '[pass]',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	),

	Fuel::PRODUCTION => array(
		'type'			=> 'mysqli',
		'connection'	=> array(
			'hostname'   => '[online_host]',
			'database'   => '[online_db]',
			'username'   => '[online_user]',
			'password'   => '[online_pass]',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	),

	Fuel::TEST => array(
		'type'			=> 'mysqli',
		'connection'	=> array(
			'hostname'   => 'localhost',
			'database'   => 'fuel_test',
			'username'   => 'root',
			'password'   => '',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	),

	Fuel::STAGE => array(
		'type'			=> 'mysqli',
		'connection'	=> array(
			'hostname'   => 'localhost',
			'database'   => 'fuel_stage',
			'username'   => 'root',
			'password'   => '',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	),

	'redis' => array(
		'default' => array(
			'hostname'	=> '127.0.0.1',
			'port'		=> 6379,
		)
	),

);

/* End of file db.php */