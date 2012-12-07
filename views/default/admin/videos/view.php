<?php

elgg_load_library('elgg:videos');

$guid = get_input('guid');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('notfound'));
	forward(REFERER);
}

echo elgg_view_title($video->title);

$headers = array(
	elgg_echo('videos:format'),
	elgg_echo('videos:size'),
	elgg_echo('status'),
	'',
);

$rows = array();
foreach($video->getConvertedFormats() as $format) {
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => "action/videos/delete_format?guid=$guid&format=$format",
		'text' => elgg_echo('delete'),
	));

	$filename = $video->getFilenameOnFilestoreWithoutExtension();
	$file = "$filename.$format";
	$filesize = filesize($file);
	
	$status = elgg_echo('ok');
	if (!file_exists($file) || $filesize == 0) {
		$status = elgg_echo('error');
		$status = "<span style=\"color: red;\">$status</span>";
	}

	$row = array(
		$format,
		$filesize,
		$status,
		$delete_link,
	);

	$rows[] = $row;
}

$table = elgg_view('output/table', array(
	'headers' => $headers,
	'rows' => $rows,
	'table_class' => 'elgg-table-alt'
));

echo elgg_view_module('inline', elgg_echo('videos:formats'), $table);

echo elgg_view('output/url', array(
	'href' => "admin/videos/convert?guid=$guid",
	'text' => elgg_echo('videos:reconvert'),
	'class' => 'elgg-button elgg-button-action'
));

echo elgg_view('output/confirmlink', array(
	'href' => "action/videos/delete?guid=$guid",
	'text' => elgg_echo('delete'),
	'class' => 'elgg-button elgg-button-action'
));