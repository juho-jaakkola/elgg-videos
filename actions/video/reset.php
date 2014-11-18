<?php
/**
 * Remove all converted sources and mark video as unconverted.
 *
 * This action is meant for administrators. It can be used when video
 * flavor configuration has changed and new sources need to be created.
 */
$guid = get_input('guid');

$video = get_entity($guid);

if (elgg_instanceof($video, 'object', 'video') && $video->canEdit()) {
	// Remove existing sources
	foreach ($video->getSources() as $source) {
		$source->delete();
	}

	// Define new sources based on current configuration
	$video->setSources();

	// Mark the original video as unconverted
	$video->conversion_done = false;
}

forward(REFERER);
