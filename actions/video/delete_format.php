<?php
/**
* Delete specific version of a video
*
* @package Video
*/

$guid = (int) get_input('guid');

$video = get_entity($guid);
if (!elgg_instanceof($video, 'object', 'video_source')) {
	register_error(elgg_echo("video:notfound"));
	forward(REFERER);
}

if ($video->delete()) {
	system_message(elgg_echo("video:formatdeleted"));
} else {
	register_error(elgg_echo("video:formatdeletefailed"));
}

forward(REFERER);

