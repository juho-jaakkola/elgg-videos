<?php

$lang = array(
	'videos' => 'Videos',
	'videos:add' => 'Upload a video',
	'video:video' => 'Video',
	'video:none' => 'No videos.',
	'video:download' => 'Download',
	'video:user' => "%s's videos",
	'videos:conversion_pending' => 'Conversion pending',
	'videos:nosupport' => 'Your browser does not support HTML5 video tag',
	'video:' => '',

	'item:object:video' => 'Videos',
	'videos:list:list' => 'Switch to the list view',
	'videos:list:gallery' => 'Switch to the gallery view',

	/**
	 * System messages
	 */
	'video:saved' => 'Video saved',
	'video:deleted' => 'Video was successfully deleted.',

	/**
	 * Error messages
	 */
	'video:noaccess' => "You do not have permissions to edit this video",
	'video:cannotload' => "There was an error uploading the video",
	'video:nofile' => "You must select a file",
	'video:uploadfailed' => 'The video could not be saved.',

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

	'videos:setting:instructions' => 'Note that these settings only affect the content created in the future.',
	'videos:setting:label:formats' => 'Convert videos to these formats',
	'videos:setting:label:framesize' => 'Video frame size (resolution)',
	'videos:setting:label:video_width' => 'Video player width as pixels (leave empty to view as 100%)',
	'videos:setting:label:period' => 'Check for unconverted videos every',
);

add_translation('en', $lang);