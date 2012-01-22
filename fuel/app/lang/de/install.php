<?php

return array(

	'steps' => array(

		1 => array(
			'header' =>	'Datenbank einstellen',
			'db_header' => 'Datenbank auswählen',
			'db_description' => 'Gebe deinen Datenbank namen an. Falls er nicht existiert wird er automatisch erstellt.',
			'db_online_description' => 'Trage deine Datenbank ein. Stelle sicher das sie existiert, bevor ihr zum nächsten Schritt geht.',
			'login_header' => 'Datenbank Benutzerdaten',
			'error_no_login' => 'Der Benutzername oder Passwort ist falsch.',
			'error_no_rights' => 'Der Benutzer hatte nicht die erfoderlichen Rechte eine Datenbank anzulegen.',
			'user' => 'Benutzername',
			'pass' => 'Passwort',
			'offline_description' => 'Die Benutzerdaten sind notwendig für die offline Entwicklung. ',
			'online_description' => 'Die Benutzerdaten sind notwendig für das online stellen der Seite.<br /><strong>Die eingetragenen Daten werde ohne Prüfung genutzt!</strong>',
			'online_db' => 'Online Datenbank',
			'offline_db' => 'Offline Datenbank'
		),
		2 => array(
			'header' =>	'Admin account erstellen',
			'acc_description' => 'Fülle die Felder Benutzernamen und Passwort aus. Ein Konto wird erstellt mit allen Rechten für das CMS.',
			'error_required' => 'Benutzername und Passwort sind pflicht angaben. Minimal 3 Zeichen!',
		),
		3 => array(
			'header' =>	'Fertigstellen',
			'finish_header' => 'Fast fertig!',
			'finish_description' => 'Wenn du auf diese Schaltfläche drückst wird das Installations Tool versiegelt und kann nurnoch durch das Löschen von ./app/INSTALL_TOOL_DISABLED benutzt werden.',
			'button' => 'Zum Logincenter',
		),
		'next' => 'Nächster Schritt',

	),

	'choose_lang' => 'Wähle deine Sprache',

);