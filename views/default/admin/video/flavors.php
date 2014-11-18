<?php

$flavors = video_get_flavor_settings();

$info = elgg_echo('video:flavors:info');

if (empty($flavors)) {
	$flavors = elgg_echo('admin:video:no_flavors');
} else {
	foreach ($flavors as $key => $flavor) {
		$flavors[$key][] = elgg_view('output/confirmlink', array(
			'href' => "action/video/delete_flavor?id=$key",
			'text' => elgg_echo('delete'),
		));
	}

	$headers = array(
		elgg_echo('video:format'),
		elgg_echo('video:resolution'),
		elgg_echo('video:bitrate'),
		'',
	);

	$table = elgg_view('output/table', array(
		'headers' => $headers,
		'rows' => $flavors,
		'table_class' => 'elgg-table-alt',
	));
}

$body = elgg_view_module('inline', elgg_echo('admin:video:flavors'), $table);

$button = elgg_view('output/url', array(
	'href' => 'admin/video/add_flavor',
	'text' => elgg_echo('admin:video:add_flavor'),
	'class' => 'elgg-button elgg-button-action',
));

echo <<<HTML
	<div>$info</div>
	<div>$body</div>
	<div>$button</div>
HTML;
