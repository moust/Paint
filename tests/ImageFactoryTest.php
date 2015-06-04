<?php

namespace Paint\Tests;

use Paint\ImageFactory;
use Paint\Transformation;
use Paint\Filter;

class ImageFactoryTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
    }

    protected function setUp()
    {
        if (!file_exists('tests/generated')) {
            mkdir('tests/generated');
        }
    }

    public function testCreate()
    {
        $factory = ImageFactory::create('tests/carlos.jpeg');
        $this->assertInstanceOf('Paint\Image', $factory->getImage());
    }

    public function testFormat()
    {
        $factory = ImageFactory::create('tests/carlos.jpeg');

        if (!function_exists('imagegif') || !function_exists('imagejpeg') || !function_exists('imagepng')) {
            $this->setExpectedException('Paint\Exception\CapabilityException');
        }

        $factory->generate('gif', 'tests/generated/output_factory.gif');
        list($width, $height, $type) = getimagesize('tests/generated/output_factory.gif');
        $this->assertEquals(IMAGETYPE_GIF, $type);

        $factory->generate('jpeg', 'tests/generated/output_factory.jpg');
        list($width, $height, $type) = getimagesize('tests/generated/output_factory.jpg');
        $this->assertEquals(IMAGETYPE_JPEG, $type);

        $factory->generate('png', 'tests/generated/output_factory.png');
        list($width, $height, $type) = getimagesize('tests/generated/output_factory.png');
        $this->assertEquals(IMAGETYPE_PNG, $type);
    }

    public function testFullCreation()
    {
        $factory = ImageFactory::create('tests/carlos.jpeg');
        $factory->addTransformation(new Transformation\Crop(300, 200));
        $factory->addFilter(new Filter\GaussianBlur());
        $factory->generate('jpeg', 'tests/generated/output_factory_full.jpg');

        list($width, $height) = getimagesize('tests/generated/output_factory_full.jpg');
        $this->assertEquals(300, $width);
        $this->assertEquals(200, $height);
    }
}