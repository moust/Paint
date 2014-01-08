<?php

namespace Paint\Tests;

use Paint\Paint;

class PaintFilterTest extends \PHPUnit_Framework_TestCase
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

		if (!file_exists('tests/generated/filter')) {
			mkdir('tests/generated/filter');
		}
	}

	public function testFilterGrayscale()
	{
		$file = 'tests/generated/filter/filter-grayscale.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Grayscale() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterNegate()
	{
		$file = 'tests/generated/filter/filter-negate.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Negate() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterEdgedetect()
	{
		$file = 'tests/generated/filter/filter-edgedetect.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Edgedetect() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterGaussianBlur()
	{
		$file = 'tests/generated/filter/filter-gaussianblur.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\GaussianBlur() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterMeanRemoval()
	{
		$file = 'tests/generated/filter/filter-meanremoval.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\MeanRemoval() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterEmboss()
	{
		$file = 'tests/generated/filter/filter-emboss.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Emboss() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterBrightness()
	{
		$file = 'tests/generated/filter/filter-brightness.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Brightness(127) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterContrast()
	{
		$file = 'tests/generated/filter/filter-contrast.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Contrast(-100) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterSmoothness()
	{
		$file = 'tests/generated/filter/filter-smoothness.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Smoothness(-8) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterPixelate()
	{
		$file = 'tests/generated/filter/filter-pixelate.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Pixelate(5, true) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterColorize()
	{
		$file = 'tests/generated/filter/filter-colorize.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Colorize(255, 0, 0, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterMultiply()
	{
		$file = 'tests/generated/filter/filter-multiply.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Multiply(0, 255, 0, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterConvolutionEmboss()
	{
		$file = 'tests/generated/filter/filter-convolution-emboss.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$matrix = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Convolution($matrix, 1, 127) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterConvolutionBlur()
	{
		$file = 'tests/generated/filter/filter-convolution-blur.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$matrix = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));

		$paint = Paint::create();
		$paint->input('tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\Convolution($matrix, 16, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}
}