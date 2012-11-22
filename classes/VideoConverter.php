<?php

class VideoConverter extends VideoShellAPI {
	
	/**
	 * Covert video with the given parameters
	 */
	public function convert () {
		$result = $this->exec();
		
		if (preg_match('/Output #0/', $result) === 1) {
			return true;
		} else {
			throw new Exception('VideoException:ConversionFailed');
		}
	}
	
	/**
	 * Set path to output file
	 * 
	 * @param string $outputfile Path to the file
	 */
	public function setOutputFile ($outputfile) {
		$this->outputfile = escapeshellarg($outputfile);
	}
	
	/**
	 * Set converter to overwrite existing files
	 */
	public function setOverwrite () {
		$this->global_options[] = 'y';
	}
	
	/**
	 * Set frame size
	 * 
	 * @param string $size The resolution (for example 320x240)
	 */
	public function setFrameSize ($size) {
		$size = escapeshellarg($size);
		$this->addOutfileOption("-s $size");
	}
}
