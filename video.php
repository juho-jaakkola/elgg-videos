<?php
/**
 * Elgg video
 *
 * @package ElggVideo
 */

require_once(dirname(dirname(__DIR__)) . '/vendor/autoload.php');

\Elgg\Application::start();

$guid = (int) get_input('guid', 0);
$video_guid = (int) get_input('video_guid', 0);

$source = get_entity($guid);

if ($source && elgg_instanceof($source, 'object', 'video_source')) {
	$file = $source->getFilenameOnFilestore();
	$format = $source->format;
} else {
	// This allows to select a particular video quality
	$video = get_entity($video_guid);

	if (!$video || $video->getSubtype() != "video") {
		exit;
	}

	$format = get_input('format');
	$resolution = get_input('resolution');
	$filepath = $video->getFilenameOnFilestoreWithoutExtension();
	$file = "{$filepath}_{$resolution}.$format";
}

$fp = @fopen($file, 'rb');

$size   = filesize($file); // File size
$length = $size;           // Content length
$start  = 0;               // Start byte
$end    = $size - 1;       // End byte

header("Content-type: video/$format");
header("Accept-Ranges: 0-$length");

if (isset($_SERVER['HTTP_RANGE'])) {
	$c_start = $start;
	$c_end   = $end;

	// Extract the range string
	list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);

	// Make sure the client hasn't sent a multibyte range
	if (strpos($range, ',') !== false) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		header("Content-Range: bytes $start-$end/$size");
		exit;
	}

	// If the range starts with an '-' we start from the
	// beginning. If not, we forward the file pointer
	// and make sure to get the end byte if specified.
	if ($range == '-') {
		// The n-number of the last bytes is requested
		$c_start = $size - substr($range, 1);
	} else {
		$range  = explode('-', $range);
		$c_start = $range[0];
		$c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
	}

	// End bytes can not be larger than $end.
	$c_end = ($c_end > $end) ? $end : $c_end;

	// Validate the requested range and return an error if it's not correct
	if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		header("Content-Range: bytes $start-$end/$size");
		exit;
	}

	$start  = $c_start;
	$end    = $c_end;
	$length = $end - $start + 1; // Calculate new content length

	fseek($fp, $start);
	header('HTTP/1.1 206 Partial Content');
}

// Notify the client the byte range we'll be outputting
header("Content-Range: bytes $start-$end/$size");
header("Content-Length: $length");

// Start buffered download
$buffer = 1024 * 8;
while (!feof($fp) && ($p = ftell($fp)) <= $end) {
	if ($p + $buffer > $end) {
		// In case we're only outputtin a chunk, make sure we don't
		// read past the length
		$buffer = $end - $p + 1;
	}

	set_time_limit(0); // Reset time limit for big files
	echo fread($fp, $buffer);
	flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
}

fclose($fp);
exit;