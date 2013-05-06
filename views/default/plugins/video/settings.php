<?php

$video_width_label = elgg_echo('video:setting:label:video_width');
$video_width_input = elgg_view('input/text', array(
	'name' => 'params[video_width]',
	'value' => $vars['entity']->video_width,
));

$video_height_label = elgg_echo('video:setting:label:video_height');
$video_height_input = elgg_view('input/text', array(
	'name' => 'params[video_height]',
	'value' => $vars['entity']->video_height,
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

$square_icons_label = elgg_echo('video:setting:label:square_icons');
$square_icons_input = elgg_view('input/dropdown', array(
	'name' => 'params[square_icons]',
	'options_values' => array(
		true => elgg_echo('option:yes'),
		false => elgg_echo('option:no')
	),
	'value' => $vars['entity']->square_icons,
));

echo <<<FORM
<div>
	<label>$video_width_label</label>
	$video_width_input
</div>
<div>
	<label>$video_height_label</label>
	$video_height_input
</div>
<div>
	<label>$period_label</label>
	$period_input
</div>
<div>
	<label>$square_icons_label</label>
	$square_icons_input
</div>
FORM;
