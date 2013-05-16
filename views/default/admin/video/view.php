<?php

elgg_load_library('elgg:video');

$guid = get_input('guid');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('notfound'));
	forward(REFERER);
}

echo elgg_view_title($video->title);


/**
 * Display video info
 */
$filepath = $video->getFilenameOnFilestore();
$rows = array(
	array('guid', $video->getGUID()),
	array(elgg_echo('video:location'), $filepath),
	array(elgg_echo('video:resolution'), $video->resolution),
	array(elgg_echo('video:bitrate'), $video->bitrate),
	array(elgg_echo('video:size'), filesize($filepath)),
);
$table = elgg_view('output/table', array(
	'rows' => $rows,
	'table_class' => 'elgg-table-alt'
));

echo elgg_view_module('inline', elgg_echo('video:info'), $table);


/**
 * Dispaly summary of different video versions
 */

$headers = array(
	elgg_echo('video:format'),
	elgg_echo('video:size'),
	elgg_echo('video:resolution'),
	elgg_echo('video:bitrate'),
	elgg_echo('video:location'),
	elgg_echo('video:status'),
	'',
);

$rows = array();
$total_size = 0;
foreach($video->getSources() as $source) {
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => "action/video/delete_format?guid={$source->getGUID()}",
		'text' => elgg_echo('delete'),
	));

	$file = $source->getFilenameOnFilestore();
	if (file_exists($file)) {
		$filesize = filesize($file);
	} else {
		$filesize = "-";
	}

	$total_size += $filesize;

	$status = elgg_echo('ok');
	if (!file_exists($file) || $filesize == 0 || $source->conversion_done == false) {
		$status = elgg_echo('video:pending');
		$status = "<span style=\"color: red;\">$status</span>";
	}

	$row = array(
		$source->format,
		$filesize,
		$source->resolution,
		$source->bitrate,
		$file,
		$status,
		$delete_link,
	);

	$rows[] = $row;
}

// Add a row that displays total size of all versions
$rows[] = array('', "<b>$total_size</b>", '', '', '', '', '');

$table = elgg_view('output/table', array(
	'headers' => $headers,
	'rows' => $rows,
	'table_class' => 'elgg-table-alt'
));

echo elgg_view_module('inline', elgg_echo('video:formats'), $table);

echo elgg_view('output/url', array(
	'href' => $video->getURL(),
	'text' => elgg_echo('video:view'),
	'class' => 'elgg-button elgg-button-action'
));

echo elgg_view('output/url', array(
	'href' => "admin/video/convert?guid=$guid",
	'text' => elgg_echo('video:reconvert'),
	'class' => 'elgg-button elgg-button-action'
));

echo elgg_view('output/confirmlink', array(
	'href' => "action/video/delete?guid=$guid",
	'text' => elgg_echo('delete'),
	'class' => 'elgg-button elgg-button-action'
));

echo elgg_view('output/confirmlink', array(
	'href' => "action/video/reset?guid=$guid",
	'text' => elgg_echo('reset'),
	'class' => 'elgg-button elgg-button-action'
));