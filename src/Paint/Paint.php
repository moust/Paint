<?php

namespace Paint;

use Paint\Exception\CapabilityException;
use Paint\Filter\FilterInterface;
use Paint\Format\FormatInterface;
use Paint\Utils;

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

	protected $outputWidth = 0;
	protected $outputHeight = 0;

	protected $colorFill;

	protected $resizeMode;

	protected $filters = array();


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
		// if is not a string, not an existing file or not a file ressource
		if (!is_string($input) || !file_exists($input) || !is_file($input)) {
			throw new \InvalidArgumentException('Input file is not a valid ressource.');
		}

		$this->inputPath = $input;

		list($this->inputWidth, $this->inputHeight, $this->inputType) = getimagesize($input);

		switch ($this->inputType) {
			case IMAGETYPE_GIF:
				if (!function_exists('imagecreatefromgif')) {
					throw new CapabilityException('GIF is not supported.');
				}
				$this->input = imagecreatefromgif($input);
				break;
			case IMAGETYPE_JPEG:
				if (!function_exists('imagecreatefromjpeg')) {
					throw new CapabilityException('JPEG is not supported.');
				}
				$this->input = imagecreatefromjpeg($input);
				break;
			case IMAGETYPE_PNG:
				if (!function_exists('imagecreatefrompng')) {
					throw new CapabilityException('PNG is not supported.');
				}
				$this->input = imagecreatefrompng($input);
				break;
			case IMAGETYPE_WBMP:
				if (!function_exists('imagecreatefromwbmp')) {
					throw new CapabilityException('WBMP is not supported.');
				}
				$this->input = imagecreatefromwbmp($input);
				break;
			// Not supported yet in PHP 5.5. WebP is supported since in PHP 5.5 (https://bugs.php.net/bug.php?id=65038)
			// case IMAGETYPE_WEBP:
			// 	if (!function_exists('imagecreatefromwebp')) {
			// 		throw new CapabilityException('WebP is not supported.');
			// 	}
			// 	$this->input = imagecreatefromwebp($input);
			// 	break;
			case IMAGETYPE_XBM:
				if (!function_exists('imagecreatefromxbm')) {
					throw new CapabilityException('XBM is not supported.');
				}
				$this->input = imagecreatefromxbm($input);
				break;
			// case IMAGETYPE_XPM:
			// 	if (!function_exists('imagecreatefromxpm')) {
			// 		throw new CapabilityException('XPM is not supported.');
			// 	}
			// 	$this->input = imagecreatefromxpm($input);
			// 	break;
			default:
				throw new CapabilityException('Unsupported input file type.');
				break;
		}

		$this->applyExifTransformations($this->input);
	}


	public function output($output)
	{
		if (!is_string($output)) {
			throw new \InvalidArgumentException('Output file is not a valid ressource.');
		}

		// if (is_file($output)) {
		// 	trigger_error('Output file already exists.', E_USER_NOTICE);
		// }

		if (!is_writable(dirname($output))) {
			throw new \InvalidArgumentException('Output file is not writable.');
		}

		$this->outputPath = $output;
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


	public function colorFill(Color $color)
	{
		$this->colorFill = $color;
	}


	public function generate(FormatInterface $format)
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
		if (!is_null($this->colorFill)) {
			imagefill($this->output, 0, 0, $this->colorFill->getColor());
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
		$format->generate($this->output, $this->outputPath);
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

		// taller
		if ($height > $outputHeight) {
			$width = ($outputHeight / $height) * $width;
			$height = $outputHeight;
		}

		// wider
		if ($width > $outputWidth) {
			$height = ($outputWidth / $width) * $height;
			$width = $outputWidth;
		}

		$output = imagecreatetruecolor($width, $height);

		imagecopyresampled($output, $input, 0, 0, 0, 0, $width, $height, $inputWidth, $inputHeight);
	}


	protected function mirror(&$image)
	{
		$width = imagesx($image);
		$height = imagesy($image);

		$src_x = $width -1;
		$src_y = 0;
		$src_width = -$width;
		$src_height = $heightt;

		$temp = imagecreatetruecolor($width, $height);

		$image = imagecopyresampled($temp, $image, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height);

		imagedestroy($temp);
	}


	public function addFilter(FilterInterface $filter)
	{
		$this->filters[] = $filter;
	}


	protected function applyExifTransformations(&$image)
	{
		if (!function_exists('exif_read_data')) {
			return;
		}

		$exif = exif_read_data($this->inputPath);

		if ($exif && isset($exif['Orientation'])) {
			switch ($exif['Orientation']) {
				case 2:
				$this->mirror();
				break;
			case 3:
				$image = imagerotate($image, 180, 0);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			case 4:
				$image = imagerotate($image, 180, 0);
				$this->mirror($image);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			case 5:
				$image = imagerotate($image, 270, 0);
				$this->mirror($image);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			case 6:
				$image = imagerotate($image, 270, 0);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			case 7:
				$image = imagerotate($image, 90, 0);
				$this->mirror($image);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			case 8:
				$image = imagerotate($image, 90, 0);
				$width = $this->inputWidth;
				$this->inputWidth = $this->inputHeight;
				$this->inputHeight = $width;
				break;
			}
		}
	}
}