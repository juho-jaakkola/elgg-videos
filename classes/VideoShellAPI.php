<?php

class VideoShellAPI {

	protected $inputfile = null;
	protected $outputfile = null;
	protected $result;
	protected $converter;
	protected $global_options = array();
	protected $infile_options = array();
	protected $outfile_options = array();
	protected $format = null;

	public function __construct () {
		$this->converter = 'avconv';
		$acvonv_test = $this->execute();

		// If avconv is not available fall back to ffmpeg
		if (preg_match('/usage: avconv/', $acvonv_test) !== 1) {
			$this->converter = 'ffmpeg';
			$ffmpeg_test = shell_exec('ffmpeg 2>&1');

			// Neither was found
			if (preg_match('/usage: ffmpeg/', $ffmpeg_test !== 1)) {
				throw new Exception('VideoExceoption:ConverterNotFound');
			}
		}
	}

	public function execute () {
		$this->result = shell_exec($this->getCommand());

		// If outputting a file check that it exists
		if ($this->outputfile) {
			if (!file_exists($this->outputfile) || filesize($this->outputfile) == 0) {
				throw new Exception($this->getError());
			}
		}

		return $this->result;
	}

	public function getCommand () {
		$global_options = $this->getGlobalOptionString();
		$outputfile = $this->getOutputFile();
		$infile_options = implode(' ', $this->infile_options);
		$outfile_options = implode(' ', $this->outfile_options);

		$command = "{$this->converter} $global_options $infile_options $this->inputfile $outfile_options $outputfile 2>&1";

		return $command;
	}

	protected function addGlobalOption ($option) {
		$this->global_options[] = $option;
	}

	protected function addInfileOption ($option) {
		$this->infile_options[] = $option;
	}

	protected function addOutfileOption ($option) {
		$this->outfile_options[] = $option;
	}

	protected function getGlobalOptionString () {
		if (empty($this->global_options)) {
			return '';
		} else {
			$options = implode('', $this->global_options);
			return "-$options";
		}
	}

	/**
	 * Set path to input file
	 *
	 * @param string $inputfile Path to the file
	 */
	public function setInputfile ($inputfile) {
		$inputfile = escapeshellarg($inputfile);
		$this->inputfile = "-i $inputfile";
	}

	/**
	 * Set path to output file
	 *
	 * @param string $outputfile Path to the file
	 */
	public function setOutputFile ($outputfile) {
		// The filename may be needed later so don't escape it yet.
		$this->outputfile = $outputfile;
	}

	public function setFormat ($format) {
		$this->format = $format;
		$format = escapeshellarg($format);

		$this->addOutfileOption("-f $format");
	}

	public function getOutputFile() {
		if ($this->outputfile) {
			return escapeshellarg($this->outputfile);
		} else {
			return null;
		}
	}

	/**
	 * Get the error message printed to stderr
	 *
	 * @return string $error_message
	 */
	public function getError() {
		if (!$this->result) {
			return null;
		}

		// Convert the message rows to an array
		$parts = explode("\n", $this->result);

		// Remove the last value which is white space
		array_pop($parts);

		// Possible error message should be the second last value
		$error_message = array_pop($parts);

		return $error_message;
	}
}