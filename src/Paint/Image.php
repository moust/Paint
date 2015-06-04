<?php

namespace Paint;

use Paint\Exception\CapabilityException;
use Paint\Filter\FilterInterface;
use Paint\Format\FormatInterface;
use Paint\Transformation\TransformationInterface;
use Paint\Transformation;

/**
 * Image
 *
 * @author Quentin Aupetit
 **/
class Image
{
	const RESIZE_FIT = 1;
	const RESIZE_CROP = 2;

	protected $image;
	protected $filename;
	protected $width;
	protected $height;
	protected $inputType;
	protected $resizeMode;
	protected $colorFill;
	protected $transformations = array();
	protected $filters = array();

	/**
	 * Image instanciation
	 *
	 * @param string Path to the image.
	 * @return void
	 **/
	public function __construct($filename)
	{
		$this->filename = $filename;

		$this->create($filename);

		$this->applyExifTransformations();
	}

	/**
	 * Create image ressource from filename.
	 *
	 * @param string Path to the image.
	 * @return void
	 **/
	protected function create($filename)
	{
		$this->filename = $filename;

		// if is not a string, not an existing file or not a file ressource
		if (!is_string($this->filename) || !file_exists($this->filename) || !is_file($this->filename)) {
			throw new \InvalidArgumentException('Filename is not a valid ressource.');
		}

		switch (exif_imagetype($this->filename)) {
			case IMAGETYPE_GIF:
				if (!function_exists('imagecreatefromgif')) {
					throw new CapabilityException('GIF is not supported.');
				}
				$this->image = imagecreatefromgif($this->filename);
				break;
			case IMAGETYPE_JPEG:
				if (!function_exists('imagecreatefromjpeg')) {
					throw new CapabilityException('JPEG is not supported.');
				}
				$this->image = imagecreatefromjpeg($this->filename);
				break;
			case IMAGETYPE_PNG:
				if (!function_exists('imagecreatefrompng')) {
					throw new CapabilityException('PNG is not supported.');
				}
				$this->image = imagecreatefrompng($this->filename);
				break;
			case IMAGETYPE_WBMP:
				if (!function_exists('imagecreatefromwbmp')) {
					throw new CapabilityException('WBMP is not supported.');
				}
				$this->image = imagecreatefromwbmp($this->filename);
				break;
			// Not supported yet in PHP 5.5. WebP is supported since in PHP 5.5 (https://bugs.php.net/bug.php?id=65038)
			case defined('IMAGETYPE_WEBP') && IMAGETYPE_WEBP:
				if (!function_exists('imagecreatefromwebp')) {
					throw new CapabilityException('WebP is not supported.');
				}
				$this->image = imagecreatefromwebp($this->filename);
				break;
			case IMAGETYPE_XBM:
				if (!function_exists('imagecreatefromxbm')) {
					throw new CapabilityException('XBM is not supported.');
				}
				$this->image = imagecreatefromxbm($this->filename);
				break;
			case defined('IMAGETYPE_WEBP') && IMAGETYPE_XPM:
				if (!function_exists('imagecreatefromxpm')) {
					throw new CapabilityException('XPM is not supported.');
				}
				$this->image = imagecreatefromxpm($this->filename);
				break;
			default:
				throw new CapabilityException('Unsupported input file type.');
				break;
		}

		$this->setWidth(imagesx($this->image));
		$this->setHeight(imagesy($this->image));
	}

	/**
	 * Sets image width.
	 *
	 * @param int Image width.
	 * @return void
	 */
	public function setWidth($width)
	{
		$this->width = (int) $width;
	}

	/**
	 * Sets image height.
	 *
	 * @param int Image height.
	 * @return void
	 */
	public function setHeight($height)
	{
		$this->height = (int) $height;
	}
	
	/**
	 * Add a transformation.
	 *
	 * @param TransformationInterface Image transformation.
	 * @return void
	 **/
	public function addTransformation(TransformationInterface $transformation)
	{
		$this->transformations[] = $transformation;
	}
	
