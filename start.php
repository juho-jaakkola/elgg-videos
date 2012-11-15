<?php

elgg_register_event_handler('init', 'system', 'videos_init');

function videos_init () {
	elgg_register_library('elgg:videos', elgg_get_plugins_path() . 'videos/lib/videos.php');

	$actionspath = elgg_get_plugins_path() . 'videos/actions/videos/';
	elgg_register_action('videos/upload', $actionspath . 'upload.php');
	
	elgg_register_page_handler('videos', 'videos_page_handler');
	
	// Site navigation
	$item = new ElggMenuItem('videos', elgg_echo('videos'), 'videos/all');
	elgg_register_menu_item('site', $item);

	elgg_register_entity_url_handler('object', 'video', 'videos_url_override');
	//elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'videos_icon_url_override');
}

function videos_page_handler ($page) {
	elgg_load_library('elgg:videos');
	
	switch ($page[0]) {
		case 'view':
			$params = videos_get_page_contents_view($page[1]);
			break;
		case 'owner':
			$params = videos_get_page_contents_owner();
			break;
		case 'add':
			$params = videos_get_page_contents_upload();
			break;
		case 'all':
		default:
			$params = videos_get_page_contents_list();
			break;
	}

	$body = elgg_view_layout('content', $params);
	
	echo elgg_view_page($params['title'], $body);
	return true;
}


/**
 * Populates the ->getUrl() method for video objects
 *
 * @param ElggEntity $entity Video entity
 * @return string Video URL
 */
function videos_url_override($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "videos/view/" . $entity->getGUID() . "/" . $title;
}