<?php

$resolution = get_input('resolution');
$format = get_input('format');
$bitrate = get_input('bitrate');

$flavor = array(
	'format' => $format,
	'resolution' => $resolution,
	'bitrate' => $bitrate,
);

$success = video_add_flavor_setting($flavor);

if ($success) {
	system_message(elgg_echo('admin:video:add_flavor:success'));
} else {
	register_error(elgg_echo('admin:video:add_flavor:error'));
}

forward('admin/video/flavors');
