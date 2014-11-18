<?php

class VideoThumbnailer extends VideoShellAPI {

	public function __construct () {
		parent::__construct();

		// By default we need only one frame
		$this->addOutfileOption("-vframes 1");

		// Force overwrite
		$this->addGlobalOption('y');

		// Use image2 as default format
		$this->setFormat("image2");
	}

	/**
	 * Set position of frame in seconds or in format HH:MM:SS
	 *
	 * @param string $position
	 */
	public function setPosition ($position) {
		$position = escapeshellarg($position);
		$option = "-ss $position";
		$this->addOutfileOption($option);
	}
}