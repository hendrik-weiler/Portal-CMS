<?php

return array(

	'header' => 'Type in what you want to know!',
	'take_tour' => 'Learn how',
	'short_cut' => 'To the place',
	'filter' => 'Filter:',

	'new_updates_single' => ' new update is available.',
	'new_updates_multi' => ' new updates are available.',

	'questions' => array(
		'supersearch' => 'Learn about supersearch',

		'updater' => 'Updating the system',

		'content' => 'Create a new content',
		'content_news' => 'Display news on the website',
		'content_width' => 'Set single contents into columns',

		'navigation' => 'Create a new entry in the navigation',
		'navigation_edit' => 'Edit an entry of the navigation',

		'navigation_group' => 'Create a new navigationgroup (for example Footer)',
		'navigation_group_edit_delete' => 'Edit or delete an existing navigationgroup',
		'navigation_sidebar' => 'Setup a sidebar from a navigationpoint with subentries',

		'layout' => 'Change the current website layout',

		'language' => 'Change the systemlanguage',

		'news' => 'Create a news entry',
		'news_edit' => 'Edit an existing news netry',

		'account' => 'Create a new useraccount',
		'account_edit' => 'Edit an existing useraccount',

		'images' => 'Change picture sizes',

		'language_version' => 'Create a new website language version',
		'language_version_show_frontend' => 'Display a list of all available website languages on the website',
	),

	'question_permissions' => array(
		'supersearch' => '-1',

		'updater' => '-1',

		'content' => '0,1',
		'content_news' => '0,1',
		'content_width' => '0,1',

		'navigation' => '0,1',
		'navigation_edit' => '0,1',
		'navigation_group' => '0,1',
		'navigation_group_edit_delete' => '0,1',
		'navigation_sidebar' => '0,1',

		'layout' => '-1',

		'language' => '3',

		'news' => '2',
		'news_edit' => '2',

		'account' => '-1',
		'account_edit' => '-1',

		'images' => '-1',

		'language_version' => '-1',
		'language_version_show_frontend' => '-1',
	),

	'question_links_show' => array(
		'supersearch' => 'tour',

		'updater' => 'tour|shortcut',

		'content' => 'tour|shortcut',
		'content_news' => 'tour|shortcut',
		'content_width' => 'tour|shortcut',

		'navigation' => 'tour|shortcut',
		'navigation_edit' => 'tour|shortcut',
		'navigation_group' => 'tour|shortcut',
		'navigation_group_edit_delete' => 'tour|shortcut',
		'navigation_sidebar' => 'tour|shortcut',

		'layout' => 'tour|shortcut',

		'language' => 'tour|shortcut',

		'news' => 'tour|shortcut',
		'news_edit' => 'shortcut',

		'account' => 'tour|shortcut',
		'account_edit' => 'tour|shortcut',

		'images' => 'tour|shortcut',

		'language_version' => 'tour|shortcut',
		'language_version_show_frontend' => 'tour|shortcut',
	),

	'question_links' => array(
		'supersearch' => 'admin/news#tour=supersearch',

		'updater' => 'admin/advanced/update#tour=updater',

		'content' => 'open-supersearch//sites/no_main#tour=content',
		'content_news' => 'open-supersearch//sites/no_main#tour=content_news',
		'content_width' => 'open-supersearch//sites/no_main#tour=content_width',

		'navigation' => 'admin/navigation#tour=navigation',
		'navigation_edit' => 'open-supersearch//sites/no_main#tour=navigation_edit',
		'navigation_group' => 'admin/navigation#tour=navigation_group',
		'navigation_group_edit_delete' => 'admin/navigation#tour=navigation_group_edit_delete',
		'navigation_sidebar' => 'open-supersearch//sites/main_points#tour=sidebar',

		'layout' => 'admin/advanced/layout#tour=layout',

		'language' => 'admin/settings#tour=language_edit',

		'news' => 'admin/news#tour=news_add',
		'news_edit' => 'open-supersearch//news#tour=news_edit',

		'account' => 'admin/accounts/add#tour=account',
		'account_edit' => 'open-supersearch//accounts#tour=account_edit',

		'images' => 'admin/advanced#tour=images',

		'language_version' => 'admin/language#tour=language_version',
		'language_version_show_frontend' => 'admin/language#tour=language_version_show_frontend_1',
	),


);