<?php

namespace Paint;

use Paint\Filter\FilterInterface;

class Paint
{
	public $inputPath;
	public $outputPath;

	protected $input;
	protected $output;

	protected $inputWidth;
	protected $inputHeight;
	protected $inputType;

	public function __construct()
	{

	}

	public function __destruct()
	{
		if ($this->output) {
			imagedestroy($this->output);
		}
		if ($this->input) {
			imagedestroy($this->input);
		}
	}

	public static function create()
	{
		return new static();
	}

	public function input($input)
	{
		if (!is_string($input) && !is_file($input)) {
			throw new \InvalidArgumentException("Input file is not a valid ressource");
		}

		list($this->inputWidth, $this->inputHeight, $this->inputType) = getimagesize($input);

		switch ($this->inputType) {
			case IMAGETYPE_JPEG:
				$this->input = imagecreatefromjpeg($input);
				break;
			case IMAGETYPE_PNG:
				$this->input = imagecreatefrompng($input);
				break;
			case IMAGETYPE_GIF:
				$this->input = imagecreatefromgif($input);
				break;
			case IMAGETYPE_WBMP:
				$this->input = imagecreatefromwbmp($input);
				break;
			case IMAGETYPE_XBM:
				$this->input = imagecreatefromxbm($input);
				break;
			default:
				throw new \InvalidArgumentException('Unsuported file type.');
				break;
		}

		$this->inputPath = $input;
	}

	public function output($output)
	{
		if (!is_string($output)) {
			throw new \InvalidArgumentException("Output file is not a valid ressource");
		}

		if (is_file($output)) {
			trigger_error("Output file already exists", E_USER_NOTICE);
		}

		if (!is_writable(dirname($output))) {
			throw new \InvalidArgumentException("Output file is not writable");
		}

		$this->outputPath = $output;
	}

	public function outputFormat($format)
	{

	}
}