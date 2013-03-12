<?php

$guid = get_input('guid');
$format = get_input('format');
$framesize = get_input('framesize');
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

if (empty($framesize)) {
	$framesize = $video->resolution;
}

// See if a source with the same format and resolution already exists
$existing = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'video_source',
	'container_guid' => $video->getGUID(),
	'metadata_name_value_pairs' => array(
		'format' => $format,
		'resolution' => $framesize
	),
));

if (!empty($existing)) {
	// Reconvert an existing source
	$source = $existing[0];
	$outputfile = $source->getFilenameOnFilestore();
} else {
	// TODO This is ugly and confusing
	$filepath = $video->getFilenameOnFilestoreWithoutExtension();
	$filename = $video->getFilenameWithoutExtension();
	$outputfile = "{$filepath}{$framesize}.$format";
	$filename = "video/{$filename}{$framesize}.$format";

	// Create a new entity that represents the physical file
	$source = new VideoSource();
	$source->format = $format;
	$source->setFilename($filename);
	$source->setMimeType("video/$format");
	$source->resolution = $framesize;
	$source->owner_guid = $video->getOwnerGUID();
	$source->container_guid = $video->getGUID();
	$source->access_id = $video->access_id;
	$source->save();
}

$inputfile = $video->getFilenameOnFilestore();

try {
	$converter = new VideoConverter();
	$converter->setInputfile($inputfile);
	$converter->setOutputfile($outputfile);
	$converter->setFrameSize($framesize);
	$converter->setBitrate($bitrate);
	$converter->setOverwrite();
	$converter->convert();

	$video->addConvertedFormat($format);
	system_message(elgg_echo('video:convert:success', array($format)));
} catch (exception $e) {
	// Delete the faulty format
	$source->delete();

	$message = elgg_echo('VideoException:ConversionFailed', array(
		$e->getMessage(),
		$converter->getCommand()
	));
	register_error($message);
}

forward("admin/video/view?guid=$guid");