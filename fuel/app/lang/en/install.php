<?php

return array(

	'steps' => array(

		1 => array(
			'header' =>	'Configure database',
			'db_header' => 'Select database',
			'db_description' => 'Type in your database name. If it doenst exist, it will be created automaticly.',
			'db_online_description' => 'Type in your database name. Make sure the database exist before you go to the next step',
			'login_header' => 'Database login',
			'error_no_login' => 'Username or Password is incorrect.',
			'error_no_rights' => 'User doenst have enough rights to create a database.',
			'user' => 'Username',
			'pass' => 'Password',
			'offline_description' => 'This data is required for offline development. ',
			'online_description' => 'This data is required when you put it up to ftp/live for production purpose.<br /><strong>The data entered here will used without testing!</strong>',
			'online_db' => 'Online Database',
			'offline_db' => 'Offline Database'
		),
		2 => array(
			'header' =>	'Create admin account',
			'acc_description' => 'Fill in your username and password. A account will created with all rights where you can login into the cms.',
			'error_required' => 'Username and password are required with atleast 3 letters each.',
		),
		3 => array(
			'header' =>	'Finish',
			'finish_header' => 'Almost finished!',
			'finish_description' => 'If you click on this button the installer will sealed and unable to use again without deleting/removing ./app/INSTALL_TOOL_DISABLED.',
			'button' => 'To the logincenter',
		),
		'next' => 'Next Step',

	),

	'choose_lang' => 'Choose your language',

);