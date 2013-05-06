<?php

$id = get_input('id');

$success = video_delete_flavor_setting($id);

if ($success) {
	system_message(elgg_echo('admin:video:delete_flavor:success'));
} else {
	register_error(elgg_echo('admin:video:delete_flavor:error'));
}

forward('admin/video/flavors');
