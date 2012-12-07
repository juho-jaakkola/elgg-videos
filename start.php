<?php

elgg_register_event_handler('init', 'system', 'videos_init');

function videos_init () {
	elgg_register_library('elgg:videos', elgg_get_plugins_path() . 'videos/lib/videos.php');

	$actionspath = elgg_get_plugins_path() . 'videos/actions/videos/';
	elgg_register_action('videos/upload', $actionspath . 'upload.php');
	elgg_register_action('video/delete', $actionspath . 'delete.php');
	elgg_register_action('videos/settings/save', $actionspath . 'settings/save.php', 'admin');
	elgg_register_action('videos/convert', $actionspath . 'convert.php', 'admin');
	elgg_register_action('videos/delete_format', $actionspath . 'delete_format.php', 'admin');

	// add to the main css
	elgg_extend_view('css/elgg', 'videos/css');

	elgg_register_page_handler('videos', 'videos_page_handler');

	// Site navigation
	$item = new ElggMenuItem('videos', elgg_echo('videos'), 'videos/all');
	elgg_register_menu_item('site', $item);

	elgg_register_entity_url_handler('object', 'video', 'videos_url_override');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'videos_icon_url_override');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'videos_entity_menu_setup');

	// Register cron hook
	$period = elgg_get_plugin_setting('period', 'videos');
	elgg_register_plugin_hook_handler('cron', $period, 'videos_conversion_cron');

	// Register an icon handler for videos
	elgg_register_page_handler('videothumb', 'videos_icon_handler');

	elgg_register_admin_menu_item('administer', 'convert',  'videos');
}

function videos_page_handler ($page) {
	elgg_load_library('elgg:videos');

	elgg_push_breadcrumb(elgg_echo('videos'), 'videos/all');

	switch ($page[0]) {
		case 'view':
			$params = videos_get_page_contents_view($page[1]);
			break;
		case 'owner':
			videos_register_toggle();
			$params = videos_get_page_contents_owner();
			break;
		case 'add':
			$params = videos_get_page_contents_upload();
			break;
		case 'all':
		default:
			videos_register_toggle();
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

/**
 * Trigger the video conversion
 */
function videos_conversion_cron($hook, $entity_type, $returnvalue, $params) {
	$videos = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'video',
		'limit' => 10,
		'metadata_name_value_pairs' => array(
			'name' => 'conversion_done',
			'value' => 0,
		)
	));

	$video_count = count($videos);

	$formats = videos_get_formats();
	$framesize = elgg_get_plugin_setting('framesize', 'videos');

	foreach ($videos as $video) {
		$conversion_errors = array();
		$thumbnail_errors = array();
		$converted_formats = $video->getConvertedFormats();

		foreach ($formats as $format) {
			// Do not convert same format multiple times
			if (in_array($format, $converted_formats)) {
				continue;
			}

			$filename = $video->getFilenameWithoutExtension();
			$dir = $video->getFileDirectory();
			$output_file = "$dir/$filename.$format";

			try {
				$converter = new VideoConverter();
				$converter->setInputFile($video->getFilenameOnFilestore());
				$converter->setOutputFile($output_file);
				$converter->setOverwrite();
				$converter->setFrameSize($framesize);
				$result = $converter->convert();

				$converted_formats[] = $format;
				$video->setConvertedFormats($converted_formats);

				echo "<p>Successfully created video file $filename.$format</p>";
			} catch (exception $e) {
				// Print simple error to screen
				echo "<p>Failed to create video file $filename.$format</p>";

				// Print detailed error to error log
				$message = elgg_echo('VideoException:ConversionFailed',array(
					$e->getMessage(),
					$converter->getCommand()
				));
				error_log($message);

				$format_errors[] = $format;
			}

			$icon_sizes = elgg_get_config('icon_sizes');

			// Use default thumbnail as master
			$imagename = "{$video->getGUID()}master.jpg";
			$imagepath = "$dir/$imagename";

			try {
				$thumbnailer = new VideoThumbnailer();
				$thumbnailer->setInputFile($video->getFilenameOnFilestore());
				$thumbnailer->setOutputFile($imagepath);
				$thumbnailer->setPosition(1);
				$result = $thumbnailer->execute();

				echo "<p>Successfully created thumbnail $imagename</p>";
			} catch (exception $e) {
				echo "<p>Failed to create thumbnail $imagename</p>";
				error_log($e->getMessage());

				$thumbnail_errors[] = $imagename;
			}

			// get the images and save their file handlers into an array
			// so we can do clean up if one fails.
			$files = array();

			// Create the thumbnails
			foreach ($icon_sizes as $name => $size_info) {
				// We have already created master image
				if ($name == 'master') {
					continue;
				}

				$resized = get_resized_image_from_existing_file($imagepath, $size_info['w'], $size_info['h'], true);

				if (!$resized) {
					echo "<p>Failed to create thumbnail $imagename</p>";
					error_log($e->getMessage());
					$thumbnail_errors[] = $imagename;

					// TODO Should we revert the whole thumbnailing process if one image fails?
					continue;
				}

				$file = new ElggFile();
				$file->owner_guid = $video->owner_guid;
				$file->container_guid = $video->getGUID();
				$file->setFilename("video/{$video->getGUID()}{$name}.jpg");
				$file->open('write');
				$result = $file->write($resized);
				$file->close();

				if ($result) {
					echo "<p>Successfully created thumbnail {$file->getFilename()}</p>";
				}

				$files[] = $file;
			}
		}

		if (!empty($conversion_errors)) {
			$conversion_errors = implode(', ', $conversion_errors);

			$error_string = elgg_echo('videos:admin:conversion_error', array($filename, $conversion_errors));
			elgg_add_admin_notice($error_string);
		}

		if (empty($thumbnail_errors)) {
			$video->icontime = time();
		} else {
			$thumbnail_errors = implode(', ', $thumbnail_errors);

			$error_string = elgg_echo('videos:admin:thumbnail_error', array($thumbnail_errors, $filename));
			elgg_add_admin_notice($error_string);
		}

		// Mark conversion done if all formats are found
		$unconverted = array_diff($formats, $converted_formats);
		if (empty($unconverted)) {
			$video->conversion_done = true;
		}
	}

	return $returnvalue;
}

