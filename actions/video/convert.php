<?php

$guid = get_input('guid');
$format = get_input('format');
$resolution = get_input('resolution');
$bitrate = get_input('bitrate');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('video:notfound'));
	forward(REFERER);
}

if (empty($format)) {
	register_error(elgg_echo('video:noformats'));
	forward(REFERER);
}

if (empty($resolution)) {
	$resolution = $video->resolution;
}

// See if a source with the same format and resolution already exists
$existing = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'video_source',
	'container_guid' => $video->getGUID(),
	'metadata_name_value_pairs' => array(
		'format' => $format,
		'resolution' => $resolution
	),
));

if (!empty($existing)) {
	// Reconvert an existing source
	$source = $existing[0];
	$outputfile = $source->getFilenameOnFilestore();
} else {
	$basename = $video->getFilenameWithoutExtension();
	$filename = "video/{$video->getGUID()}/{$basename}_{$resolution}.{$format}";

	// Create a new entity that represents the physical file
	$source = new VideoSource();
	$source->format = $format;
	$source->setFilename($filename);
	$source->setMimeType("video/$format");
	$source->resolution = $resolution;
	$source->bitrate = $bitrate;
	$source->owner_guid = $video->getOwnerGUID();
	$source->container_guid = $video->getGUID();
	$source->access_id = $video->access_id;
	$source->save();
}

try {
	$converter = new VideoConverter();
	$converter->setInputfile($video->getFilenameOnFilestore());
	$converter->setOutputfile($source->getFilenameOnFilestore());
	$converter->setResolution($resolution);
	$converter->setBitrate($bitrate);
	$converter->convert();

	$source->conversion_done = true;
	system_message(elgg_echo('video:convert:success', array($format)));
} catch (exception $e) {
	// Delete the faulty format
	$source->delete();

	$message = elgg_echo('VideoException:ConversionFailed', array(
		$outputfile,
		$e->getMessage(),
		$converter->getCommand()
	));

	register_error($message);
}

forward("admin/video/view?guid=$guid");