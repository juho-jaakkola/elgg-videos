<?php

$lang = array(
	'videos' => 'Videot',
	'videos:add' => 'Lisää video',
	'video:video' => 'Video',
	'video:none' => 'Ei videoita.',
	'video:download' => 'Lataa',
	'video:user' => "Käyttäjän %s videot",
	'videos:conversion_pending' => "Tätä videota ei ole ehditty vielä käsitellä. Yritä hetken kuluttua uudelleen.",
	'videos:nosupport' => 'Selaimesi ei tue HTML5 videotagia',
	'video:' => '',

	'item:object:video' => 'Videot',
	'videos:list:list' => 'Vaihda listanäkymään',
	'videos:list:gallery' => 'Vaihda gallerianäkymään',

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
	'videos:minute' => 'minute',
	'videos:fiveminute' => 'five minutes',
	'videos:fifteenmin' => 'fifteen minutes',
	'videos:halfhour' => 'half an hour',
	'videos:hourly' => 'hour',
	'videos:daily' => 'day',

	/**
	 * Admin features
	 */
	'admin:videos' => 'Videot',
	'admin:videos:convert' => 'Konvertointi',
	'videos:convert' => 'Konvertoi',
	'videos:label:guid' => 'Konvertoitavan videon guid',
	'videos:convert:success' => 'Video konvertoitu muotoihin: %s.',

	'videos:admin:conversion_error' => 'Failed to convert video ‰s to the following format(s): %s. You may find more information in server error log.',
	'videos:admin:thumbnail_error' => 'Failed to create thumbnail(s) %s for the video %s. You may find more information in server error log.',

	'videos:setting:instructions' => 'Note that these settings only affect the content created in the future.',
	'videos:setting:label:formats' => 'Convert videos to these formats',
	'videos:setting:label:framesize' => 'Video frame size (resolution)',
	'videos:setting:label:video_width' => 'Video player width as pixels (leave empty to view as 100%)',
	'videos:setting:label:period' => 'Check for unconverted videos every',

	'VideoException:ConversionFailed' => 'ERROR: Video conversion failed. %s. Command: "%s"',
);

add_translation('fi', $lang);