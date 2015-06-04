<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Smoothness implements FilterInterface
{
    /**
     * Applies a 9-cell convolution matrix where center pixel has the weight arg1 and others weight of 1.0. The result is normalized by dividing the sum with arg1 + 8.0 (sum of the matrix).
     * any float is accepted, large value (in practice: 2048 or more) = no change
     *
     * @param float $arg1 Smoothness level
     * @return void
     **/
    public function __construct($arg1)
    {
        $this->arg1 = (float) $arg1;
    }

    public function apply($image)
    {
        if (!imagefilter($image, IMG_FILTER_SMOOTH, $this->arg1))
        {
            throw new FilterException('Fail to apply smoothness filter.');
        }
    }
}
