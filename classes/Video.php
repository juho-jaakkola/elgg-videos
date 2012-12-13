<?php

/**
 * Override the ElggFile
 */
class Video extends ElggFile {
	protected function  initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "video";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	/**
	 * Delete all formats of the video
	 */
	public function delete() {
		$formats = $this->getConvertedFormats();

		foreach ($formats as $format) {
			$this->deleteFormat($format);
		}

		return parent::delete();
	}

	/**
	 * Delete a single format of the video
	 */
	public function deleteFormat($format) {
		$filename = $this->getFilenameWithoutExtension();

		$file = new ElggFile();
		$file->owner_guid = $this->getOwnerGUID();
		$file->setFilename("video/{$filename}.{$format}");
		$filepath = $file->getFilenameOnFilestore();

		// These files are not represented by entities so remove manually
		if (file_exists($filepath)) {
			if (unlink($filepath)) {
				$this->removeConvertedFormat($format);
				return true;
			}
		} else {
			elgg_log("Video removal failed. Remove $filepath manually, please.", 'WARNING');
			return true;
		}
	}

	/**
	 * Return the data directory where the file is located
	 *
	 * @return string $path
	 */
	public function getFileDirectory() {
		$filepath = $this->getFilenameOnFilestore();

		$parts = explode("/", $filepath);
		array_pop($parts);
		$path = implode("/", $parts);

		return $path;
	}

	/**
	 * Return the file name without file extension
	 * 
	 * If file store name is "video/1352994519movie.mov"
	 * then the result will be "1352994519movie"
	 *
	 * @return string $path
	 */
	public function getFilenameWithoutExtension () {
		$filestorename = $this->getFilenameOnFilestore();
		$path_parts = pathinfo($filestorename);
		return $path_parts['filename'];
	}

	public function getFilenameOnFilestoreWithoutExtension () {
		$filestorename = $this->getFilenameOnFilestore();
		$path_parts = pathinfo($filestorename);
		return $path_parts['dirname'] . "/" . $path_parts['filename'];
	}

	/**
	 * Gets a list of converted formats
	 *
	 * @return array $unserialized Array of converted formats
	 */
	public function getConvertedFormats() {
		$converted_formats = $this->getPrivateSetting('converted_formats');
		if ($converted_formats) {
			$unserialized = unserialize($converted_formats);
			return $unserialized;
		} else {
			return array();
		}
	}

	/**
	 * Sets a list of converted formats
	 *
	 * @return array Array of converted formats
	 */
	public function setConvertedFormats(array $converted_formats) {
		$converted_formats = array_unique($converted_formats);
		return $this->setPrivateSetting('converted_formats', serialize($converted_formats));
	}

	/**
	 * Add a new converted format.
	 * 
	 * @param string $format
	 * @return boolean
	 */
	public function addConvertedFormat($format) {
		$setting = $this->getPrivateSetting('converted_formats');
		if ($setting) {
			$formats = unserialize($setting);
			$formats[] = $format;
		} else {
			$formats = array($format);
		}

		$formats = array_unique($formats);

		return $this->setPrivateSetting('converted_formats', serialize($formats));
	}

	/**
	 * Remove one of the converted formats
	 * 
	 * @param string $format
	 * @return boolean
	 */
	public function removeConvertedFormat($format) {
		$setting = $this->getPrivateSetting('converted_formats');
		$formats = unserialize($setting);

		foreach ($formats as $key => $value) {
			if ($format === $value) {
				unset($formats[$key]);
			}
		}

		if (empty($formats)) {
			$this->removePrivateSetting('converted_formats');
		} else {
			return $this->setPrivateSetting('converted_formats', serialize($formats));
		}
	}

	/**
	 * Gets the video duration in format HH:MM:SS
	 * 
	 * @return string
	 */
	public function getDuration() {
		if ($this->duration) {
			return $this->duration;
		}

		$file = escapeshellarg($this->getFilenameOnFilestore());

		$command = "avconv -i $file 2>&1";

		$fileinfo = shell_exec($command);

		preg_match('/Duration: [0-9]{2}:[0-9]{2}:[0-9]{2}/', $fileinfo, $matches);
		preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $matches[0], $matches);

		$duration = $matches[0];

		$this->duration = $duration;
		return $duration;
	}

	/**
	 * Returns array of relative urls for all video sources
	 * 
	 * @return array $sources
	 */
	public function getSources() {
		$formats = $this->getConvertedFormats();

		$sources = array();
		foreach ($formats as $format) {
			$sources[$format] = "mod/video/video.php?video_guid={$this->getGUID()}&format=$format";
		}

		return $sources;
	}
}
