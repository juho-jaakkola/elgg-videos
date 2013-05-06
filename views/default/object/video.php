<?php
/**
 * Video renderer.
 *
 * @package ElggVideo
 */

$full = elgg_extract('full_view', $vars, FALSE);
$video = elgg_extract('entity', $vars, FALSE);

if (!$video) {
	return TRUE;
}

$owner = $video->getOwnerEntity();
$container = $video->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($video->description);
$mime = $video->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$owner_link = elgg_view('output/url', array(
	'href' => "video/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$video_icon = elgg_view_entity_icon($video, 'small');

$date = elgg_view_friendly_time($video->time_created);

$comments_count = $video->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $video->getURL() . '#video-comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'video',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {
	$video_vars = array('sources' => $video->getSources());

	$width = elgg_get_plugin_setting('video_width', 'video');
	if ($width) {
		$video_vars['width'] = $width;
	}
	$height = elgg_get_plugin_setting('video_height', 'video');
	if ($height) {
		$video_vars['height'] = $height;
	}

	$player = elgg_view("output/video", $video_vars);

	$params = array(
		'entity' => $video,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$text = elgg_view('output/longtext', array('value' => $video->description));
	$body = "$player $text";

	echo elgg_view('object/elements/full', array(
		'entity' => $video,
		'title' => false,
		'icon' => $video_icon,
		'summary' => $summary,
		'body' => $body,
	));
} elseif (elgg_in_context('gallery')) {
	echo '<div class="video-gallery-item">';
	echo "<h3>" . $video->title . "</h3>";
	echo elgg_view_entity_icon($video, 'medium');
	echo "<p class='subtitle'>$owner_link</p>";
	echo '</div>';
} else {
	// brief view

	$params = array(
		'entity' => $video,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($video_icon, $list_body);
}
