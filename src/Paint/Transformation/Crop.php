<?php

namespace Paint\Transformation;

use Paint\Exception\TransformationException;

class Crop implements TransformationInterface
{
	/**
	 * Instanciate crop transformation
	 *
	 * @param int Output width.
	 * @param int Output height.
	 * @return void
	 **/
	public function __construct($width, $height)
	{
		$this->outputWidth = abs((int) $width);
		$this->outputHeight = abs((int) $height);
	}
	
	/**
     * {@inheritdoc}
     */
	public function apply(&$image)
	{
		$inputWidth = imagesx($image);
		$inputHeight = imagesy($image);

		$input_aspect = $inputWidth / $inputHeight;
		$output_aspect = $this->outputWidth / $this->outputHeight;

		if ( $input_aspect >= $output_aspect )
		{
			// If image is wider than thumbnail (in aspect ratio sense)
			$new_height = $this->outputHeight;
			$new_width = $inputWidth / ($inputHeight / $this->outputHeight);
		}
		else
		{
			// If the thumbnail is wider than the image
			$new_width = $this->outputWidth;
			$new_height = $inputHeight / ($inputWidth / $this->outputWidth);
		}

		$output = imagecreatetruecolor($this->outputWidth, $this->outputHeight);

		// Resize and crop
		imagecopyresampled(
			$output, $image,
			0 - ($new_width - $this->outputWidth) / 2, // Center the image horizontally
			0 - ($new_height - $this->outputHeight) / 2, // Center the image vertically
			0, 0,
			$new_width, $new_height,
			$inputWidth, $inputHeight
		);

		$image = $output;
	}
}