	/**
	 * Add a filter.
	 *
	 * @param FilterInterface Image filter.
	 * @return void
	 **/
	public function addFilter(FilterInterface $filter)
	{
		$this->filters[] = $filter;
	}

	/**
	 * Generate image
	 *
	 * @param FormatInterface Image format.
	 * @param string The path to save the file to.
	 * @return void
	 **/
	public function generate(FormatInterface $format, $filename = null)
	{
		if ($filename) {
			$this->isValidOutput($filename);
		}

		// create output image
		$output = imagecreatetruecolor($this->width, $this->height);

		// background color fill
		if (!is_null($this->colorFill)) {
			imagefill($output, 0, 0, $this->colorFill->getColor());
		}

		// copy image
		imagecopyresampled($output, $this->image, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);

		// apply transformations
		foreach ($this->transformations as $transformation)
		{
			$transformation->apply($output);
		}

		// apply filters
		foreach ($this->filters as $filter)
		{
			$filter->apply($output);
		}

		// generate output fill in the right format
		$format->generate($output, $filename);
	}

	/**
	 * Validate output path.
	 *
	 * @param string The path to save the file to.
	 * @return boolean
	 **/
	protected function isValidOutput($filename)
	{
		// check if output is a valid ressource
        if (!is_string($filename)) {
            throw new \InvalidArgumentException('Output is not a valid ressource.');
        }

        // create output directory if not exists
        if (!file_exists(dirname($filename)) && !mkdir(dirname($filename))) {
            throw new \RuntimeException('Can\'t create output directory.');
        }

		// check if output dirname is writable, if it is a file and if it is also writable
		if (!is_writable(dirname($filename)) || (file_exists($filename) && !is_writable($filename))) {
			throw new \InvalidArgumentException('Output is not writable.');
		}

		return true;
	}

	/**
	 * Sets image output width, height and croping mode.
	 *
	 * @param int Output width.
	 * @param int Output height.
	 * @param int Resize mode. Can be RESIZE_FIT or RESIZE_CROP.
	 * @return void
	 **/
	public function setOutputSize($width, $height, $mode = self::RESIZE_FIT)
	{
		switch ($mode) {
			case self::RESIZE_CROP:
				$this->addTransformation(new Transformation\Crop($width, $height));
				break;
			case self::RESIZE_FIT:
			default:
				$this->addTransformation(new Transformation\Resize($width, $height));
				break;
		}
	}

	/**
	 * Sets image output width
	 *
	 * @param int Output width.
	 * @return void
	 **/
	public function setOutputWidth($width)
	{
		$this->outputWidth = abs((int) $width);
	}

	/**
	 * Sets image output height
	 *
	 * @param int Output height.
	 * @return void
	 **/
	public function setOutputHeight($height)
	{
		$this->outputHeight = abs((int) $height);
	}

	/**
	 * Sets image fill color.
	 *
	 * @param Color Fill color.
	 * @return void
	 **/
	public function colorFill(Color $color)
	{
		$this->colorFill = $color;
	}

	/**
	 * Applies image transformations according to EXIF informations for JPEG images.
	 *
	 * @return void
	 **/
	protected function applyExifTransformations()
	{
		if (!function_exists('exif_read_data')) {
			return;
		}

		$exif = exif_read_data($this->filename);

        // nota bene : EXIF is only available on JPEG images

		if ($exif && isset($exif['Orientation'])) {
			switch ($exif['Orientation']) {
				case 2:
					$this->addTransformation(new Transformation\Mirror());
					break;
				case 3:
					$this->addTransformation(new Transformation\Rotate(180));
					break;
				case 4:
					$this->addTransformation(new Transformation\Rotate(180));
					$this->addTransformation(new Transformation\Mirror());
					break;
				case 5:
					$this->addTransformation(new Transformation\Rotate(270));
					$this->addTransformation(new Transformation\Mirror());
					break;
				case 6:
					$this->addTransformation(new Transformation\Rotate(270));
					break;
				case 7:
					$this->addTransformation(new Transformation\Rotate(90));
					$this->addTransformation(new Transformation\Mirror());
					break;
				case 8:
					$this->addTransformation(new Transformation\Rotate(90));
					break;
			}
		}
	}
}
