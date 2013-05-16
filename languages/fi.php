<?php

$lang = array(
	'video' => 'Videot',
	'video:add' => 'Lisää video',
	'video:edit' => 'Muokkaa',
	'video:thumbnail:edit' => 'Muokkaa esikatselukuvaa',
	'video:video' => 'Video',
	'video:none' => 'Ei videoita.',
	'video:view' => 'Näytä video',
	'video:download' => 'Lataa',
	'video:user' => "Käyttäjän %s videot",
	'video:conversion_pending' => "Tätä videota ei ole ehditty vielä käsitellä. Yritä myöhemmin uudelleen.",
	'video:nosupport' => 'Selaimesi ei tue HTML5-videoita. Ole hyvä ja päivitä selain uudempaan versioon.',
	'video:thumbnail:position' => 'Kuvan sijainti sekunteina',
	'video:thumbnail:instructions' => 'Tällä sivulla voit valita videon esikatselukuvissa näytettävän kuvan. Valitse kuva joko pysäyttämällä video haluamaasi kohtaan tai syöttämällä haluamasi aika sekunteina suoraan tektikenttään.',
	'video:' => '',

	'item:object:video' => 'Videot',
	'item:object:video_source' => 'Videomuunnokset',
	'video:list:list' => 'Vaihda listanäkymään',
	'video:list:gallery' => 'Vaihda gallerianäkymään',

	/**
	 * System messages
	 */
	'video:saved' => 'Video tallennettu',
	'video:deleted' => 'Video poistettu',
	'video:thumbnail:success' => 'Luotiin uudet esikatselukuvat',

	/**
	 * Error messages
	 */
	'video:notfound' => 'Videota ei löytynyt',
	'video:noaccess' => "Sinulla ei ole oikeuksia tämän videon muokkaamiseen",
	'video:cannotload' => "Videon lisäämisessä tapahtui virhe",
	'video:nofile' => "Valitse lisättävä tiedosto",
	'video:uploadfailed' => 'Videon tallentaminen epäonnistui.',
	'video:thumbnail:error' => 'Yhden tai useamman esiatselukuvan luominen epäonnistui',
	'video:deletefailed' => 'Videon poistaminen epäonnistui',
	'video:dir_delete_failed' => 'Hakemiston %s poistaminen epäonnistui. Poista hakemisto manuaalisesti.',

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
	'admin:video:flavors' => 'Konversioasetukset',
	'video:manage' => 'Hallinta',
	'video:convert' => 'Konvertoi',
	'video:reconvert' => 'Uudelleenkonvertoi',
	'video:formats' => 'Formaatit',
	'video:format' => 'Formaatti',
	'video:resolution' => 'Resoluutio',
	'video:bitrate' => 'Bitrate (kb/s)',
	'video:formatdeletefailed' => 'Formaatin poistaminen epäonnistui',
	'video:formatdeleted' => 'Formaatti poistettu',
	'video:formatnotfound' => 'Määritettyä formaattia ei löytynyt',
	'video:alreadyexists' => 'Määritetty versio on jo olemassa',
	'video:label:guid' => 'Konvertoitavan videon guid',
	'video:convert:success' => 'Video konvertoitu muotoihin: %s.',

	'admin:video:no_flavors' => 'Versioita ei ole vielä luotu',
	'admin:video:add_flavor' => 'Lisää versio',
	'admin:video:add_flavor:success' => 'Lisättiin uusi versio',
	'video:flavors:info' => '<p>Tällä sivulla voit määrittää, mihin eri laatuihin ja formaatteihin Elggiin lisättävät videot muunnetaan.
Samasta formaatista voi esimerkiksi tehdä useita eri laatuja.</p>
<p>HUOM! Asetusten muuttaminen vaikuttaa vain uusiin videoihin.</p>',
	'admin:video:delete_flavor:success' => 'Versio poistettu',
	'admin:video:delete_flavor:error' => 'Version poistaminen epäonnistui',

	'video:info' => 'Info',
	'video:location' => 'Sijainti',
	'video:size' => 'Koko',
	'video:status' => 'Tila',
	'video:pending' => 'Odottaa',

	'video:admin:thumbnail_error' => 'Failed to create thumbnail(s) for the video %s. You may find more information in server error log.',

	'video:setting:instructions' => 'Note that these settings only affect the content created in the future.',
	'video:setting:label:formats' => 'Convert videos to these formats',
	'video:setting:label:resolutionesolution' => 'Video resolution',
	'video:setting:label:bitrate' => 'Bitrate in kilobits',
	'video:setting:label:video_width' => 'Video player width as pixels (leave empty to view as 100%)',
	'video:setting:label:video_height' => 'Video player height as pixels',
	'video:setting:label:period' => 'Check for unconverted videos every',
	'video:setting:label:square_icons' => 'Use square thumbnail icons',

	'VideoException:ConversionFailed' => 'ERROR: Failed to create file %s.<br /><br />%s.<br /><br />Used command: "%s"',
	'VideoException:ThumbnailCreationFailed' => 'ERROR: Failed to create thumbnails for video %s.<br /><br />%s.<br /><br />Used command: "%s"',
);

add_translation('fi', $lang);