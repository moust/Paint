<?php

namespace Paint\Filter;

class Grayscale implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_GRAYSCALE))
		{
			throw new FilterException('Fail to apply grayscale filter.');
		}
	}
}