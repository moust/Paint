<?php
require_once __DIR__.'/vendor/autoload.php';

$paint = Paint\Paint::create();

$paint->input('tests/carlos.jpeg');
$paint->output('destination.jpeg');
$paint->setOutputSize(960, 480);
$paint->addFilter( new Paint\Filter\FilterGrayscale() );

$paint->generate(new Paint\Format\JPEG(60));
