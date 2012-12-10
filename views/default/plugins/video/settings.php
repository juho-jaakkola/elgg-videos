<?php

$instructions = elgg_echo('video:setting:instructions');

$formats = $vars['entity']->getMetadata('formats');

$formats_label = elgg_echo('video:setting:label:formats');
$formats_input = elgg_view('input/checkboxes', array(
	'name' => 'params[formats]',
	'options' => array(
		'mp4' => 'mp4',
		'webm' => 'webm',
		'ogg' => 'ogg',
	),
	'value' => $formats,
));

$framesize_label = elgg_echo('video:setting:label:framesize');
$framesize_input = elgg_view('input/dropdown', array(
	'name' => 'params[framesize]',
	'options_values' => video_get_framesize_options(),
	'value' => $vars['entity']->framesize,
));

$video_width_label = elgg_echo('video:setting:label:video_width');
$video_width_input = elgg_view('input/text', array(
	'name' => 'params[video_width]',
	'value' => $vars['entity']->video_width,
));

$period_label = elgg_echo('video:setting:label:period');
$period_input = elgg_view('input/dropdown', array(
	'name' => 'params[period]',
	'options_values' => array(
		'minute' => elgg_echo('video:minute'),
		'fiveminute' => elgg_echo('video:fiveminute'),
		'fifteenmin' => elgg_echo('video:fifteenmin'),
		'halfhour' => elgg_echo('video:halfhour'),
		'hourly' => elgg_echo('video:hourly'),
		'daily' => elgg_echo('video:daily'),
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
