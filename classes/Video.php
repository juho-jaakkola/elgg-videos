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

	public function delete() {
		/*
		$thumbnails = array($this->thumbnail, $this->smallthumb, $this->largethumb);
		foreach ($thumbnails as $thumbnail) {
			if ($thumbnail) {
				$delvideo = new ElggFile();
				$delvideo->owner_guid = $this->owner_guid;
				$delvideo->setFilename($thumbnail);
				$delvideo->delete();
			}
		}
		*/
		
		return parent::delete();
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

	/**
	 * Gets a list of converted formats
	 *
	 * @return array $unserialized Array of converted formats
	 */
	public function getConvertedFormats() {
		$converted_formats = $this->getPrivateSetting('converted_formats');
		if ($converted_formats) {
			$unserialized = unserialize($converted_formats);
			var_dump($unserialized); die;
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
		return $this->getPrivateSetting('converted_formats', serialize($converted_formats));
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
}
