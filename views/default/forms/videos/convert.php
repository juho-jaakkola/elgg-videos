<?php

$guid = isset($vars['guid']) ? $vars['guid'] : null;

$guid_label = elgg_echo('videos:label:guid');
$guid_input = elgg_view('input/text', array(
	'name' => 'guid',
	'value' => $guid,
	'style' => 'width: 10%;'
));

$formats_label = elgg_echo('videos:setting:label:formats');
$formats_input = elgg_view('input/checkboxes', array(
	'name' => 'formats',
	'options' => array(
		'mp4' => 'mp4',
		'webm' => 'webm',
		'ogg' => 'ogg',
	),
	'value' => $formats,
));

$framesize_label = elgg_echo('videos:setting:label:framesize');
$framesize_input = elgg_view('input/dropdown', array(
	'name' => 'framesize',
	// TODO Get all the supported formats straight from the converter?
	'options_values' => array(
		'0' => 'same as source',
		'320x240' => '320x240 (qvga)',
		'640x480' => '640x480 (vga)',
		'852x480' => '852x480 (hd480)',
		'1280x720' => '1280x720 (hd720)',
		'1920x1080' => '1920x1080 (hd1080)',
	),
	'value' => $vars['entity']->framesize,
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
	<label>$framesize_label</label>
	$framesize_input
</div>
<div class="elgg-foot">
	$submit_input
</div>
FORM;
