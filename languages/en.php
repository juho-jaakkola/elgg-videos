<?php

$lang = array(
	'videos' => 'Videos',
	'videos:add' => 'Upload a video',
	'video:video' => 'Video',
	'video:saved' => 'Video saved',
	'video:download' => 'Download',
	'video:user' => "%s's videos",
	'videos:conversion_pending' => 'Conversion pending',
	'video:' => '',

	'item:object:video' => 'Videos',

	/**
	 * Cron intervals for video conversion
	 */
	'videos:minute' => 'minute',
	'videos:fiveminute' => 'five minutes',
	'videos:fifteenmin' => 'fifteen minutes',
	'videos:halfhour' => 'half an hour',
	'videos:hourly' => 'hour',
	'videos:daily' => 'day',

	'videos:admin:conversion_error' => 'Failed to convert video ‰s to the following format(s): %s. You may find more information in server error log.',
	'videos:admin:thumbnail_error' => 'Failed to create thumbnail(s) %s for the video %s. You may find more information in server error log.',

	'videos:setting:label:formats' => 'Convert videos to these formats',
	'videos:setting:label:period' => 'Check for unconverted videos every',
);

add_translation('en', $lang);