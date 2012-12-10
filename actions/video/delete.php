<?php
/**
* Delete video
* 
* @package Video
*/

$guid = (int) get_input('guid');

$video = new Video($guid);
if (!$video->guid) {
	register_error(elgg_echo("video:deletefailed"));
	forward('video/all');
}

if (!$video->canEdit()) {
	register_error(elgg_echo("video:deletefailed"));
	forward($video->getURL());
}

// Delete all thumbnails from diskspace
$icon_sizes = elgg_get_config('icon_sizes');
foreach ($icon_sizes as $name => $size_info) {
	$file = new ElggFile();
	$file->owner_guid = $video->getOwnerGUID();
	$file->setFilename("video/{$video->getGUID()}{$name}.jpg");
	$filepath = $file->getFilenameOnFilestore();

	if (!$file->delete()) {
		elgg_log("Video thumbnail remove failed. Remove $filepath manually, please.", 'WARNING');
	}
}

$container = $video->getContainerEntity();

if (!$video->delete()) {
	register_error(elgg_echo("video:deletefailed"));
} else {
	system_message(elgg_echo("video:deleted"));
}

if (elgg_instanceof($container, 'group')) {
	forward("video/group/$container->guid/all");
} else {
	forward("video/owner/$container->username");
}
