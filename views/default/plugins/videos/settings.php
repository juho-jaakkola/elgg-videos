<?php

$instructions = elgg_echo('videos:setting:instructions');

$formats = $vars['entity']->getMetadata('formats');

$formats_label = elgg_echo('videos:setting:label:formats');
$formats_input = elgg_view('input/checkboxes', array(
	'name' => 'params[formats]',
	'options' => array(
		'mp4' => 'mp4',
		'webm' => 'webm',
		'ogg' => 'ogg',
	),
	'value' => $formats,
));

$framesize_label = elgg_echo('videos:setting:label:framesize');
$framesize_input = elgg_view('input/dropdown', array(
	'name' => 'params[framesize]',
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

$video_width_label = elgg_echo('videos:setting:label:video_width');
$video_width_input = elgg_view('input/text', array(
	'name' => 'params[video_width]',
	'value' => $vars['entity']->video_width,
));

$period_label = elgg_echo('videos:setting:label:period');
$period_input = elgg_view('input/dropdown', array(
	'name' => 'params[period]',
	'options_values' => array(
		'minute' => elgg_echo('videos:minute'),
		'fiveminute' => elgg_echo('videos:fiveminute'),
		'fifteenmin' => elgg_echo('videos:fifteenmin'),
		'halfhour' => elgg_echo('videos:halfhour'),
		'hourly' => elgg_echo('videos:hourly'),
		'daily' => elgg_echo('videos:daily'),
	),
	'value' => $vars['entity']->period,
));

echo <<<FORM
<div><p>$instructions</p></div>
<div>
	<label>$formats_label</label>
	$formats_input
</div>
<div>
	<label>$framesize_label</label>
	$framesize_input
</div>
<div>
	<label>$video_width_label</label>
	$video_width_input
</div>
<div>
	<label>$period_label</label>
	$period_input
</div>
FORM;
