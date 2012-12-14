<?php

$lang = array(
	'video' => 'Videos',
	'video:add' => 'Upload a video',
	'video:edit' => 'Edit',
	'video:video' => 'Video',
	'video:none' => 'No videos.',
	'video:download' => 'Download',
	'video:user' => "%s's videos",
	'video:conversion_pending' => "This video hasn't been processed yet. Try again after few minutes.",
	'video:nosupport' => 'Your browser does not support HTML5 video tag',
	'video:' => '',

	'item:object:video' => 'Videos',
	'video:list:list' => 'Switch to the list view',
	'video:list:gallery' => 'Switch to the gallery view',

	/**
	 * System messages
	 */
	'video:saved' => 'Video saved',
	'video:deleted' => 'Video was successfully deleted.',

	/**
	 * Error messages
	 */
	'video:notfound' => 'Video was not found',
	'video:noaccess' => "You do not have permissions to edit this video",
	'video:cannotload' => "There was an error uploading the video",
	'video:nofile' => "You must select a file",
	'video:uploadfailed' => 'The video could not be saved.',

	/**
	 * Cron intervals for video conversion
	 */
	'video:minute' => 'minute',
	'video:fiveminute' => 'five minutes',
	'video:fifteenmin' => 'fifteen minutes',
	'video:halfhour' => 'half an hour',
	'video:hourly' => 'hour',
	'video:daily' => 'day',

	/**
	 * Admin features
	 */
	'admin:video' => 'Videos',
	'admin:video:view' => 'Manage',
	'admin:video:convert' => 'Convert',
	'video:manage' => 'Manage',
	'video:convert' => 'Convert',
	'video:reconvert' => 'Reconvert',
	'video:formats' => 'Formats',
	'video:format' => 'Format',
	'video:resolution' => 'Resolution',
	'video:formatdeletefailed' => 'Failed to delete format',
	'video:formatdeleted' => 'Format was successfully deleted',
	'video:formatnotfound' => 'The specified format was not found',
	'video:alreadyexists' => 'Version like this already exists',
	'video:label:guid' => 'Guid of the video',
	'video:convert:success' => 'Video was successfully converted to formats: %s.',

	'video:info' => 'Info',
	'video:location' => 'Location',
	'video:size' => 'Size',

	'video:admin:conversion_error' => 'Failed to convert video ‰s to the following format(s): %s. You may find more information in server error log.',
	'video:admin:thumbnail_error' => 'Failed to create thumbnail(s) %s for the video %s. You may find more information in server error log.',

	'video:setting:instructions' => 'Note that these settings only affect the content created in the future.',
	'video:setting:label:formats' => 'Convert videos to these formats',
	'video:setting:label:framesize' => 'Video frame size (resolution)',
	'video:setting:label:video_width' => 'Video player width as pixels (leave empty to view as 100%)',
	'video:setting:label:period' => 'Check for unconverted videos every',

	'VideoException:ConversionFailed' => 'ERROR: Video conversion failed. %s. Command: "%s"',
);

add_translation('en', $lang);