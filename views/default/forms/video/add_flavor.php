<?php

$format_label = elgg_echo('video:format');
$format_input = elgg_view('input/dropdown', array(
	'name' => 'format',
	'options' => array(
		'mp4' => 'mp4',
		'webm' => 'webm',
		'ogg' => 'ogg',
	),
));

$resolution_label = elgg_echo('video:resolution');
$resolution_input = elgg_view('input/dropdown', array(
	'name' => 'resolution',
	'options_values' => video_get_resolution_options(),
));

$bitrate_label = elgg_echo('video:bitrate');
$bitrate_input = elgg_view('input/text', array(
	'name' => 'bitrate'
));

$submit_input = elgg_view('input/submit', array(
	'text' => elgg_echo('save')
));

echo <<<FORM
	<div>
		<label>$format_label</label><br />
		$format_input
	</div>
	<div>
		<label>$resolution_label</label><br />
		$resolution_input
	</div>
	<div>
		<label>$bitrate_label</label>
		$bitrate_input
	</div>
	<div>
		$submit_input
	</div>
FORM;
