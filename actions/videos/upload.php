<?php
/**
 * Elgg video uploader/edit action
 *
 * @package ElggVideos
 */

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('video_guid');
$tags = get_input("tags");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('video');

// check if upload failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	register_error(elgg_echo('video:cannotload'));
	forward(REFERER);
}

// check whether this is a new video or an edit
$new_video = true;
if ($guid > 0) {
	$new_video = false;
}

if ($new_video) {
	// must have a video if a new video upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('video:novideo');
		register_error($error);
		forward(REFERER);
	}

	$video = new Video();
	$video->subtype = "video";

	// if no title on new upload, grab videoname
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} else {
	// load original video object
	$video = new Video($guid);
	if (!$video) {
		register_error(elgg_echo('video:cannotload'));
		forward(REFERER);
	}

	// user must be able to edit video
	if (!$video->canEdit()) {
		register_error(elgg_echo('video:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $video->title;
	}
}

$video->title = $title;
$video->description = $desc;
$video->access_id = $access_id;
$video->container_guid = $container_guid;

$tags = explode(",", $tags);
$video->tags = $tags;

// we have a video upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "video/";

	// if previous video, delete it
	if ($new_video == false) {
		$videoname = $video->getFilenameOnFilestore();
		if (file_exists($videoname)) {
			unlink($videoname);
		}

		// use same videoname on the disk - ensures thumbnails are overwritten
		$videostorename = $video->getFilename();
		$videostorename = elgg_substr($videostorename, elgg_strlen($prefix));
	} else {
		$videostorename = elgg_strtolower(time().$_FILES['upload']['name']);
	}

	$video->setFilename($prefix . $videostorename);
	$mime_type = ElggFile::detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

	$video->setMimeType($mime_type);
	$video->originalvideoname = $_FILES['upload']['name'];
	$video->simpletype = file_get_simple_type($mime_type);

	// Open the video to guarantee the directory exists
	$video->open("write");
	$video->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $video->getFilenameOnFilestore());

	$guid = $video->save();

	// if image, we need to create thumbnails (this should be moved into a function)
	/*
	if ($guid && $video->simpletype == "image") {
		$video->icontime = time();
		
		$thumbnail = get_resized_image_from_existing_video($video->getFilenameOnFilestore(), 60, 60, true);
		if ($thumbnail) {
			$thumb = new ElggFile();
			$thumb->setMimeType($_FILES['upload']['type']);

			$thumb->setFilename($prefix."thumb".$videostorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$video->thumbnail = $prefix."thumb".$videostorename;
			unset($thumbnail);
		}

		$thumbsmall = get_resized_image_from_existing_video($video->getFilenameOnFilestore(), 153, 153, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$videostorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$video->smallthumb = $prefix."smallthumb".$videostorename;
			unset($thumbsmall);
		}

		$thumblarge = get_resized_image_from_existing_video($video->getFilenameOnFilestore(), 600, 600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$videostorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$video->largethumb = $prefix."largethumb".$videostorename;
			unset($thumblarge);
		}
	}
	*/
} else {
	// not saving a video but still need to save the entity to push attributes to database
	$video->save();
}

// video saved so clear sticky form
elgg_clear_sticky_form('video');

// handle results differently for new videos and video updates
if ($new_video) {
	if ($guid) {
		// Mark the video as unconverted so conversion script can find it
		$video->conversion_done = false;
		$video->save();

		$message = elgg_echo("video:saved");
		system_message($message);
		add_to_river('river/object/video/create', 'create', elgg_get_logged_in_user_guid(), $video->guid);
	} else {
		// failed to save video object - nothing we can do about this
		$error = elgg_echo("video:uploadfailed");
		register_error($error);
	}

	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		forward("videos/group/$container->guid/all");
	} else {
		forward("videos/owner/$container->username");
	}

} else {
	if ($guid) {
		system_message(elgg_echo("video:saved"));
	} else {
		register_error(elgg_echo("video:uploadfailed"));
	}

	forward($video->getURL());
}	
