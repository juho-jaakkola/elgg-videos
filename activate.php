<?php
/**
 * Register the ElggVideo class for the object/video subtype
 */

if (get_subtype_id('object', 'video')) {
	update_subtype('object', 'video', 'Video');
} else {
	add_subtype('object', 'video', 'Video');
}

/**
 * Register the VideoSource class for the object/video_source subtype
 */
if (get_subtype_id('object', 'video_source')) {
	update_subtype('object', 'video_source', 'VideoSource');
} else {
	add_subtype('object', 'video_source', 'VideoSource');
}
