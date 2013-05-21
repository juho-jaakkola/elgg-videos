<?php

$lang = array(
	'video' => 'Videos',
	'video:add' => 'Upload a video',
	'video:edit' => 'Edit',
	'video:thumbnail:edit' => 'Edit thumbnail',
	'video:video' => 'Video',
	'video:none' => 'No videos.',
	'video:view' => 'View video',
	'video:download' => 'Download',
	'video:user' => "%s's videos",
	'video:conversion_pending' => "This video hasn't been processed yet. Try again later.",
	'video:nosupport' => 'Your browser does not support HTML5 videos. Please upgrade your browser.',
	'video:thumbnail:position' => 'Image position in seconds',
	'video:thumbnail:instructions' => 'Here you can define which image to use as video thumbnail. Either stop the video to the desired position or type the video position as seconds to the text box.',
	'video:' => '',

	'item:object:video' => 'Videos',
	'item:object:video_source' => 'Video sources',
	'video:list:list' => 'Switch to the list view',
	'video:list:gallery' => 'Switch to the gallery view',

	/**
	 * System messages
	 */
	'video:saved' => 'Video saved',
	'video:deleted' => 'Video was successfully deleted.',
	'video:thumbnail:success' => 'New thumbnails were successfully created',
	'video:error:invalid_position' => 'You inserted an invalid position',
	'video:error:thumbnail_creation_failed' => 'Failed to create a new thumbnail',

	/**
	 * Error messages
	 */
	'video:notfound' => 'Video was not found',
	'video:noaccess' => "You do not have permissions to edit this video",
	'video:cannotload' => "There was an error uploading the video",
	'video:nofile' => "You must select a file",
	'video:uploadfailed' => 'The video could not be saved.',
	'video:thumbnail:error' => 'Failed to create one or more thumbnails',
	'video:deletefailed' => 'Failed to delete video',
	'video:dir_delete_failed' => 'Failed to delete directory %s. Please delete it manually.',

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
	'river:create:object:video' => '%s published a video %s',
	'river:comment:object:video' => '%s commented on the video %s',

	/**
	 * Admin features
	 */
	'admin:video' => 'Videos',
	'admin:video:view' => 'Manage',
	'admin:video:convert' => 'Convert',
	'admin:video:flavors' => 'Conversion settings',
	'video:manage' => 'Manage',
	'video:convert' => 'Convert',
	'video:reconvert' => 'Reconvert',
	'video:source:add' => 'Add version',
	'video:formats' => 'Formats',
	'video:format' => 'Format',
	'video:resolution' => 'Resolution',
	'video:bitrate' => 'Bitrate (kb/s)',
	'video:formatdeletefailed' => 'Failed to delete format',
	'video:formatdeleted' => 'Format was successfully deleted',
	'video:formatnotfound' => 'The specified format was not found',
	'video:alreadyexists' => 'Version like this already exists',
	'video:label:guid' => 'Guid of the video',
	'video:convert:success' => 'Video was successfully converted to formats: %s.',

	'admin:video:no_flavors' => "Video flavor settings haven't been added yet.",
	'admin:video:add_flavor' => 'Add flavor',
	'admin:video:add_flavor:success' => 'New flavor added',
	'video:flavors:info' => '<p>Here you can define settings for video conversion.
You can decide the format, resolution and bitrate for each version.</p>
<p>Changing these settings only affects the videos added in the future..</p>',
	'admin:video:delete_flavor:success' => 'Flavor deleted',
	'admin:video:delete_flavor:error' => 'Failed to delete the flavor',

	'video:info' => 'Info',
	'video:location' => 'Location',
	'video:size' => 'Size',
	'video:status' => 'Status',
	'video:pending' => 'Pending',

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

add_translation('en', $lang);
