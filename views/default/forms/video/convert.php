<?php

$guid = isset($vars['guid']) ? $vars['guid'] : null;

$guid_label = elgg_echo('video:label:guid');
$guid_input = elgg_view('input/text', array(
	'name' => 'guid',
	'value' => $guid,
	'style' => 'width: 10%;'
));

$formats_label = elgg_echo('video:format');
$formats_input = elgg_view('input/dropdown', array(
	'name' => 'format',
	'options' => array(
		'mp4' => 'mp4',
		'webm' => 'webm',
		'ogg' => 'ogg',
	),
	'value' => $formats,
));

$resolution_label = elgg_echo('video:resolution');
$resolution_input = elgg_view('input/dropdown', array(
	'name' => 'resolution',
	'options_values' => video_get_resolution_options(),
	'value' => $vars['entity']->resolution,
));

$bitrate_label = elgg_echo('video:setting:label:bitrate');
$bitrate_input = elgg_view('input/text', array(
	'name' => 'bitrate',
	'value' => $vars['entity']->bitrate,
	'style' => 'width: 10%;'
));

$submit_input = elgg_view('input/submit');

echo <<<FORM
<div>
	<label>$guid_label</label>
	$guid_input
</div>
<div>
	<label>$formats_label</label>
	$formats_input
</div>
<div>
	<label>$resolution_label</label>
	$resolution_input
</div>
<div>
	<label>$bitrate_label</label>
	$bitrate_input
</div>
<div class="elgg-foot">
	$submit_input
</div>
FORM;
