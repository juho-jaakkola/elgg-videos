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
	$formats = array(
		'webm' => 'webm',
		'mp4' => 'mp4',
		'mpg' => 'mpg',
		'ogv' => 'ogg',
		'ogg' => 'ogg',
		'MOV' => 'MOV',
	);
	foreach ($formats as $format => $mime_type) {
		$site_url = elgg_get_site_url();
		$video_url = "{$site_url}mod/videos/video.php?video_guid={$video->guid}&format=$format";
	
	echo <<<HTML
		<source src="$video_url" type="video/$mime_type">
HTML;
	}
}

echo elgg_echo('videos:nosupport');
echo '</video>';
