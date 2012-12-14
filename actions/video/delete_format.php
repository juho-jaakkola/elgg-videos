<?php
/**
* Delete specific version of a video
* 
* @package Video
*/

$guid = (int) get_input('guid');
$format = get_input('format');
$resolution = get_input('resolution');

$video = new Video($guid);
if (!$video->guid) {
	register_error(elgg_echo("video:notfound"));
	forward('video/all');
}

// Check that the specific format exists
$formats = $video->getConvertedFormats();
if (!in_array($format, $formats)) {
	register_error(elgg_echo("video:formatnotfound"));
	forward(REFERER);
}

if ($video->deleteFormat($format, $resolution)) {
	system_message(elgg_echo("video:formatdeleted"));
} else {
	register_error(elgg_echo("video:formatdeletefailed"));
}

forward(REFERER);

