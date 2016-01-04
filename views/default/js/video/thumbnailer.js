/**
 * Get position of current frame as seconds and set as value of form field
 */
define(function(require) {
	var $ = require('jquery');
	var video = $('#elgg-video');

	video.on('pause', function() {
		$('#video-position').val(this.currentTime);
	});
});