<?php
/**
 * Holds location and information of each physical video file
 */

class VideoSource extends ElggFile {
	protected function  initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "video_source";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	public function getUrl() {
		if (empty($this->resolution)) {
			$resolution = '';
		} else {
			$resolution = "&resolution={$this->resolution}";
		}

		/**
		 * Passing both video and video source guid makes it possible to later
		 * implement a video quality selector that selects for example all HD
		 * quality sources from a single video.
		 */
		return "mod/video/video.php?guid={$this->guid}&video_guid={$this->getContainerGUID()}&format={$this->format}{$resolution}";
	}
}