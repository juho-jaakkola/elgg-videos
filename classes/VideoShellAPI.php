<?php

class VideoShellAPI {
	
	protected $inputfile = '';
	protected $outputfile = '';
	protected $converter;
	protected $global_options = array();
	protected $infile_options = array();
	
	public function __construct () {
		$acvonv_test = shell_exec('avconv 2>&1');
		preg_match('/usage: avconv/', $acvonv_test, $matches);

		if ($matches[0]) {
			$this->converter = 'avconv';
		} else {
			$ffmpeg_test = shell_exec('ffmpeg 2>&1');
			preg_match('/usage: ffmpeg/', $ffmpeg_test, $matches);
			if ($matches[0]) {
				$this->converter = 'ffmpeg';
			} else {
				throw new Exception('VideoExceoption:ConverterNotFound');
			}
		}
	}

	public function exec () {
		$global_options = $this->getGlobalOptionString();
		$infile_options = implode(' ', $this->infile_options);
		$outfile_options = implode(' ', $this->outfile_options);

		$command = "{$this->converter} $global_options $infile_options -i $this->inputfile $outfile_options $this->outputfile 2>&1";
		return shell_exec($command);
	}
	
	public function addGlobalOption ($option) {
		$this->global_options[] = $option;
	}
	
	public function addInfileOption ($option) {
		$this->infile_options[] = $option;
	}
	
	public function addOutfileOption ($option) {
		$this->outfile_options[] = $option;
	}
	
	public function getGlobalOptionString () {
		if (empty($this->global_options)) {
			return '';
		} else {
			$options = implode('', $this->global_options);
			
			return "-$options";
		}
	}

	public function setInputfile ($inputfile) {
		$this->inputfile = escapeshellarg($inputfile);
	}
}