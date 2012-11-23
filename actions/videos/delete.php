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
