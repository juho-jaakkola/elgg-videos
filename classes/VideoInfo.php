<?php
/**
 * Get information of video represented by Video or VideoSource entity
 */

class VideoInfo extends VideoShellAPI {
	private $fileinfo;

	public function __construct(ElggObject $video) {
		parent::__construct();
		$this->setInputfile($video->getFilenameOnFilestore());
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

	/**
	 * Get video bitrate in kilobits
	 *
	 * @return string
	 */
	public function getBitrate() {
		preg_match('/bitrate: [0-9]+ kb\/s/', $this->fileinfo, $matches);
		preg_match('/[0-9]+/', $matches[0], $matches);
		return $matches[0];
	}
}