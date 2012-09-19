<?php

return array(

	'header' => 'Was wollen Sie tun?',
	'take_tour' => 'Erfahre wie',
	'short_cut' => 'Zum Einsatzort',
	'filter' => 'Filter:',

	'questions' => array(
		'supersearch' => 'Supersearch kennenlernen',

		'content' => 'Einen neuen Inhalt erstellen',
		'content_news' => 'Nachrichten darstellen auf der Website',

		'navigation' => 'Einen neuen Eintrag in der Navigation erstellen',
		'navigation_edit' => 'Einen Navigationspunkt bearbeiten',
		'navigation_group' => 'Eine neue Navigationsgruppe erstellen (z.B Footer)',
		'navigation_group_edit_delete' => 'Eine bestehende Navigationsgruppe bearbeiten oder löschen',
		'navigation_sidebar' => 'Eine Sidebar von einem Navigationpunkt mit Unterpunkten einrichten.',

		'layout' => 'Ein anderes Layout der Seite verpassen',

		'language' => 'Die Systemsprache ändern',

		'news' => 'Eine neue Nachricht erstellen',
		'news_edit' => 'Eine bestehende Nachricht bearbeiten',

		'account' => 'Ein neues Benutzerkonto anlegen',
		'account_edit' => 'Ein bestehendes Benutzerkonto bearbeiten',

		'images' => 'Bildergrößen anpassen',

		'language_version' => 'Eine neue Website für eine andere Sprache erstellen',
		'language_version_show_frontend' => 'Die Sprachversionen auf der Webseite zum auswählen darstellen.',
	),

	'question_permissions' => array(
		'supersearch' => '-1',

		'content' => '0,1',
		'content_news' => '0,1',

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

		'content' => 'tour|shortcut',
		'content_news' => 'tour|shortcut',

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

		'content' => 'open-supersearch//sites/no_main#tour=content',
		'content_news' => 'open-supersearch//sites/no_main#tour=content_news',

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