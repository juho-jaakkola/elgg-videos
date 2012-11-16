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

$video = get_entity($video_guid);
if (!$video || $video->getSubtype() != "video" || !$format) {
	exit;
}

$name = "video/{$video->time_created}movie.$format";

$readvideo = new Video();
$readvideo->owner_guid = $video->owner_guid;
$readvideo->setFilename($name);
$mime = $video->getMimeType();
$contents = $readvideo->grabFile();

header("Content-type: $mime");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($contents));

echo $contents;
exit;
