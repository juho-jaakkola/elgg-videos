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
}
