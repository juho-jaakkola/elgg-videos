<?php
/**
 * Get information of video represented by VideoSource object
 */
 
class VideoInfo extends VideoShellAPI {
	private $fileinfo;

	public function __construct(Video $video) {
		$this->setInputfile($video->getFilenameOnFilestore());
		parent::__construct();
		$this->fileinfo = $this->execute();
	}

	/**
	 * Get video resolution on format 1920x1080
	 * 
	 * @return string
	 */

	public function getResolution() {
		preg_match('/Video: .*\n/', $this->fileinfo, $matches);
		preg_match('/[0-9]{1,4}x[0-9]{1,4}/', $matches[0], $matches);
		return $matches[0];
	}

	/**
	 * Get video duration in format HH:MM:SS
	 * 
	 * @return string
	 */
	public function getDuration() {
		preg_match('/Duration: [0-9]{2}:[0-9]{2}:[0-9]{2}/', $this->fileinfo, $matches);
		preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $matches[0], $matches);
		return $matches[0];
	}
}