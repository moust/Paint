<?php

namespace Paint\Transformation;

use Paint\Exception\TransformationException;

class Rotate implements TransformationInterface
{
	/**
	 * Instanciate rotate transformation
	 *
	 * @return void
	 **/
	public function __construct($angle, $bgd_color = 0, $ignore_transparent = 0)
	{
		$this->angle = (float) $angle;
		$this->bgd_color = (int) $bgd_color;
		$this->ignore_transparent = (int) $ignore_transparent;
	}
	
	/**
     * {@inheritdoc}
     */
	public function apply(&$image)
	{
		$image = imagerotate($image, $this->angle, $this->bgd_color, $this->ignore_transparent);
	}
}
