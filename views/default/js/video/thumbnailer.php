<?php
/**
 * Get position of current frame as seconds and set as value of form field
 *
 * @package Video
 */
?>
elgg.provide('elgg.video');

elgg.video.init = function() {
	$('#elgg-video').live('click', function() {
		var video = document.getElementById("elgg-video");
		var position = video.currentTime;

		$('#video-position').val(position);
	})
};

elgg.register_hook_handler('init', 'system', elgg.video.init);