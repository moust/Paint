<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Multiply implements FilterInterface
{
	/**
	 * @param int $red : Value of red component
	 * @param int $green : Value of green component
	 * @param int $blue : Value of blue component
	 * @param int $alpha : Alpha channel, A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
	 * @return void
	 **/
	public function __construct($red, $green, $blue, $alpha)
	{
		$this->red = min(255, abs((int) $red));
		$this->green = min(255, abs((int) $green));
		$this->blue = min(255, abs((int) $blue));
		$this->alpha = min(127, abs((int) $alpha));

		// get oposite color
		$this->red = 0 - (255 - $this->red);
		$this->green = 0 - (255 - $this->green);
		$this->blue = 0 - (255 - $this->blue);
	}

	public function apply($image)
	{
		// subtract the opposite color from the image
		if (!imagefilter($image, IMG_FILTER_COLORIZE, $this->red, $this->green, $this->blue, $this->alpha))
		{
			throw new FilterException('Fail to apply multiply filter.');
		}
	}
}

