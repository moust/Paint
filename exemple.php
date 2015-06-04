<?php
require_once __DIR__.'/vendor/autoload.php';

$image = new Paint\Image('tests/carlos.jpeg');
$image->addTransformation(new Transformation\Crop(300, 200));
$image->addFilter(new Paint\Filter\FilterGrayscale());
$image->generate(new Paint\Format\JPEG(60), 'tests/destination.jpeg');

$factory = ImageFactory::create('tests/carlos.jpeg');
$factory->addTransformation(new Transformation\Crop(300, 200));
$factory->addFilter(new Filter\FilterGrayscale());
$factory->generate('jpeg', 'tests/destination.jpg');