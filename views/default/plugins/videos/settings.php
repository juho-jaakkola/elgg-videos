<?php

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
<div>
	<label>$formats_label</label>
	$formats_input
</div>
<div>
	<label>$period_label</label>
	$period_input
</div>
FORM;
