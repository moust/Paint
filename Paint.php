<?php

namespace Paint;

use Paint\Filter\FilterInterface;

class Paint
{
	public static $AVAILABLE_FORMATS = array(
		IMAGETYPE_JPEG,
		IMAGETYPE_PNG,
		IMAGETYPE_GIF,
		IMAGETYPE_WBMP,
		IMAGETYPE_XBM
	);

	public $inputPath;
	public $outputPath;

	protected $input;
	protected $output;

	protected $inputWidth;
	protected $inputHeight;
	protected $inputType;

	protected $outputFormat = IMAGETYPE_JPEG;

	protected $outputWidth = 0;
	protected $outputHeight = 0;

	protected $outputQuality = 100;

	protected $foreground = array(0, 0, 0);

	protected $pngFilters;


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


	/**
	 * undocumented class variable
	 *
	 * @param string octet du format d'image. Formats supportés : IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM
	 **/
	public static function isSupportedFormats($format)
	{
		return true == (imagetypes() & $format);
	}


	public static function validColor($color)
	{
		return min(abs((int) $color), 255);
	}


	public static function create()
	{
		return new static();
	}


	public function input($input)
	{
		// if is not a string, not an existing file or not a file ressource
		if (!is_string($input) || !file_exists($input) || !is_file($input)) {
			throw new \InvalidArgumentException('Input file is not a valid ressource.');
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
			case IMAGETYPE_XPM:
				$this->input = imagecreatefromxpm($input);
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
			throw new \InvalidArgumentException('Output file is not a valid ressource.');
		}

		if (is_file($output)) {
			trigger_error('Output file already exists.', E_USER_NOTICE);
		}

		if (!is_writable(dirname($output))) {
			throw new \InvalidArgumentException('Output file is not writable.');
		}

		$this->outputPath = $output;
	}


	public function outputFormat($format)
	{
		if (false === self::isSupportedFormats($format)) {
			throw new \InvalidArgumentException('This output format is not supported.');
		}

		$this->outputFormat = $format;
	}


	public function setOutputWidth($width)
	{
		$this->outputWidth = abs((int) $width);
	}


	public function setOutputHeight($height)
	{
		$this->outputHeight = abs((int) $height);
	}


	public function setOutputSize($width, $height)
	{
		$this->setOutputWidth($width);
		$this->setOutputHeight($height);
	}


	public function setQuality($quality = 100)
	{
		$this->outputQuality = abs((int) $quality);
	}


	public function setForeground($red, $green, $blue)
	{
		$this->foreground = array(
			self::validColor($red),
			self::validColor($green),
			self::validColor($blue)
		);
	}


	public function generate()
	{
		if (0 >= $this->outputWidth || 0 >= $this->outputHeight) {
			throw new \LengthException('Invalid image dimensions');
		}

		$this->output = imagecreatetruecolor($this->outputWidth, $this->outputHeight);

		switch ($this->outputFormat) {
			case IMG_JPEG:
				imagejpeg($this->output, $this->outputPath, $this->outputQuality);
				break;
			case IMG_PNG:
				imagepng($this->output, $this->outputPath, $this->outputQuality, $this->pngFilters);
				break;
			case IMG_GIF:
				imagegif($this->output, $this->outputPath);
				break;
			case IMG_WBMP:
				$foreground = imagecolorallocate($this->output, $this->foreground[0], $this->foreground[1], $this->foreground[2]);
				imagewbmp($this->output, $this->outputPath, $foreground);
				break;
			case IMG_XPM:
				$foreground = imagecolorallocate($this->output, $this->foreground[0], $this->foreground[1], $this->foreground[2]);
				imagexbm($this->output, $this->outputPath, $foreground);
				break;
			default:
				throw new \InvalidArgumentException('Unknow output format.');
				break;
		}
	}
}