/**
 * Get video formats configured in plugin settings
 * 
 * @return null|array
 */
function videos_get_formats() {
	$plugin = elgg_get_plugin_from_id('videos');
	$formats = $plugin->getMetadata('formats');

	if (is_array($formats)) {
		return $formats;
	} else {
		return array($formats);
	}
}

/**
 * Override the default entity icon for videos
 *
 * @return string Relative URL
 */
function videos_icon_url_override($hook, $type, $returnvalue, $params) {
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
	//return "mod/videos/graphics/default{$size}.gif";
}

/**
 * Handle video thumbnails.
 *
 * @param array $page
 * @return void
 */
function videos_icon_handler($page) {
	if (isset($page[0])) {
		set_input('video_guid', $page[0]);
	}
	if (isset($page[1])) {
		set_input('size', $page[1]);
	}

	// Include the standard profile index
	$plugin_dir = elgg_get_plugins_path();
	include("$plugin_dir/videos/videothumb.php");
	return true;
}

/**
 * Add links/info to entity menu
 */
function videos_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'video') {
		return $return;
	}

	$conversion_status = $entity->conversion_done;

	// view different items depending on status of video conversion
	if ($conversion_status) {
		// video duration
		$duration = $entity->getDuration();
		$options = array(
			'name' => 'length',
			'text' => $duration,
			'href' => false,
			'priority' => 200,
		);
		$return[] = ElggMenuItem::factory($options);
	} else {
		// note that conversion is not finished
		/*
		$options = array(
			'name' => 'conversion_status',
			'text' => elgg_echo("videos:conversion_pending"),
			'href' => false,
			'priority' => 100,
		);
		$return[] = ElggMenuItem::factory($options);
		*/
	}

	// admin links
	if (elgg_is_admin_logged_in()) {
		$options = array(
			'name' => 'manage',
			'text' => elgg_echo('videos:manage'),
			'href' => "admin/videos/view?guid={$entity->getGUID()}",
			'priority' => 300,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function videos_register_toggle() {
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
		'title' => elgg_echo("videos:list:$list_type"),
		'priority' => 1000,
	));
}

/**
 * Return associative array of available video frame sizes.
 * 
 * @return array
 */
function videos_get_framesize_options() {
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
