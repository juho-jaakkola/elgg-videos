<?php
/**
 * Register the ElggVideo class for the object/video subtype
 */

if (get_subtype_id('object', 'video')) {
	update_subtype('object', 'video', 'Video');
} else {
	add_subtype('object', 'video', 'Video');
}
