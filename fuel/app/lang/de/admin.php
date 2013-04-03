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

	'picturemanager_button' => 'Öffne Bildmanager',
	'nojavascript' => 'Ab hier wird javascript benötigt um alles korrekt nutzen zu können. <a target="_blank" href="http://www.activatejavascript.org/">So aktiviere ich mein Javascript!</a>',

	'news' => array(
		'dateformat' => 'd.m.Y H:i',
		'attachment' => array(
			'header' => 'Anhang',
			'site' => 'Seite',
		),
		'title' => 'Titel',
		'header' => 'News übersicht',
		'submit' => 'Erstellen',
		'no_entries' => 'Noch keine Nachrichten erstellt.',

		'edit' => array(
			'header' => 'Änderung',
			'submit' => 'Bearbeiten',
			'back' => 'Zurück zur Übersicht',
			'title' => 'Titel',
			'pictures' => 'Bilder',
			'upload' => 'Bilder hochladen'
		),
	),

	'nav' => array(
		'dashboard' => 'Aktionszentrum',
		'navigation' => 'Navigation',
		'news' => 'Nachrichten',
		'language' => 'Sprachen',
		'settings' => 'Benutzereinstellungen',
		'advanced' => 'Erweitere einstellungen',
		'logout' => 'Abmelden',
		'clear_cache' => 'Cache leeren',
	),

	'supersearch' => array(
		'all' => 'Alles',
		'tasks' => 'Aufgaben',
		'content' => 'Inhalte',
		'sites' => 'Seiten',
		'news' => 'Nachrichten',
		'accounts' => 'Benutzerkonten',
	),

	'supersearch_results' => array(
		'nothing_found' => 'Keine übereinstimmung gefunden.',
		'main_point' => 'Oberpunkt',
		'sub_point' => 'Unterpunkt',
		'normal_point' => 'Normalpunkt',
		'account_admin' => 'Admin',
		'account_normal' => 'Normal',
	),

	'constants' => array(
		'edit' => 'Bearbeiten',
		'edit_site' => 'Seite Bearbeiten',
		'back' => 'Zurück zur Übersicht',
		'user' => 'Benutzername',
		'next_step' => 'Zum nächsten Schritt',
		'end_tour' => 'Tour beenden',
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
			5 => '1 Spalte [Inhalts-verlinkung]',
			1 => '1 Spalte [Textcontainer]',
			6 => '2 Spalte [Textcontainer]',
			7 => '3 Spalte [Textcontainer]',
			8  =>	'2 Spalte [Inhalts-verlinkung]',
			9  =>	'3 Spalte [Inhalts-verlinkung]',
			10 => 'Flash',
            11 => 'HTML',
            12 => 'Plugin',
            13 => 'Template',
            14 => 'FLV Videoplayer',
		),
		'txtcon' => 'Textcontainer',
		'cl' => 'Inhalts-verlinkung',
		'add_button' => ' + ',
		'none_available' => 'Zurzeit sind keine Inhalte in der Seite',
		'preview' => 'Vorschau',
		'confirm' => 'Übernehmen',
		'confirm_count_single' => ' Eintrag wird übernommen',
		'confirm_count_multiple' => ' Einträge werden übernommen',
	),

	'types' => array(
		'1' => array(
			'header' => 'Bearbeitene den Textcontainer',
			'label' => 'Titel',
			'back' => 'Zurück zur Übersicht',
			'submit' => 'Bearbeiten',
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
			'submit' => 'Bearbeiten',
		),
		'5' => array(
			'header' => 'Wähle ein bereits existierender Inhalt',
			'submit' => 'Speichern',
			'back' => 'Zurück zur Übersicht',
		),
		'10' => array(
			'params' => 'Flash Parameter',
			'params-help' => 'eine pro Zeile, Syntax: schlüssel=wert. Benutzbare Schlüsselwörter: $language[endung], $sitename[endung]',
			'replace_pic' => 'Bild, falls Flash nicht verfügbar ist.',
			'flash_vid' => 'Flash Video',
		),
        '11' => array(
            'header' => 'Fülle HTML/CSS oder Javascript in das Textfeld ein',
            'addplaceholder' => '+ Platzhalter hinzufügen',
            'placeholder_name' => 'Platzhalter Name',
            'placeholder_text' => 'Platzhalter Text',
            'placeholder_delete' => 'Platzhalter löschen',
        ),
        '13' => array(
			'template' => 'Wähle ein Template',
			'preview' => 'Vorschau',
			'info' => 'Speichere das jetzige Template um eine Vorschau zu erhalten.',
			'submit' => 'Speichern',
			'back' => 'Zurück zur Übersicht',
		),
        '14' => array(
			'label' => 'Titel',
			'preview' => 'Vorschau',
			'file' => 'FLV-Datei hochladen...',
			'file_choose' => '...oder wähle aus Layout Ordner',
			'submit' => 'Speichern',
			'back' => 'Zurück zur Übersicht',
			'skin' => 'Wähle den Skin',
			'color_text' => 'Text',
			'color_seekbar' => 'Vorspulleiste',
			'color_loadingbar' => 'Ladebalken',
			'color_seekbarbg' => 'Hintergrund Vorspulleiste',
			'color_button_out' => 'Button Mouseout',
			'color_button_over' => 'Button Mouseover',
			'color_button_highlight' => 'Button Highlight',
			'load' => 'Aussehen Laden',
			'save' => 'Aussehen Speichern',
			'player_color' => 'Aussehen des Videoplayers',
			'video_path' => 'Pfad zum Videoordner: ',
			'skin_path' => 'Pfad zum Skinordner: ',
			'no_video' => 'Es wurde noch kein Video hochgeladen.',
			'no_skin' => 'Es wurde kein skin gewählt.',
			'none' => '(Nichts wählen)',
			'height' => 'Höhe',
			'width' => 'Breite',
			'autoplay' => 'Automatisch abspielen',
			'autohide' => 'Automatisch die Videoleiste verbergen',
			'fullscreen' => 'Vollbild erlauben',
			'preview_pic' => 'Vorschaubild',
			'preview_pic_delete' => 'Vorschaubild Entfernen',
			'dialog_save_headline' => 'Gib dem Skin einen Namen:',
			'dialog_save_confirm' => 'Speichern',
			'dialog_save_cancel' => 'Abbrechen',
		),
	),

	'sites' => array(
		'add_header' => 'Erstelle eine neue Freie-Seite',
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
		'nav_group' => 'Navigation',
                'landingpage' => 'Als Startseite',
            
                'current_template' => 'Jetziges Template',
                'template_default' => 'Layout Standard',
                'template_from_folder' => 'Aus Ordner',
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
		'nav_group' => 'Navigationsgruppe',
		'parent' => 'Befindet sich in',
		'show_in_navigation' => 'Sichtbar in der Navigation',
		'show_sub' => 'Zeige untergruppen auf der Webseite',
		'show_sub_list' => array(
			'none' => '(Verborgen)',
			'left' => 'Linke Seite',
			'right' => 'Rechte Seite'
		),

		'menu_rename' => 'Umbenennen',
		'menu_delete' => 'Löschen',

		'image' => 'Bild',
		'image_is_shown' => 'Bild wird angezeigt',

		'use_default_styles' => 'Standard aussehen behalten',
		'description' => 'Beschreibung',
		'text_color' => 'Textfarbe',
		'background_color' => 'Hintergrundfarbe',
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
		),
		'startlanguage' => 'Das ist die Start-sprache',
	),

	'advanced' => array(
		'tabs' => array(
			'general' => 'Allgemein',
			'seo' => 'Suchmaschinenoptimierung',
			'modules' => 'Module',
			'assets' => 'CSS/JS',
			'layout' => 'Layout',
			'back' => 'Back',
		),
		'header' => array(
			'thumbnails' => 'Thumbnailgrößen',
			'news' => 'Nachrichtenoptionen',
			'accounts' => 'Benutzerkonten & Rechte',
			'help' => 'Hilfsoptionen',
			'inline_edit' => 'Webseiten direkt bearbeitungsmodus',
			'general' => 'Allgemein',
			'site_caching' => 'Website caching',
		),
		'layout' => array(
			'wait' => 'Bitte warten...',
			'current' => 'Jetziges Layout',
		),
		'assets' => array(
			'list' => 'Modul Liste',
		),
		'seo' => array(
			'analytics_id' => 'Analytics Id',
			'robots' => 'Robots',
		),
		'modules' => array(
			'description' => 'Verwalte deine Module',
			'navigation' => 'Navigation',
			'content' => 'Inhaltsbereich',
			'seo_head' => 'Seo Meta Tags',
			'seo_analytics' => 'Google Analytics integration',
			'language_switcher' => 'Sprachversion auswahl',
		),
		'subHeader' => array(
			'news' => 'Nachrichten',
			'gallery' => 'Galerie',
			'view' => 'Design',
			'navi_images' => 'Navigationsbilder',
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
					'language' => 'Systemsprache',
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
		'updater' => array(
			'release_date' => 'Erscheinungsdatum',
			'version' => 'Version',
			'description' => 'Inhalt',
			'update' => 'Aktualisieren zu',
			'no_updates' => 'Es sind keine Updates zurzeit vorhanden.',
			'update_not_available' => 'Noch nicht verfügbar',

			'no_update_able' => 'Der Updateservice ist zurzeit nicht erreichbar.',

			'dateformat' => 'd.m.Y',

			'success' => 'Das Update war erfolgreich.',
			'failure' => 'Es gab Probleme beim updaten!',

			'no_fsock' => 'Die Funktion "fsockopen" muss verfügbar sein, damit der Updater funktioniert.',

			'manually' => array(
				'update' => 'Aktualisieren',
				'instruction' => 'Laden sie ein Update herunter und laden sie es in diesem Formular hoch.',
				'download' => 'Download von Version : ',
			),
		),
	),

	'settings' => array(
		'header' => array(
			'language' => 'Sprache'
		),
		'lang' => 'Systemsprache',
	),

);