<?php

namespace Paint;

use Paint\Filter\FilterInterface;

class Paint
{
	const RESIZE_FIT = 1;
	const RESIZE_CROP = 2;

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

	protected $colorFill;

	protected $pngFilters;

	protected $resizeMode;

	protected $filters = array();


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
	 * check if $format is a valid image format
	 *
	 * @param string octet du format d'image. Formats supportés : IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM
	 **/
	public static function isSupportedFormats($format)
	{
		return true == (imagetypes() & $format);
	}


	/**
	 * validate an RGB color
	 *
	 * @param int $color
	 **/
	public static function validColor($color)
	{
		// convert hexa to decimal
		if (is_string($color)) {
			$color = hexdec($color);
		}

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


	public function setOutputSize($width, $height, $mode = self::RESIZE_FIT)
	{
		$this->setOutputWidth($width);
		$this->setOutputHeight($height);
		$this->setResizeMode($mode);
	}


	public function setResizeMode($mode)
	{
		if (self::RESIZE_FIT !== $mode && self::RESIZE_CROP !== $mode)
		{
			throw new \InvalidArgumentException('This resize mode is not supported.');
		}

		$this->resizeMode = $mode;
	}


	public function setQuality($quality = 100)
	{
		$this->outputQuality = min(100, abs((int) $quality));
	}


	public function setForeground($red, $green, $blue)
	{
		$this->foreground = array(
			self::validColor($red),
			self::validColor($green),
			self::validColor($blue)
		);
	}


	public function colorFill($red, $green, $blue)
	{
		$this->colorFill = array(
			self::validColor($red),
			self::validColor($green),
			self::validColor($blue)
		);
	}


	public function generate()
	{
		// default output size egual input size
		if (empty($this->outputWidth)) {
			$this->setOutputWidth($this->inputWidth);
		}
		if (empty($this->outputHeight)) {
			$this->setOutputHeight($this->inputHeight);
		}

		// valid output size
		if (0 >= $this->outputWidth || 0 >= $this->outputHeight) {
			throw new \LengthException('Invalid image dimensions');
		}

		// create output image
		$this->output = imagecreatetruecolor($this->outputWidth, $this->outputHeight);

		// background color fill
		if (!empty($this->colorFill)) {
			imagefill($this->output, 0, 0, imagecolorallocate($this->output, $this->colorFill[0], $this->colorFill[1], $this->colorFill[2]));
		}

		// copy input
		if ($this->input) {
			switch ($this->resizeMode) {
				case self::RESIZE_FIT:
					$this->resize($this->output, $this->input, $this->outputWidth, $this->outputHeight, $this->inputWidth, $this->inputHeight);
					break;
				case self::RESIZE_CROP:
					$this->crop($this->output, $this->input, $this->outputWidth, $this->outputHeight, $this->inputWidth, $this->inputHeight);
					break;
				default:
					imagecopyresampled($this->output, $this->input, 0, 0, 0, 0, $this->outputWidth, $this->outputHeight, $this->inputWidth, $this->inputHeight);
			}
		}

		// apply filters
		foreach ($this->filters as $filter)
		{
			$filter->apply($this->output);
		}

		// generate output fill in the right format
		switch ($this->outputFormat) {
			case IMG_JPEG:
				imagejpeg($this->output, $this->outputPath, $this->outputQuality);
				break;
			case IMG_PNG:
				$quality = (1 - ($this->outputQuality / 100)) * 9; // PNG Compression level: from 0 (no compression) to 9.
				imagepng($this->output, $this->outputPath, $quality, $this->pngFilters);
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


	protected function crop(&$output, &$input, $outputWidth, $outputHeight, $inputWidth, $inputHeight)
	{
		$input_aspect = $inputWidth / $inputHeight;
		$output_aspect = $outputWidth / $outputHeight;

		if ( $input_aspect >= $output_aspect )
		{
			// If image is wider than thumbnail (in aspect ratio sense)
			$new_height = $outputHeight;
			$new_width = $inputWidth / ($inputHeight / $outputHeight);
		}
		else
		{
			// If the thumbnail is wider than the image
			$new_width = $outputWidth;
			$new_height = $inputHeight / ($inputWidth / $outputWidth);
		}

		// Resize and crop
		imagecopyresampled(
			$output, $input,
			0 - ($new_width - $outputWidth) / 2, // Center the image horizontally
			0 - ($new_height - $outputHeight) / 2, // Center the image vertically
			0, 0,
			$new_width, $new_height,
			$inputWidth, $inputHeight
		);
	}


	protected function resize(&$output, &$input, $outputWidth, $outputHeight, $inputWidth, $inputHeight)
	{
		$width = $inputWidth;
		$height = $inputHeight;

		// bigger
		if ($height < $outputHeight) {
			$width = ($outputHeight / $height) * $width;
			$height = $outputHeight;
		}
		if ($width < $outputWidth) {
			$height = ($outputWidth / $width) * $height;
			$width = $outputWidth;
		}

		# taller
		if ($height > $outputHeight) {
			$width = ($outputHeight / $height) * $width;
			$height = $outputHeight;
		}

		# wider
		if ($width > $outputWidth) {
			$height = ($outputWidth / $width) * $height;
			$width = $outputWidth;
		}

		$output = imagecreatetruecolor($width, $height);

		imagecopyresampled($output, $input, 0, 0, 0, 0, $width, $height, $inputWidth, $inputHeight);
	}


	public function addFilter(FilterInterface $filter)
	{
		$this->filters[] = $filter;
	}
}