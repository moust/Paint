<?php

namespace Paint\Format;

use Paint\Exception\CapabilityException;

class JPEG implements FormatInterface
{
    public $quality = 100;

    /**
     * Constructor
     *
     * @param int $quality JPEG Compression level: from 0 to 100 (no compression).
     **/
    public function __construct($quality = 100)
    {
        if (!function_exists('imagejpeg')) {
            throw new CapabilityException('JPEG is not supported.');
        }
        
        $this->quality = min(100, abs((int) $quality));
    }

    /**
     * {@inheritdoc}
     */
    public function generate($output, $outputPath = null)
    {
        imagejpeg($output, $outputPath, $this->quality);
    }
}
