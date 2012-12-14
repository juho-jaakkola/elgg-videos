<?php

$guid = get_input('guid');
$formats = get_input('formats');
$framesize = get_input('framesize');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('video:notfound'));
	forward(REFERER);
}

if (empty($formats)) {
	register_error(elgg_echo('video:noformats'));
	forward(REFERER);
}

if (empty($framesize)) {
	$framesize = '';
}

$inputfile = $video->getFilenameOnFilestore();
$filepath = $video->getFilenameOnFilestoreWithoutExtension();

$filename = $video->getFilenameWithoutExtension();

$success = array();
foreach ($formats as $format) {
	$outputfile = "{$filepath}{$framesize}.$format";
	$filename = "video/{$filename}{$framesize}.$format";

	try {
		$converter = new VideoConverter();
		$converter->setInputfile($inputfile);
		$converter->setOutputfile($outputfile);
		$converter->setFrameSize($framesize);
		$converter->setOverwrite();
		$converter->convert();

		// Create an entity that represents the physical file
		$source = new VideoSource();
		$source->format = $format;
		$source->setFilename($filename);
		$source->setMimeType("video/$format");
		$source->resolution = $framesize;
		$source->owner_guid = $video->getOwnerGUID();
		$source->container_guid = $video->getGUID();
		$source->access_id = $video->access_id;
		$source->save();

		$video->addConvertedFormat($format);
		$success[] = $format;
	} catch (exception $e) {
		$message = elgg_echo('VideoException:ConversionFailed', array(
			$e->getMessage(),
			$converter->getCommand()
		));
		register_error($message);
	}
}

if (!empty($success)) {
	system_message(elgg_echo('video:convert:success', array(implode(', ', $success))));
}

forward("admin/video/view?guid=$guid");