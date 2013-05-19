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

	'siteselector_button' => 'Choose a site',
	'siteselector' => array(
		'title' => 'Choose a site',
		'text' => 'Select a site from the list',
		'confirm' => 'Select',
		'cancel' => 'Cancel'
	),
	'picturemanager_button' => 'Open picturemanager',
	'nojavascript' => 'At this point you need javascript activated to use all features. <a target="_blank" href="http://www.activatejavascript.org/">Learn how to activate javascript!</a>',

	'news' => array(
		'dateformat' => 'Y-m-d H:i',
		'attachment' => array(
			'header' => 'Attachment',
			'site' => 'Site',
		),
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
		'dashboard' => 'Helpdesk',
		'navigation' => 'Navigation',
		'news' => 'News',
		'language' => 'Languages',
		'settings' => 'User settings',
		'advanced' => 'Advanced settings',
		'logout' => 'Logout',
		'clear_cache' => 'Clear cache',
	),

	'supersearch' => array(
		'all' => 'All',
		'tasks' => 'Tasks',
		'content' => 'Contents',
		'sites' => 'Sites',
		'news' => 'News',
		'accounts' => 'Accounts',
	),

	'supersearch_results' => array(
		'nothing_found' => 'This search had no results.',
		'main_point' => 'Mainpoint',
		'sub_point' => 'Subordinate',
		'normal_point' => 'Normalpoint',
		'account_admin' => 'Admin',
		'account_normal' => 'Normal',
	),

	'constants' => array(
		'edit' => 'Edit',
		'edit_site' => 'Edit Site',
		'back' => 'Back to overview',
		'user' => 'Username',
		'next_step' => 'Next Step',
		'end_tour' => 'Quit tour',
		'delete' => 'Delete',
		'save' => 'Save settings',
		'not_set' => '(unset)',
		'choose_lang' => 'Language version:',
		'choose_lang_submit' => 'Change',
		'untitled_element' => 'Untitled',
		'install_tool_usable' => '<strong>Warning:</strong> The install tool is usable. Create app/INSTALL_TOOL_DISABLED to disable it.',
	),

	'content' => array(
		'type' => array(
			2  =>	'Contactform',
			3  =>	'Gallery',
			4  =>	'News',
			5  =>	'1 Column [Content-linking]',
			1  =>	'1 Column [Textcontainer]',
			6  =>	'2 Column [Textcontainer]',
			7  =>	'3 Column [Textcontainer]',
			8  =>	'2 Column [Content-linking]',
			9  =>	'3 Column [Content-linking]',
			10 => 'Flash',
            11 => 'HTML',
            12 => 'Plugin',
            13 => 'Template',
            14 => 'FLV Videoplayer',
		),
		'txtcon' => 'Textcontainer',
		'cl' => 'Content-linking',
		'add_button' => ' + ',
		'none_available' => 'Currently no contents in this site',
		'preview' => 'Preview',
		'confirm' => 'Accept',
		'confirm_count_single' => ' entry will be accepted',
		'confirm_count_multiple' => ' entries will be accepted',
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
		'10' => array(
			'params' => 'Flash Parameter',
			'params-help' => 'one each line, syntax: key=value. Usable keywords: $language[extension], $sitename[extension]',
			'replace_pic' => 'Picture, if flash is not available.',
			'flash_vid' => 'Flash Video',
		),
        '11' => array(
            'header' => 'Fill in HTML/CSS or javascript into the textfield',
            'addplaceholder' => '+ Add Placeholder',
            'placeholder_name' => 'Placeholder name',
            'placeholder_text' => 'Placeholder text',
            'placeholder_delete' => 'Delete placeholder',
        ),
		'13' => array(
			'template' => 'Choose a template',
			'preview' => 'Preview',
			'info' => 'Save the current template to get a preview',
			'submit' => 'Save',
			'back' => 'Back to overview',
		),
        '14' => array(
			'label' => 'Title',
			'preview' => 'Preview',
			'file' => 'Upload FLV-File...',
			'file_choose' => '...or choose from the layout folder',
			'submit' => 'Save',
			'back' => 'Back to overview',
			'skin' => 'Choose the skin',
			'color_text' => 'Text',
			'color_seekbar' => 'Fast-Forward-Bar',
			'color_loadingbar' => 'Loading-bar',
			'color_seekbarbg' => 'Background Fast-Forward-Bar',
			'color_button_out' => 'Button Mouseout',
			'color_button_over' => 'Button Mouseover',
			'color_button_highlight' => 'Button Highlight',
			'load' => 'Load appeareance',
			'save' => 'Save appeareance',
			'player_color' => 'Appeareance of the videoplayer',
			'video_path' => 'Path to video folder: ',
			'skin_path' => 'Path to skin folder: ',
			'no_video' => 'No video was uploaded yet.',
			'no_skin' => 'No skin was selected yet.',
			'none' => '(No selection)',
			'height' => 'Height',
			'width' => 'Width',
			'autoplay' => 'Start to play automaticly',
			'autohide' => 'Hide controls automaticly',
			'fullscreen' => 'Enable fullscreen',
			'preview_pic' => 'Previewimage',
			'preview_pic_delete' => 'Delete previewimage',
			'dialog_save_headline' => 'Skin title:',
			'dialog_save_confirm' => 'Save',
			'dialog_save_cancel' => 'Cancel',
		),
	),

	'sites' => array(
		'add_header' => 'Create a new free site',
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
		'nav_group' => 'Navigation',
                'landingpage' => 'As landingpage',
            
                'current_template' => 'Current Template',
                'template_default' => 'Default Layout',
                'template_from_folder' => 'From Folder',
	),

	'navigation' => array(
		'add_header' => 'Create a new navigationgroup',
		'create_header' => 'Create a new navigationpoint',
		'current_entries' => 'Current entries',
		'no_entries' => 'Your navigation is empty',
		'none_parent' => '(None)',
		'label' => 'Label',
		'add' => 'Add a new navigationpoint',
		'edit' => 'Edit navigationpoint',
		'nav_group' => 'Navigationgroup',
		'parent' => 'Located in',
		'show_in_navigation' => 'Show up in navigation',
		'show_sub' => 'Show subnavigation on webpage',
		'show_sub_list' => array(
			'none' => '(Invisible)',
			'left' => 'Left side',
			'right' => 'Right side'
		),

		'add_navigation' => 'Add navigation',
		'edit_navigation' => 'Rename navigation',
		'delete_navigation' => 'Delete navigation',

		'image' => 'Picture',
		'image_is_shown' => 'Picture will be shown',

		'use_default_styles' => 'Use default style',
		'description' => 'Description',
		'text_color' => 'Text color',
		'background_color' => 'Background color',
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
		),
		'startlanguage' => 'This is the startlanguage',
	),

	'advanced' => array(
		'tabs' => array(
			'general' => 'General',
			'seo' => 'Searchoptimizing',
			'modules' => 'Modules',
			'assets' => 'CSS/JS',
			'layout' => 'Layout',
			'back' => 'Back',
		),
		'header' => array(
			'thumbnails' => 'Sizes of thumbnails',
			'news' => 'News options',
			'accounts' => 'Accounts & Permissions',
			'help' => 'Help options',
			'inline_edit' => 'Website direct edit mode',
			'general' => 'General',
			'site_caching' => 'Website caching',
		),
		'layout' => array(
			'wait' => 'Please wait....',
			'current' => 'Current Layout',
		),
		'assets' => array(
			'list' => 'Modul List',
		),
		'seo' => array(
			'analytics_id' => 'Analytics Id',
			'robots' => 'Robots',
		),
		'modules' => array(
			'description' => 'Manage your modules',
			'navigation' => 'Navigation',
			'content' => 'Contentarea',
			'seo_head' => 'Seo Meta Tags',
			'seo_analytics' => 'Google Analytics integration',
			'language_switcher' => 'Language selection',
		),
		'subHeader' => array(
			'news' => 'News',
			'gallery' => 'Gallery',
			'view' => 'Viewing',
			'navi_images' => 'Navigationpictures',
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
					'language' => 'Systemlanguage',
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
		'updater' => array(
			'release_date' => 'Release date',
			'version' => 'Version',
			'description' => 'Content',
			'update' => 'Update to',
			'no_updates' => 'No updates available currently.',
			'update_not_available' => 'Not available yet',

			'dateformat' => 'Y-m-d',

			'no_update_able' => 'The Updateservice is currently not available.',

			'success' => 'The update was successfull.',
			'failure' => 'Problems occured while updating!',

			'no_fsock' => 'The function "fsockopen" have to be available.',

			'manually' => array(
				'update' => 'Update',
				'instruction' => 'Download an update and upload it via this form.',
				'download' => 'Download of version : ',
			),
		),
	),

	'settings' => array(
		'header' => array(
			'language' => 'Language'
		),
		'lang' => 'Systemlanguage',
	),

);