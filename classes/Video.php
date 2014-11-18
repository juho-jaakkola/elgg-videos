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
	 * Override ElggFile::delete()
	 *
	 * After deleting the file delete also the directory.
	 *
	 * @return bool
	 */
	public function delete() {
		$fs = $this->getFilestore();
		$dir = $this->getFileDirectory();

		// Delete the file on disc
		if ($fs->delete($this)) {
			// Delete the ElggFile entity
			if (parent::delete()) {
				// Delete the directory
				if (is_dir($dir)) {
				    if (rmdir($dir)) {
				    	return true;
				    } else {
						elgg_add_admin_notice('video_dir_delete_failed', elgg_echo('video:dir_delete_failed', $dir));
					}
				}
			}
		}

		return false;
	}

	/**
	 * Delete a single version of the video
	 */
	public function deleteFormat($format, $resolution = 0) {
		if (empty($resolution)) {
			// Use resolution of the original file as default
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
	 * Get all VideoSource objects created of this video
	 *
	 * @param  array $options Options for the query
	 * @return array $sources Array of VideoSource objects
	 */
	function getSources($options = array()) {
		$defaults = array(
			'type' => 'object',
			'subtype' => 'video_source',
			'container_guid' => $this->getGUID(),
		);

		$options = array_merge($defaults, $options);

		$sources = elgg_get_entities_from_metadata($options);

		return $sources;
	}

	/**
	 * Returns array of relative urls for all video sources
	 *
	 * @return array $sources
	 */
	public function getSourceUrls($options = array()) {
		$sources = $this->getSources($options);

		$urls = array();
		foreach ($sources as $source) {
			$urls[] = $source->getURL();
		}

		return $sources;
	}

	/**
	 * Create different video sources based on plugin configuration
	 */
	public function setSources () {
		$flavors = video_get_flavor_settings();

		foreach ($flavors as $flavor) {
			$source = new VideoSource();
			$source->container_guid = $this->getGUID();
			$source->owner_guid = $this->getOwnerGUID();
			$source->access_id = $this->access_id;
			$source->conversion_done = false;

			if (empty($flavor['resolution'])) {
				$source->resolution = null;

				// Use resolution of the parent in the filename
				$resolution = $this->resolution;
			} else {
				$source->resolution = $flavor['resolution'];
				$resolution = $source->resolution;
			}

			if (empty($flavor['bitrate'])) {
				$source->bitrate = null;
			} else {
				$source->bitrate = $flavor['bitrate'];
			}

			$source->format = $flavor['format'];

			$basename = $this->getFilenameWithoutExtension();
			$filename = "video/{$this->getGUID()}/{$basename}_{$resolution}.{$source->format}";

			$source->setFilename($filename);
			$source->setMimeType("video/$source->format");
			$source->save();
		}
	}
}
