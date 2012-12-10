<?php
/**
 * Display a video
 *
 * @uses $vars['entity']
 */

$video = $vars['entity'];

$image_url = $video->getIconURL('large');
$image_url = elgg_format_url($image_url);
$download_url = elgg_get_site_url() . "video/download/{$video->getGUID()}";

$width = elgg_get_plugin_setting('video_width', 'video');
$vars['width'] = $width ? $width : '100%';
$vars['controls'] = 'controls';

$attributes = elgg_format_attributes($vars);

$formats = $video->getConvertedFormats();
foreach ($formats as $format) {
	$site_url = elgg_get_site_url();
	$video_url = "{$site_url}mod/video/video.php?video_guid={$video->guid}&format=$format";
	$sources .= "<source src=\"$video_url\" type=\"video/$format\">";
}

$sources .= elgg_echo('video:nosupport');

echo "<video $attributes>$sources</video>";
