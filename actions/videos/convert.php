<?php

$guid = get_input('guid');
$formats = get_input('formats');
$framesize = get_input('framesize');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	register_error(elgg_echo('videos:notfound'));
	forward(REFERER);
}

if (empty($formats)) {
	register_error(elgg_echo('videos:noformats'));
	forward(REFERER);
}

$inputfile = $video->getFilenameOnFilestore();
$filepath = $video->getFilenameOnFilestoreWithoutExtension();

foreach ($formats as $format) {
	$outputfile = "$filepath.$format"; 

	try {
		$converter = new VideoConverter();
		$converter->setInputfile($inputfile);
		$converter->setOutputfile($outputfile);
		$converter->setFrameSize($framesize);
		$converter->setOverwrite();
		$converter->execute();

		// TODO Check that the file exists and add the
		// format using $video->setConvertedFormats()
	} catch (exception $e) {
		register_error($e->getMessage());
		forward(REFERER);
	}
}

system_message(elgg_echo('videos:convert:success', array(implode(', ', $formats))));
forward("admin/videos/convert?guid=$guid");



