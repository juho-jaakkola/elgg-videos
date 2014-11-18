<?php

class VideoConverter extends VideoShellAPI {

	public function __construct () {
		$this->setOverwrite();
		parent::__construct();
	}

	/**
	 * Convert video with the given parameters
	 */
	public function convert () {
		$result = $this->execute();

		if (preg_match('/Output #0/', $result) === 1) {
			return true;
		} else {
			throw new Exception($this->getError());
		}
	}

	/**
	 * Set converter to overwrite existing files
	 */
	public function setOverwrite () {
		$this->global_options[] = 'y';
	}

	/**
	 * Set frame size (in format "320x240")
	 *
	 * If undefined or 0 the conversion uses the same resolution as the source
	 *
	 * @param string $size The resolution
	 */
	public function setResolution ($size) {
		if (!empty($size)) {
			$size = escapeshellarg($size);
			$this->addOutfileOption("-s $size");
		}
	}

	/**
	 * Set bitrate in kilobits
	 *
	 * If undefined the conversion uses the same bitrate as the source
	 *
	 * @param string $size The bitrate
	 */
	public function setBitrate ($bitrate) {
		if (!empty($bitrate)) {
			$size = escapeshellarg($bitrate);
			$this->addOutfileOption("-b {$bitrate}k");
		}
	}

	/**
	 * Override VideoShellAPI::setOutputFile
	 *
	 * Set path to output file and add some extra options when needed.
	 *
	 * @param string $outputfile Path to the file
	 */
	public function setOutputFile ($outputfile) {
		// Force some extra options for mp4 format
		$pathinfo = pathinfo($outputfile);
		if ($pathinfo['extension'] == 'mp4') {
			$this->addOutfileOption('-c:v libx264');
			//$this->addOutfileOption('-strict experimental -acodec aac');
		}

		parent::setOutputFile($outputfile);
	}
}
