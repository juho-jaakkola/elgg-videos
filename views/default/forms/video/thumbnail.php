<?php
$position_label = elgg_echo('video:thumbnail:position');

$position_input = elgg_view('input/text', array(
	'name' => 'position',
	'id' => 'video-position',
	'style' => 'width: 100px;',
));

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$submit_input = elgg_view('input/submit');

echo <<<FORM
	<div>
		$position_label
		$position_input
	</div>
	<div class="elgg-foot">
		$guid_input
		$submit_input
	</div>
FORM;
