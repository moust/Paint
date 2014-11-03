<?php

namespace Paint\Tests;

use Paint\Color;
use Paint\Paint;
use Paint\Utils;

class PaintTest extends \PHPUnit_Framework_TestCase
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

	protected function assertImageColorEquals($img, $red, $green, $blue)
	{
		$rgb = imagecolorsforindex($img, imagecolorat($img, 4, 4));
		$this->assertEquals($red, $rgb['red']);
		$this->assertEquals($green, $rgb['green']);
		$this->assertEquals($blue, $rgb['blue']);
	}

	public function testCreate()
	{
		$paint = Paint::create();
		$this->assertInstanceOf('Paint\Paint', Paint::create());
	}

	public function testValidColor()
	{
		$this->assertEquals(0, Utils::validColor(0));
		$this->assertEquals(255, Utils::validColor(255));
		$this->assertEquals(255, Utils::validColor('255'));
		$this->assertEquals(0, Utils::validColor(0x00));
		$this->assertEquals(255, Utils::validColor(0xFF));
		$this->assertEquals(255, Utils::validColor('0xFF'));
	}

	public function testColor()
	{
		$this->assertEquals(imagecolorallocate(imagecreatetruecolor(1, 1), 255, 127, 0), Color::get(255, 127, 0));
		$this->assertEquals(imagecolorallocate(imagecreatetruecolor(1, 1), 0x88, 0x00, 0xFF), Color::get(0x88, 0x00, 0xFF));
		$this->assertEquals(imagecolorallocatealpha(imagecreatetruecolor(1, 1), 255, 127, 0, 127), Color::get(255, 127, 0, 127));
	}

	public function testInput()
	{
		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
	}

	public function testInvalidInput()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->input('tests/undefined.jpeg');

		$paint = Paint::create();
		$paint->input('tests/');
	}

	public function testUnsupportedInput()
	{
		$this->setExpectedException('Paint\Exception\CapabilityException');

		$paint = Paint::create();
		$paint->input('tests/unsupported.psd');
	}

	public function testOutput()
	{
		$paint = Paint::create();
		$paint->output('tests/generated/carlos-output.jpeg');
	}

	public function testOutputNotWritable()
	{
		$this->setExpectedException('InvalidArgumentException');

		$paint = Paint::create();
		$paint->output('tests/generated/undefined/carlos.jpeg');
	}

	public function testColorFill()
	{
		$file = 'tests/generated/output-red.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->setOutputSize(100, 100);
		$paint->colorFill(new Color(255, 0, 0));
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));

		$img = imagecreatefromjpeg($file);
		$this->assertImageColorEquals($img, 254, 0, 0);
	}

	public function testResizeSmaller()
	{
		$file = 'tests/generated/resize-smaller.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(300, 300);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testResizeBigger()
	{
		$file = 'tests/generated/resize-bigger.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(1024, 768, Paint::RESIZE_FIT);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testCrop()
	{
		$file = 'tests/generated/resize-crop.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->setOutputSize(300, 300, Paint::RESIZE_CROP);
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

}
