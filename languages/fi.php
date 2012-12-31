<?php

$lang = array(
	'video' => 'Videot',
	'video:add' => 'Lisää video',
	'video:edit' => 'Muokkaa',
	'video:video' => 'Video',
	'video:none' => 'Ei videoita.',
	'video:view' => 'Näytä video',
	'video:download' => 'Lataa',
	'video:user' => "Käyttäjän %s videot",
	'video:conversion_pending' => "Tätä videota ei ole ehditty vielä käsitellä. Yritä hetken kuluttua uudelleen.",
	'video:nosupport' => 'Selaimesi ei tue HTML5 videotagia',
	'video:' => '',

	'item:object:video' => 'Videot',
	'video:list:list' => 'Vaihda listanäkymään',
	'video:list:gallery' => 'Vaihda gallerianäkymään',

	/**
	 * System messages
	 */
	'video:saved' => 'Video tallennettu',
	'video:deleted' => 'Video poistettu',

	/**
	 * Error messages
	 */
	'video:notfound' => 'Videota ei löytynyt',
	'video:noaccess' => "Sinulla ei ole oikeuksia tämän videon muokkaamiseen",
	'video:cannotload' => "Videon lisäämisessä tapahtui virhe",
	'video:nofile' => "Valitse lisäätävä tiedosto",
	'video:uploadfailed' => 'Videon tallentaminen epäonnistui.',

	/**
	 * Cron intervals for video conversion
	 */
	'video:minute' => 'minute',
	'video:fiveminute' => 'five minutes',
	'video:fifteenmin' => 'fifteen minutes',
	'video:halfhour' => 'half an hour',
	'video:hourly' => 'hour',
	'video:daily' => 'day',

	// river
	'river:create:object:video' => '%s julkaisi videon %s',
	'river:comment:object:video' => '%s kommentoi videota %s',

	/**
	 * Admin features
	 */
	'admin:video' => 'Videot',
	'admin:video:view' => 'Hallinta',
	'admin:video:convert' => 'Konvertointi',
	'video:manage' => 'Hallinta',
	'video:convert' => 'Konvertoi',
	'video:reconvert' => 'Uudelleenkonvertoi',
	'video:formats' => 'Formaatit',
	'video:format' => 'Formaatti',
	'video:resolution' => 'Resoluutio',
	'video:formatdeletefailed' => 'Formaatin poistaminen epäonnistui',
	'video:formatdeleted' => 'Formaatti poistettu',
	'video:formatnotfound' => 'Määritettyä formaattia ei löytynyt',
	'video:alreadyexists' => 'Määritetty versio on jo olemassa',
	'video:label:guid' => 'Konvertoitavan videon guid',
	'video:convert:success' => 'Video konvertoitu muotoihin: %s.',

	'video:info' => 'Info',
	'video:location' => 'Sijainti',
	'video:size' => 'Koko',

	'video:admin:conversion_error' => 'Failed to convert video ‰s to the following format(s): %s. You may find more information in server error log.',
	'video:admin:thumbnail_error' => 'Failed to create thumbnail(s) %s for the video %s. You may find more information in server error log.',

	'video:setting:instructions' => 'Note that these settings only affect the content created in the future.',
	'video:setting:label:formats' => 'Convert videos to these formats',
	'video:setting:label:framesize' => 'Video frame size (resolution)',
	'video:setting:label:video_width' => 'Video player width as pixels (leave empty to view as 100%)',
	'video:setting:label:period' => 'Check for unconverted videos every',

	'VideoException:ConversionFailed' => 'ERROR: Video conversion failed. %s. Command: "%s"',
);

add_translation('fi', $lang);