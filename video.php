<?php
/**
 * Elgg video
 *
 * @package ElggVideo
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$video_guid = (int) get_input('video_guid', 0);
$format = get_input('format');
$resolution = get_input('resolution');

$video = get_entity($video_guid);
if (!$video || $video->getSubtype() != "video" || !$format || !$resolution) {
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