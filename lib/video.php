<?php
/**
 * Video helper functions
 *
 * @package ElggVideo
 */

/**
 * Prepare the upload/edit form variables
 *
 * @param object $video
 * @return array
 */
function video_prepare_form_vars($video = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $video,
	);

	if ($video) {
		foreach (array_keys($values) as $field) {
			if (isset($video->$field)) {
				$values[$field] = $video->$field;
			}
		}
	}

	if (elgg_is_sticky_form('video')) {
		$sticky_values = elgg_get_sticky_values('video');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('video');

	return $values;
}

function video_get_page_contents_list ($container = 0) {
	$options = array(
		'type' => 'object',
		'subtype' => 'video',
		'full_view' => false,
	);
	$videos = elgg_list_entities($options);
	
	elgg_register_title_button();
	
	$params = array(
		'title' => elgg_echo('video'),
		'content' => $videos,
	);
	
	return $params;
}

function video_get_page_contents_upload () {
	$owner = elgg_get_page_owner_entity();

	// set up breadcrumbs
	if (elgg_instanceof($owner, 'user')) {
		elgg_push_breadcrumb($owner->name, "video/owner/$owner->username");
	} else {
		elgg_push_breadcrumb($owner->name, "video/group/$owner->guid/all");
	}

	$title = elgg_echo('video:add');
	elgg_push_breadcrumb($title);

	// Video upload form
	$form_vars = array('enctype' => 'multipart/form-data');
	$body_vars = video_prepare_form_vars();
	$form = elgg_view_form('video/upload', $form_vars, $body_vars);

	$params = array(
		'title' => $title,
		'content' => $form,
	);

	return $params;
}

/**
 * Edit a video
 *
 * @package Video
 */
function video_get_page_contents_edit ($video_guid) {
	elgg_load_library('elgg:video');

	$video = new FilePluginFile($video_guid);
	if (!$video) {
		forward();
	}
	if (!$video->canEdit()) {
		forward();
	}

	$title = elgg_echo('video:edit');

	elgg_push_breadcrumb(elgg_echo('video'), "video/all");
	elgg_push_breadcrumb($video->title, $video->getURL());
	elgg_push_breadcrumb($title);

	elgg_set_page_owner_guid($video->getContainerGUID());

	$form_vars = array('enctype' => 'multipart/form-data');
	$body_vars = video_prepare_form_vars($video);

	$content = elgg_view_form('video/upload', $form_vars, $body_vars);

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);

	return $params;
}

/**
 * Disply individual's or group's videos
 */
function video_get_page_contents_owner () {
	// access check for closed groups
	group_gatekeeper();
	
	$owner = elgg_get_page_owner_entity();
	if (!$owner) {
		forward('video/all');
	}
	
	elgg_push_breadcrumb($owner->name);
	
	elgg_register_title_button();
	
	$params = array();
	
	if ($owner->guid == elgg_get_logged_in_user_guid()) {
		// user looking at own videos
		$params['filter_context'] = 'mine';
	} else if (elgg_instanceof($owner, 'user')) {
		// someone else's videos
		// do not show select a tab when viewing someone else's posts
		$params['filter_context'] = 'none';
	} else {
		// group videos
		$params['filter'] = '';
	}
	
	$title = elgg_echo("video:user", array($owner->name));
	
	// List videos
	$content = elgg_list_entities(array(
		'types' => 'object',
		'subtypes' => 'video',
		'container_guid' => $owner->guid,
		'limit' => 10,
		'full_view' => FALSE,
	));
	if (!$content) {
		$content = elgg_echo("video:none");
	}
	
	$sidebar = elgg_view('video/sidebar');
	
	$params['content'] = $content;
	$params['title'] = $title;
	$params['sidebar'] = $sidebar;

	return $params;
}

/**
 * Get page components to view a video.
 *
 * @param int $guid GUID of a video entity.
 * @return array
 */
function video_get_page_contents_view ($guid = null) {
	$video = get_entity($guid);
	if (!$video) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}
	
	$owner = elgg_get_page_owner_entity();

	$crumbs_title = $owner->name;
	if (elgg_instanceof($owner, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "video/group/$owner->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "video/owner/$owner->username");
	}

	$title = $video->title;

	elgg_push_breadcrumb($title);

	if ($video->conversion_done) {
		$content = elgg_view_entity($video, array('full_view' => true));
		$content .= elgg_view_comments($video);
	} else {
		$string = elgg_echo('video:conversion_pending');
		$content = "<div>$string</div>";
	}

	/*
	elgg_register_menu_item('title', array(
		'name' => 'download',
		'text' => elgg_echo('video:download'),
		'href' => "video/download/$video->guid",
		'link_class' => 'elgg-button elgg-button-action',
	));
	*/
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	
	return $params;
}
