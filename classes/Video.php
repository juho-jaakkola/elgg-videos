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
	 * Delete a single version of the video
	 */
	public function deleteFormat($format, $resolution = 0) {
		if (empty($resolution)) {
			// Use resolution of original file as defautl
			$resolution = $this->resolution;
		}

		$sources = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'video_source',
			'container_guid' => $this->getGUID(),
			'metadata_name_value_pairs' => array(
				'format' => $format,
				'resolution' => $resolution
			)
		));

		foreach ($sources as $source) {
			if ($source->delete()) {
				return true;
			} else {
				elgg_log("Video removal failed. Remove $filepath manually, please.", 'WARNING');
				return false;
			}
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
	 * Get all VideoSource objects created of this video
	 * 
	 * @return array $sources Array of VideoSource objects  
	 */
	function getSources() {
		$sources = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'video_source',
			'container_guid' => $this->getGUID(),
		));

		return $sources;
	}

	/**
	 * Returns array of relative urls for all video sources
	 * 
	 * @return array $sources
	 */
	public function getSourceUrls($options) {
		$sources = $this->getSources($options);

		$urls = array();
		foreach ($sources as $source) {
			$urls[] = $source->getURL();
		}

		return $sources;
	}
}
