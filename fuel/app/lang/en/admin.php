<?php

return array(

	'prompt' => array(
		'language' => array(
			'header' => 'Delete language',
			'text' => 'Do you really want to delete the whole language version of the Site?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
		'navigation' => array(
			'header' => 'Delete navigation point',
			'text' => 'Do you really want to delete it?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
		'sites' => array(
			'header' => 'Delete',
			'text' => 'Do you really want to delete it?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
		'news' => array(
			'header' => 'Delete',
			'text' => 'Do you really want to delete it?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
		'content' => array(
			'header' => 'Delete',
			'text' => 'Do you really want to delete it?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
		'advanced' => array(
			'header' => 'Delete account',
			'text' => 'Do you really want to delete it?',
			'ok' => 'Yes',
			'cancel' => 'Cancel',
		),
	),

	'news' => array(
		'dateformat' => 'Y-m-d H:i',

		'title' => 'Title',
		'header' => 'News overview',
		'submit' => 'Create',
		'no_entries' => 'No news created yet.',

		'edit' => array(
			'header' => 'Editing',
			'submit' => 'Edit',
			'back' => 'Back to overview',
			'title' => 'Title',
			'pictures' => 'Pictures',
			'upload' => 'Upload files'
		),
	),

	'nav' => array(
		'navigation' => 'Navigation',
		'sites' => 'Sites',
		'news' => 'News',
		'language' => 'Languages',
		'settings' => 'User settings',
		'advanced' => 'Advanced settings',
		'logout' => 'Logout',
	),

	'constants' => array(
		'edit' => 'Edit',
		'delete' => 'Delete',
		'save' => 'Save settings',
		'not_set' => '(unset)',
		'choose_lang' => 'Choose your language version:',
		'choose_lang_submit' => 'Change',
		'untitled_element' => 'Untitled',
		'install_tool_usable' => '<strong>Warning:</strong> The install tool is usable. Create app/INSTALL_TOOL_DISABLED to disable it.',
	),

	'content' => array(
		'type' => array(
			2 => 'Contactform',
			3 => 'Gallery',
			4 => 'News',
			5 => '1 Column [Content-linking]',
			1 => '1 Column [Textcontainer]',
			6 => '2 Column [Textcontainer]',
			7 => '3 Column [Textcontainer]',
			8 => '2 Column [Content-linking]',
			9 => '3 Column [Content-linking]',
		),
		'txtcon' => 'Textcontainer',
		'cl' => 'Content-linking',
		'add_button' => ' + ',
		'none_available' => 'Currently no contents in this site',
	),

	'types' => array(
		'1' => array(
			'header' => 'Edit a textcontainer',
			'label' => 'Title',
			'back' => 'Back to overview',
			'submit' => 'Edit',
		),
		'2' => array(
			'header' => 'Manage your form',
			'form_header' => 'Form elements',
			'required' => 'Required',
			'visible' => 'Visible',
			'submit' => 'Submit',
			'sendTo' => 'Receiver adress',
			'back' => 'Back to overview',
			'label' => array(
				'company' => 'Company',
				'first_name' => 'First name',
				'last_name' => 'Last name',
				'postal_code' => 'Postal code',
				'city' => 'City',
				'email' => 'E-mail',
				'phone' => 'Phone',
				'text' => 'Comment',
			),
		),
		'3' => array(
			'header' => 'Basic Settings',
			'custom' => 'Custom',
			'label' => 'Title',
			'description' => 'Image description(one each row)',
			'back' => 'Back to overview',
			'upload' => 'Picture upload',
			'picture_submit' => 'Upload files',
			'image_header' => 'Pictures within gallery',
			'no_entries' => 'This gallery holds no pictures right now.',
			'submit' => 'Edit',
		),
		'5' => array(
			'header' => 'Choose an existing content',
			'submit' => 'Save',
			'back' => 'Back to overview',
		),
	),

	'sites' => array(
		'add_header' => 'Create a new site',
		'edit_header' => 'Edit a site',
		'current_entries' => 'Current entries',
		'no_entries' => 'Your have no sites created yet.',
		'label' => 'Label',
		'redirect' => 'Redirecto to given url by click (optional)',
		'add' => 'Add a new site',
		'edit' => 'Edit site',
		'site_title' => 'Title of site',
		'keywords' => 'Keywords (optional, komma seperated)',
		'description' => 'Description (optional)',
		'content_header' => 'Contents of the site',
		'navigation_id' => 'Navigation point',
	),

	'navigation' => array(
		'add_header' => 'Create a new navigation',
		'edit_header' => 'Edit a navigationpoint',
		'current_entries' => 'Current entries',
		'no_entries' => 'Your navigation is empty',
		'none_parent' => '(None)',
		'label' => 'Label',
		'add' => 'Add a new navigationpoint',
		'edit' => 'Edit navigationpoint',
	),

	'languages' => array(
		'add_lang_header' => 'Create a new Language for the Site',
		'edit_lang_header' => 'Edit current Language settings',
		'sortable' => 'Sortable using drag & drop',
		'form' => array(
			'lang' => 'Description',
			'lang_prefix' => 'Prefix(e.g. de,en)',
			'add_button' => 'Add new language',
			'edit_button' => 'Edit language'
		)
	),

	'advanced' => array(
		'header' => array(
			'thumbnails' => 'Sizes of thumbnails',
			'news' => 'News options',
			'accounts' => 'Accounts & Permissions'
		),
		'subHeader' => array(
			'news' => 'News',
			'gallery' => 'Gallery',
			'view' => 'Viewing',
		),
		'thumbs' => array(
			'width' => 'Width',
			'height' => 'Height',
		),
		'news' => array(
			'show_last' => 'Show last x entries',
			'show_max_token' => 'Show x token each entry',
		),
		'accounts' => array(
				'add' => array(
					'button' => 'Add new account',
					'user' => 'Username',
					'pass' => 'Password',
					'language' => 'Language',
					'back' => 'Back to overview',
				),
				'edit' => array(
					'button' => 'Edit account',
					'pass' => 'New password',
				),
				'permissions' => 'Permissions',
				'languages' => 'Languages',
				'categories' => 'Menu',
				'navigation' => 'Navigation',
				'admin' => 'Administrator',
		),
	),

	'settings' => array(
		'header' => array(
			'language' => 'Language'
		),
		'lang' => 'Language',
	),

);