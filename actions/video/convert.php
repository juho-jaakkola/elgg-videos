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

$success = array();
foreach ($formats as $format) {
	$outputfile = "$filepath.$format"; 

	try {
		$converter = new VideoConverter();
		$converter->setInputfile($inputfile);
		$converter->setOutputfile($outputfile);
		$converter->setFrameSize($framesize);
		$converter->setOverwrite();
		$converter->convert();

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