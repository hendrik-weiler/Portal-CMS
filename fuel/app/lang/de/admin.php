<?php

return array(

	'prompt' => array(
		'language' => array(
			'header' => 'Sprachversion löschen',
			'text' => 'Soll wirklich die Sprachversion der Seite gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
		'navigation' => array(
			'header' => 'Navigationspunkt löschen',
			'text' => 'Soll es wirklich gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
		'sites' => array(
			'header' => 'Löschen',
			'text' => 'Soll es wirklich gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
		'news' => array(
			'header' => 'Löschen',
			'text' => 'Soll es wirklich gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
		'content' => array(
			'header' => 'Löschen',
			'text' => 'Soll es wirklich gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
		'advanced' => array(
			'header' => 'Löschen',
			'text' => 'Soll es wirklich gelöscht werden?',
			'ok' => 'Ja',
			'cancel' => 'Nein',
		),
	),

	'news' => array(
		'dateformat' => 'd.m.Y H:i',

		'title' => 'Titel',
		'header' => 'News übersicht',
		'submit' => 'Erstellen',
		'no_entries' => 'Noch keine Nachrichten erstellt.',

		'edit' => array(
			'header' => 'Änderung',
			'submit' => 'Ändern',
			'back' => 'Zurück zur Übersicht',
			'title' => 'Titel',
			'pictures' => 'Bilder',
			'upload' => 'Bilder hochladen'
		),
	),

	'nav' => array(
		'navigation' => 'Navigation',
		'sites' => 'Seiten',
		'news' => 'Nachrichten',
		'language' => 'Sprachen',
		'settings' => 'Benutzereinstellungen',
		'advanced' => 'Erweitere einstellungen',
		'logout' => 'Abmelden',
	),

	'constants' => array(
		'edit' => 'Ändern',
		'delete' => 'Löschen',
		'save' => 'Änderungen speichern',
		'not_set' => '(ohne)',
		'choose_lang' => 'Wähle Sprachversion:',
		'choose_lang_submit' => 'Wechsel',
		'untitled_element' => 'Unbenannt',
		'install_tool_usable' => '<strong>Achtung:</strong> Das Installationswerkzeug ist verwendbar. Erstelle app/INSTALL_TOOL_DISABLED um es auszuschalten.',
	),

	'content' => array(
		'type' => array(
			2 => 'Kontaktformular',
			3 => 'Galerie',
			4 => 'Nachrichten',
			5 => 'Inhalts-verlinkung',
			1 => '1 Spalte',
			6 => '2 Spalte',
			7 => '3 Spalte',
		),
		'txtcon' => 'Textcontainer',
		'add_button' => ' + ',
		'none_available' => 'Zurzeit sind keine Inhalte in der Seite',
	),

	'types' => array(
		'1' => array(
			'header' => 'Änderne den Textcontainer',
			'label' => 'Titel',
			'back' => 'Zurück zur Übersicht',
			'submit' => 'Ändern',
		),
		'2' => array(
			'header' => 'Verwalte dein Formular',
			'form_header' => 'Formular Elemente',
			'required' => 'Pflichtfelder',
			'visible' => 'Sichtbar',
			'submit' => 'Bestätigen',
			'sendTo' => 'Empfängeradresse',
			'back' => 'Zurück zur Übersicht',
			'label' => array(
				'company' => 'Firma',
				'first_name' => 'Vorname',
				'last_name' => 'Nachname',
				'postal_code' => 'PLZ',
				'city' => 'Ort',
				'email' => 'E-mail',
				'phone' => 'Telefon',
				'text' => 'Kommentar',
			),
		),
		'3' => array(
			'header' => 'Grundeinstellungen',
			'custom' => 'Eigenes',
			'label' => 'Titel',
			'description' => 'Bild beschreibung(eine pro Zeile)',
			'back' => 'Zurück zur Übersicht',
			'upload' => 'Bilder hochladen',
			'picture_submit' => 'Dateien hochladen',
			'image_header' => 'Bilder in der Galerie',
			'no_entries' => 'Die Galerie besitzt noch keine Bilder',
			'submit' => 'Ändern',
		),
		'5' => array(
			'header' => 'Wähle ein bereits existierender Inhalt',
			'submit' => 'Speichern',
			'back' => 'Zurück zur Übersicht',
		),
	),

	'sites' => array(
		'add_header' => 'Erstelle eine neue Seite',
		'edit_header' => 'Seite ändern',
		'current_entries' => 'Derzeitige Einträge',
		'no_entries' => 'Es wurde bisher noch keine Seiten erstellt.',
		'label' => 'Beschreibung',
		'redirect' => 'Weiterleitung bei Klick(optional)',
		'add' => 'Neue Seite',
		'edit' => 'Seite ändern',
		'site_title' => 'Titel der Seite',
		'keywords' => 'Schlüsselwörter (optional, Komma getrennt)',
		'description' => 'Seitenbeschreibung (optional)',
		'content_header' => 'Inhalte der Seite',
		'navigation_id' => 'Navigationspunkt',
	),

	'navigation' => array(
		'add_header' => 'Neuer Navigationspunkt',
		'edit_header' => 'Navigationspunkt bearbeiten',
		'current_entries' => 'Derzeitige Einträge',
		'no_entries' => 'Deine Navigation ist leer.',
		'none_parent' => '(Keins)',
		'label' => 'Beschreibung',
		'add' => 'Neuer Navigationspunkt',
		'edit' => 'Navigationspunkt ändern',
	),

	'languages' => array(
		'add_lang_header' => 'Erstelle eine neue Sprachversion für die Seite',
		'edit_lang_header' => 'Bearbeite die ausgewählte Sprachversion',
		'sortable' => 'Sortierbar durch Objekte ziehen & loslassen',
		'form' => array(
			'lang' => 'Beschreibung',
			'lang_prefix' => 'Prefix(e.g. de,en)',
			'add_button' => 'Neue Sprache hinzufügen',
			'edit_button' => 'Bearbeiten'
		)
	),

	'advanced' => array(
		'header' => array(
			'thumbnails' => 'Thumbnailgrößen',
			'news' => 'Nachrichtenoptionen',
			'accounts' => 'Benutzerkonten & Rechte'
		),
		'subHeader' => array(
			'news' => 'Nachrichten',
			'gallery' => 'Galerie',
			'view' => 'Design',
		),
		'thumbs' => array(
			'width' => 'Höhe',
			'height' => 'Breite',
		),
		'news' => array(
			'show_last' => 'Zeige letzte x Einträge',
			'show_max_token' => 'Zeige x Zeichen pro Eintrag',
		),
		'accounts' => array(
				'add' => array(
					'button' => 'Neues Konto hinzufügen',
					'user' => 'Benutzername',
					'pass' => 'Passwort',
					'language' => 'Sprache',
					'back' => 'Zurück zur übersicht',
				),
				'edit' => array(
					'button' => 'Konto bearbeiten',
					'pass' => 'Neues Passwort',
				),
				'permissions' => 'Rechte',
				'languages' => 'Sprachversionen',
				'categories' => 'Menü',
				'navigation' => 'Navigation',
				'admin' => 'Administrator',
		),
	),

	'settings' => array(
		'header' => array(
			'language' => 'Sprache'
		),
		'lang' => 'Sprache',
	),

);