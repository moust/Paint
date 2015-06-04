<?php

namespace Paint;

use Paint\Exception\CapabilityException;
use Paint\Filter\FilterInterface;
use Paint\Format;
use Paint\Format\FormatInterface;
use Paint\Transformation\TransformationInterface;

/**
 * Image factory
 *
 * @author Quentin Aupetit
 **/
class ImageFactory
{
	private $image;

	/**
	 * Create Image instance from an image path
	 *
	 * @param string Path to the image.
	 * @return Image
	 * @author Quentin Aupetit
	 **/
	public static function create($filename)
	{
		return new self($filename);
	}

	public function __construct($filename)
	{
		$this->image = new Image($filename);
	}

	public function getImage()
	{
		return $this->image;
	}

	public function addTransformation(TransformationInterface $transformation)
	{
		$this->image->addTransformation($transformation);
	}

	public function addFilter(FilterInterface $filter)
	{
		$this->image->addFilter($filter);
	}

	public function generate($format, $filename = null)
	{
		switch ($format) {
			case 'gif':
				$format = new Format\GIF();
				break;
			case 'jpeg':
				$format = new Format\JPEG();
				break;
			case 'png':
				$format = new Format\PNG();
				break;
			case 'wbmp':
				$format = new Format\WBMP();
				break;
			case 'webp':
				$format = new Format\WebP();
				break;
			case 'xbm':
				$format = new Format\XBM();
				break;
		}

		$this->image->generate($format, $filename);
	}
}
