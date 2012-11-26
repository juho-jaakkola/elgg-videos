<?php

class VideoConverter extends VideoShellAPI {
	
	/**
	 * Covert video with the given parameters
	 */
	public function convert () {
		$result = $this->execute();
		
		if (preg_match('/Output #0/', $result) === 1) {
			return true;
		} else {
			throw new Exception('VideoException:ConversionFailed');
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
	 * If undefined or 0 the conversion uses the same frame size as the source
	 * 
	 * @param string $size The resolution
	 */
	public function setFrameSize ($size) {
		if (!empty($size)) {
			$size = escapeshellarg($size);
			$this->addOutfileOption("-s $size");
		}
	}
}
