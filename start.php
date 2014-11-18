<?php

elgg_register_event_handler('init', 'system', 'video_init');

function video_init () {
	elgg_register_library('elgg:video', elgg_get_plugins_path() . 'video/lib/video.php');

	// register thumbnailing JavaScript
	$thumbnail_js = elgg_get_simplecache_url('js', 'video/thumbnailer');
	elgg_register_simplecache_view('js/video/thumbnailer');
	elgg_register_js('elgg.video.thumbnailer', $thumbnail_js);

	$actionspath = elgg_get_plugins_path() . 'video/actions/video/';
	elgg_register_action('video/upload', $actionspath . 'upload.php');
	elgg_register_action('video/delete', $actionspath . 'delete.php');
	elgg_register_action('video/edit', $actionspath . 'upload.php');
	elgg_register_action('video/thumbnail', $actionspath . 'thumbnail.php');
	elgg_register_action('video/settings/save', $actionspath . 'settings/save.php', 'admin');
	elgg_register_action('video/convert', $actionspath . 'convert.php', 'admin');
	elgg_register_action('video/delete_format', $actionspath . 'delete_format.php', 'admin');
	elgg_register_action('video/add_flavor', $actionspath . 'add_flavor.php', 'admin');
	elgg_register_action('video/delete_flavor', $actionspath . 'delete_flavor.php', 'admin');
	elgg_register_action('video/reset', $actionspath . 'reset.php', 'admin');

	// add to the main css
	elgg_extend_view('css/elgg', 'video/css');

	elgg_register_page_handler('video', 'video_page_handler');

	// Site navigation
	$item = new ElggMenuItem('video', elgg_echo('video'), 'video/all');
	elgg_register_menu_item('site', $item);

	elgg_register_entity_url_handler('object', 'video', 'video_url_override');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'video_icon_url_override');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'video_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'video_owner_block_menu');

	// Register cron hook
	$period = elgg_get_plugin_setting('period', 'video');
	elgg_register_plugin_hook_handler('cron', $period, 'video_conversion_cron');

	// Register an icon handler for video
	elgg_register_page_handler('videothumb', 'video_icon_handler');

	// Add group option
	add_group_tool_option('video', elgg_echo('video:enablevideo'), true);
	elgg_extend_view('groups/tool_latest', 'video/group_module');

	elgg_register_admin_menu_item('administer', 'convert', 'video');
	elgg_register_admin_menu_item('administer', 'flavors', 'video');
}

function video_page_handler ($page) {
	elgg_load_library('elgg:video');

	elgg_push_breadcrumb(elgg_echo('video'), 'video/all');

	switch ($page[0]) {
		case 'view':
			$params = video_get_page_contents_view($page[1]);
			break;
		case 'owner':
			video_register_toggle();
			$params = video_get_page_contents_owner();
			break;
		case 'add':
			$params = video_get_page_contents_upload();
			break;
		case 'edit':
			video_edit_menu_setup($page[1]);
			$params = video_get_page_contents_edit($page[1]);
			break;
		case 'group':
			$group = get_entity($page[1]);
			if (!elgg_instanceof($group, 'group')) {
				forward('', '404');
			}
			set_input('guid', $group->guid);

			$params = video_get_page_contents_owner();
			break;
		case 'thumbnail':
			video_edit_menu_setup($page[1]);
			$params = video_get_page_contents_edit_thumbnail($page[1]);
			break;
		case 'all':
		default:
			video_register_toggle();
			$params = video_get_page_contents_list();
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
function video_url_override($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "video/view/" . $entity->getGUID() . "/" . $title;
}

function video_edit_menu_setup($guid) {
	elgg_register_menu_item('page', array(
		'name' => 'edit_video',
		'href' => "video/edit/{$guid}",
		'text' => elgg_echo('video:edit'),
	));

	elgg_register_menu_item('page', array(
		'name' => 'edit_video_thumbnail',
		'href' => "video/thumbnail/{$guid}",
		'text' => elgg_echo('video:thumbnail:edit'),
	));
}

/**
 * Trigger the video conversion
 */
function video_conversion_cron($hook, $entity_type, $returnvalue, $params) {
	$ia = elgg_set_ignore_access(true);

	$videos = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'video',
		'limit' => 2,
		'metadata_name_value_pairs' => array(
			'name' => 'conversion_done',
			'value' => 0, // In metadata booleans are saved as 0|1
		)
	));

	elgg_load_library('elgg:video');

	foreach ($videos as $video) {
		$sources = $video->getSources();
		$success = true;
		foreach ($sources as $source) {
			// Converted sources may exist if previous conversion has been interrupted
			if ($source->conversion_done == true) {
				continue;
			}

			try {
				$filename = $source->getFilenameOnFilestore();

				// Create a new video file to data directory
				$converter = new VideoConverter();
				$converter->setInputFile($video->getFilenameOnFilestore());
				$converter->setOutputFile($filename);
				$converter->setResolution($source->resolution);
				$converter->setBitrate($source->bitrate);
				$result = $converter->convert();

				// Save video details
				$info = new VideoInfo($source);
				$source->resolution = $info->getResolution();
				$source->bitrate = $info->getBitrate();
				$source->conversion_done = true;
				$source->save();

				echo "<p>Successfully created video file {$source->getFilename()}</p>";
			} catch (Exception $e) {
				// Print simple error to screen
				echo "<p>Failed to create video file {$source->getFilename()}</p>";

				$success = false;

				// Print detailed error to error log
				$message = elgg_echo('VideoException:ConversionFailed', array(
					$filename,
					$e->getMessage(),
					$converter->getCommand()
				));
				error_log($message);

				elgg_add_admin_notice('conversion_error', $message);
			}
		}

		if ($success) {
			$video->conversion_done = true;
			add_to_river('river/object/video/create', 'create', $video->getOwnerGUID(), $video->getGUID());
		}
	}

	elgg_set_ignore_access($ia);

	return $returnvalue;
}

