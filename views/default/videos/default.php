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

echo '<video width="320" height="240" controls="controls">';

if ($vars['full_view']) {
	$formats = $video->getConvertedFormats();
	foreach ($formats as $format) {
		$site_url = elgg_get_site_url();
		$video_url = "{$site_url}mod/videos/video.php?video_guid={$video->guid}&format=$format";
	
	echo <<<HTML
		<source src="$video_url" type="video/$format">
HTML;
	}
}

echo elgg_echo('videos:nosupport');
echo '</video>';
