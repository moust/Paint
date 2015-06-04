<?php

namespace Paint\Transformation;

use Paint\Exception\TransformationException;

class Resize implements TransformationInterface
{
    /**
     * Instanciate resize transformation
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

        $width = $inputWidth;
        $height = $inputHeight;

        // bigger
        if ($height < $this->outputHeight) {
            $width = ($this->outputHeight / $height) * $width;
            $height = $this->outputHeight;
        }
        if ($width < $this->outputWidth) {
            $height = ($this->outputWidth / $width) * $height;
            $width = $this->outputWidth;
        }

        // taller
        if ($height > $this->outputHeight) {
            $width = ($this->outputHeight / $height) * $width;
            $height = $this->outputHeight;
        }

        // wider
        if ($width > $this->outputWidth) {
            $height = ($this->outputWidth / $width) * $height;
            $width = $this->outputWidth;
        }

        $output = imagecreatetruecolor($width, $height);
        imagecopyresampled($output, $image, 0, 0, 0, 0, $width, $height, $inputWidth, $inputHeight);

        $image = $output;
    }
}
