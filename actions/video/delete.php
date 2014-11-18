<?php
/**
* Delete video
*
* @package Video
*/

$guid = (int) get_input('guid');

$video = get_entity($guid);

if (elgg_instanceof($video, 'object', 'video') && $video->canEdit()) {
	// Delete the thumbnails
	$icon_sizes = elgg_get_config('icon_sizes');
	foreach ($icon_sizes as $name => $size_info) {
		$file = new ElggFile();
		$file->owner_guid = $video->getOwnerGUID();
		$file->setFilename("video/$guid/icon-{$name}.jpg");
		$filepath = $file->getFilenameOnFilestore();

		if (!$file->delete()) {
			elgg_log("Failed to remove video thumbnail. Remove $filepath manually.", 'WARNING');
		}
	}

	if ($video->delete()) {
		system_message(elgg_echo("video:deleted"));
	} else {
		register_error(elgg_echo("video:deletefailed"));
	}
} else {
	register_error(elgg_echo("video:deletefailed"));
}

forward('video/all');