/**
 * Get video formats configured in plugin settings
 *
 * @return null|array
 */
function video_get_formats() {
	$plugin = elgg_get_plugin_from_id('video');
	$formats = $plugin->getMetadata('formats');

	if (is_array($formats)) {
		return $formats;
	} else {
		return array($formats);
	}
}

/**
 * Override the default entity icon for video
 *
 * @return string Relative URL
 */
function video_icon_url_override($hook, $type, $returnvalue, $params) {
	$video = $params['entity'];
	$size = $params['size'];

	if (!elgg_instanceof($video, 'object', 'video')) {
		return $returnvalue;
	}

	$icontime = $video->icontime;

	if ($icontime) {
		return "videothumb/$video->guid/$size/$icontime.jpg";
	}

	// TODO Add default images
	//return "mod/video/graphics/default{$size}.gif";
}

/**
 * Handle video thumbnails.
 *
 * @param array $page
 * @return void
 */
function video_icon_handler($page) {
	if (isset($page[0])) {
		set_input('video_guid', $page[0]);
	}
	if (isset($page[1])) {
		set_input('size', $page[1]);
	}

	// Include the standard profile index
	$plugin_dir = elgg_get_plugins_path();
	include("$plugin_dir/video/videothumb.php");
	return true;
}

/**
 * Add links/info to entity menu
 */
function video_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'video') {
		return $return;
	}

	$conversion_status = $entity->conversion_done;

	if ($conversion_status) {
		// video duration
		$options = array(
			'name' => 'length',
			'text' => $entity->duration,
			'href' => false,
			'priority' => 200,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	// admin links
	if (elgg_is_admin_logged_in()) {
		$options = array(
			'name' => 'manage',
			'text' => elgg_echo('video:manage'),
			'href' => "admin/video/view?guid={$entity->getGUID()}",
			'priority' => 200,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Add a menu item to an ownerblock
 *
 * @param  string         $hook
 * @param  string         $type
 * @param  ElggMenuItem[] $return Array of ElggMenuItem objects
 * @param  array          $params Menu parameters
 * @return ElggMenuItem[] $return Array of ElggMenuItem objects
 */
function video_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "video/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('video', elgg_echo('video'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->video_enable != "no") {
			$url = "video/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('video', elgg_echo('video:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function video_register_toggle() {
	$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

	if (get_input('list_type', 'list') == 'list') {
		$list_type = "gallery";
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = "list";
		$icon = elgg_view_icon('list');
	}

	if (substr_count($url, '?')) {
		$url .= "&list_type=" . $list_type;
	} else {
		$url .= "?list_type=" . $list_type;
	}

	elgg_register_menu_item('extras', array(
		'name' => 'video_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("video:list:$list_type"),
		'priority' => 1000,
	));
}

/**
 * Return associative array of available video frame sizes.
 *
 * @return array
 */
function video_get_resolution_options() {
	// TODO Get all the supported formats straight from the converter?
	return array(
		'0' => 'same as source',
		'320x240' => '320x240 (qvga)',
		'640x480' => '640x480 (vga)',
		'852x480' => '852x480 (hd480)',
		'1280x720' => '1280x720 (hd720)',
		'1920x1080' => '1920x1080 (hd1080)',
	);
}

/**
 * Get video flavor settings
 *
 * @return array $flavors
 */
function video_get_flavor_settings () {
	$settings = elgg_get_plugin_setting('flavors', 'video');
	$flavors = unserialize($settings);

	if (empty($flavors)) {
		$flavors = array();
	}

	return $flavors;
}

/**
 * Add a new flavor to flavor settings.
 */
function video_add_flavor_setting (array $flavor) {
	$flavors = video_get_flavor_settings();
	$flavors[] = $flavor;
	$settings = serialize($flavors);
	return elgg_set_plugin_setting('flavors', $settings, 'video');
}

/**
 * Add a new flavor to flavor settings.
 */
function video_delete_flavor_setting ($id) {
	$flavors = video_get_flavor_settings();
	unset($flavors[$id]);
	$settings = serialize($flavors);
	return elgg_set_plugin_setting('flavors', $settings, 'video');
}
