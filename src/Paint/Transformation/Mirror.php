<?php

namespace Paint\Transformation;

use Paint\Exception\TransformationException;

class Mirror implements TransformationInterface
{
	/**
	 * Instanciate mirror transformation
	 *
	 * @return void
	 **/
	public function __construct()
	{
		
	}
	
	/**
     * {@inheritdoc}
     */
	public function apply(&$image)
	{
		$width = imagesx($image);
		$height = imagesy($image);

		$src_x = $width -1;
		$src_y = 0;
		$src_width = -$width;
		$src_height = $height;

		$temp = imagecreatetruecolor($width, $height);

		imagecopyresampled($temp, $image, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height);

		$image = $temp;
	}
}
