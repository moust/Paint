<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Negate implements FilterInterface
{
    public function apply($image)
    {
        if (!imagefilter($image, IMG_FILTER_NEGATE))
        {
            throw new FilterException('Fail to apply negate filter.');
        }
    }
}
