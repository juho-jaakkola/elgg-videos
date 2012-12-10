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

$inputfile = $video->getFilenameOnFilestore();
$filepath = $video->getFilenameOnFilestoreWithoutExtension();

$format_errors = array();
$format_success = array();
foreach ($formats as $format) {
	$outputfile = "$filepath.$format"; 

	try {
		$converter = new VideoConverter();
		$converter->setInputfile($inputfile);
		$converter->setOutputfile($outputfile);
		$converter->setFrameSize($framesize);
		$converter->setOverwrite();
		$converter->convert();

		if (file_exists($outputfile) && filesize($outputfile) > 0) {
			$format_success[] = $format;
			$video->addConvertedFormat($format);
		} else {
			$format_errors[] = $format;
		}
	} catch (exception $e) {
		register_error($e->getMessage());
		forward(REFERER);
	}
}

if (!empty($format_erros)) {
	$format_errors = implode(', ', $format_errors);
	register_error(elgg_echo('video:admin:conversion_error', array($video->title, $format_errors)));
}

system_message(elgg_echo('video:convert:success', array(implode(', ', $format_success))));
forward("admin/video/convert?guid=$guid");



