<?php
/**
 * Elgg video thumbnail
 *
 * @package Video
 */

// Get video GUID
$video_guid = (int) get_input('video_guid', 0);

// Get video thumbnail size
$size = get_input('size', 'small');

$video = get_entity($video_guid);

if (!elgg_instanceof($video, 'object', 'video')) {
	exit;
}

$filename = "video/{$video_guid}/icon-{$size}.jpg";

// Grab the file
$thumb = new ElggFile();
$thumb->owner_guid = $video->owner_guid;
$thumb->setFilename($filename);
$storename = $thumb->getFilenameOnFilestore();
$contents = $thumb->grabFile();

// caching images for 10 days
header("Content-type: image/jpg");
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($contents));

echo $contents;
exit;


