<?php

namespace Paint\Filter;

use Paint\Exception\FilterException;

class Convolution implements FilterInterface
{
    /**
     * @param array $matrix A 3x3 matrix: an array of three arrays of three floats.
     * @param float $div The divisor of the result of the convolution, used for normalization.
     * @param float $offset Color offset.
     * @return void
     **/
    public function __construct(array $matrix, $div, $offset)
    {
        $this->matrix = (array) $matrix;
        $this->div = (float) $div;
        $this->offset = (float) $offset;
    }

    public function apply($image)
    {
        if (!imageconvolution($image, $this->matrix, $this->div, $this->offset))
        {
            throw new FilterException('Fail to apply brightness filter.');
        }
    }
}
