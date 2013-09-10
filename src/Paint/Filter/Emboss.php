<?php

namespace Paint\Filter;

class Emboss implements FilterInterface
{
	public function apply($image)
	{
		if (!imagefilter($image, IMG_FILTER_EMBOSS))
		{
			throw new FilterException('Fail to apply emboss filter.');
		}
	}
}