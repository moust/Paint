<?php

namespace Paint\Tests;

use Paint\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
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
		$image = new Image('tests/carlos.jpeg');
		$this->assertInstanceOf('Paint\Image', $image);
		return $image;
	}

	public function testOutputNotWritable()
	{
		$this->setExpectedException('InvalidArgumentException');

		// test invalid path
		$file = 'tests/generated/undefined/exists.jpeg';
		$paint = new Image($file);

		// test unwritable file
		$file = 'tests/generated/exists.jpeg';

		// create an unwritable temp file
		if (file_exists($file)) {
			unlink($file);
		}

		file_put_contents($file, null);
		chmod($file, 0400);
		$paint = new Image($file);
	}

	public function testGenerate()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output.jpg');
	}

	public function testTransformationResize()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->addTransformation(new \Paint\Transformation\Resize(100, 100));
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_resized.jpg');

		$source = imagecreatefromjpeg('tests/generated/output.jpg');
		$ratio = imagesy($source) / imagesx($source);

		list($width, $height) = getimagesize('tests/generated/output_resized.jpg');

		$this->assertEquals(100, $width);
		$this->assertEquals(round($width * $ratio), $height);
	}

	public function testTransformationCrop()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->addTransformation(new \Paint\Transformation\Crop(100, 100));
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_croped.jpg');

		list($width, $height) = getimagesize('tests/generated/output_croped.jpg');
		$this->assertEquals(100, $width);
		$this->assertEquals(100, $height);
	}

	public function testOutputSizeAuto()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->setOutputSize(300, 300);
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_resize_auto.jpg');

		$source = imagecreatefromjpeg('tests/generated/output.jpg');
		$ratio = imagesy($source) / imagesx($source);

		list($width, $height) = getimagesize('tests/generated/output_resize_auto.jpg');
		$this->assertEquals(300, $width);
		$this->assertEquals(round($width * $ratio), $height);
	}

	public function testOutputSizeFit()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->setOutputSize(300, 300, Image::RESIZE_FIT);
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_resize_fit.jpg');

		$source = imagecreatefromjpeg('tests/generated/output.jpg');
		$ratio = imagesy($source) / imagesx($source);

		list($width, $height) = getimagesize('tests/generated/output_resize_fit.jpg');
		$this->assertEquals(300, $width);
		$this->assertEquals(round($width * $ratio), $height);
	}

	public function testOutputSizeCrop()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->setOutputSize(300, 300, Image::RESIZE_CROP);
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_resize_crop.jpg');

		list($width, $height) = getimagesize('tests/generated/output_resize_crop.jpg');
		$this->assertEquals(300, $width);
		$this->assertEquals(300, $height);
	}

	public function testTransformationMirror()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->addTransformation(new \Paint\Transformation\Mirror());
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_mirrored.jpg');
	}

	public function testTransformationRotate()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->addTransformation(new \Paint\Transformation\Rotate(90));
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_rotated.jpg');

		$source = imagecreatefromjpeg('tests/generated/output.jpg');

		list($width, $height) = getimagesize('tests/generated/output_rotated.jpg');
		$this->assertEquals(imagesy($source), $width);
		$this->assertEquals(imagesx($source), $height);
	}

	public function testTransformationMultipleRotate()
	{
		$image = new Image('tests/carlos.jpeg');
		$image->addTransformation(new \Paint\Transformation\Rotate(90));
		$image->addTransformation(new \Paint\Transformation\Rotate(90));
		$image->addTransformation(new \Paint\Transformation\Rotate(180));
		$image->addTransformation(new \Paint\Transformation\Rotate(180));
		$image->addTransformation(new \Paint\Transformation\Rotate(270));
		$image->addTransformation(new \Paint\Transformation\Rotate(270));
		$image->generate(new \Paint\Format\JPEG(), 'tests/generated/output_multi_rotated.jpg');
	}
}
