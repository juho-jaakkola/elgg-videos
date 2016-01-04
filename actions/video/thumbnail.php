<?php
/**
 * Action for creating a new thumbnail for video
 *
 * @package Video
 */

$guid = get_input('guid');
$position = get_input('position');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('notfound'));
	forward();
}

if (!$position || !is_numeric($position)) {
	register_error(elgg_echo('video:error:invalid_position'));
	forward(REFERER);
}

elgg_load_library('elgg:video');

if (video_create_thumbnails($video, $position)) {
	forward($video->getURL());
	system_message(elgg_echo('video:thumbnail:success'));
} else {
	forward(REFERER);
	register_error(elgg_echo('video:error:thumbnail_creation_failed'));
}
