<?php
/**
 * Elgg video
 *
 * @package ElggVideo
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get video GUID
$video_guid = (int) get_input('video_guid', 0);

$format = get_input('format');

$resolution = get_input('resolution');
if (empty($resolution)) {
	$resolution = '';
}

$video = get_entity($video_guid);
if (!$video || $video->getSubtype() != "video" || !$format) {
	exit;
}

$filepath = $video->getFilenameOnFilestoreWithoutExtension();
$file = "{$filepath}{$resolution}.$format";

ob_clean();
header("Content-type: video/$format");
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . filesize($file));
readfile($file);
exit;