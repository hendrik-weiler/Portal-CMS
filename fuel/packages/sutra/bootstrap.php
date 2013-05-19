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
	// flourish dependencies
	'Sutra\\fCore'				   	=> __DIR__.'/classes/fCore.php',
	'Sutra\\fException'				   	=> __DIR__.'/classes/fException.php',
	'Sutra\\fExpectedException'				   	=> __DIR__.'/classes/fExpectedException.php',
	'Sutra\\fUnexpectedException'				   	=> __DIR__.'/classes/fUnexpectedException.php',
	// ---------------------
	'Sutra\\sArray'				   	=> __DIR__.'/classes/sArray.php',
	'Sutra\\sAuthorization'				   	=> __DIR__.'/classes/sAuthorization.php',
	'Sutra\\sCache'				   	=> __DIR__.'/classes/sCache.php',
	'Sutra\\sCore'				   	=> __DIR__.'/classes/sCore.php',
	'Sutra\\sCRUDForm'				   	=> __DIR__.'/classes/sCRUDForm.php',
	'Sutra\\sGrammar'				   	=> __DIR__.'/classes/sGrammar.php',
	'Sutra\\sHTML'				   	=> __DIR__.'/classes/sHTML.php',
	'Sutra\\sHTTPRequest'				   	=> __DIR__.'/classes/sHTTPRequest.php',
	'Sutra\\sImage'				   	=> __DIR__.'/classes/sImage.php',
	'Sutra\\sJSONP'				   	=> __DIR__.'/classes/sJSONP.php',
	'Sutra\\sLoader'				   	=> __DIR__.'/classes/sLoader.php',
	'Sutra\\sNumber'				   	=> __DIR__.'/classes/sNumber.php',
	'Sutra\\sObject'				   	=> __DIR__.'/classes/sObject.php',
	'Sutra\\sORMJSON'				   	=> __DIR__.'/classes/sORMJSON.php',
	'Sutra\\sProcess'				   	=> __DIR__.'/classes/sProcess.php',
	'Sutra\\sProcessArguments'				   	=> __DIR__.'/classes/sProcessArguments.php',
	'Sutra\\sProcessArgumentsException'				   	=> __DIR__.'/classes/sProcessArgumentsException.php',
	'Sutra\\sRequest'				   	=> __DIR__.'/classes/sRequest.php',
	'Sutra\\sResponse'				   	=> __DIR__.'/classes/sResponse.php',
	'Sutra\\sString'				   	=> __DIR__.'/classes/sString.php',
	'Sutra\\sTemplate'				   	=> __DIR__.'/classes/sTemplate.php',
	'Sutra\\sTimestamp'				   	=> __DIR__.'/classes/sTimestamp.php',
));

/* End of file bootstrap.php */