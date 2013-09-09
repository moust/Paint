<?php

namespace Paint\Tests;

use Paint\Paint;

class PaintFilterTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	public function testFilterGrayscale()
	{
		$file = 'Tests/filter/filter-grayscale.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterGrayscale() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterNegate()
	{
		$file = 'Tests/filter/filter-negate.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterNegate() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterEdgedetect()
	{
		$file = 'Tests/filter/filter-edgedetect.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterEdgedetect() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterGaussianBlur()
	{
		$file = 'Tests/filter/filter-gaussianblur.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterGaussianBlur() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterMeanRemoval()
	{
		$file = 'Tests/filter/filter-meanremoval.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterMeanRemoval() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterEmboss()
	{
		$file = 'Tests/filter/filter-emboss.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterEmboss() );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterBrightness()
	{
		$file = 'Tests/filter/filter-brightness.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterBrightness(127) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterContrast()
	{
		$file = 'Tests/filter/filter-contrast.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterContrast(-100) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterSmoothness()
	{
		$file = 'Tests/filter/filter-smoothness.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterSmoothness(-8) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterPixelate()
	{
		$file = 'Tests/filter/filter-pixelate.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterPixelate(5, true) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterColorize()
	{
		$file = 'Tests/filter/filter-colorize.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterColorize(255, 0, 0, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterMultiply()
	{
		$file = 'Tests/filter/filter-multiply.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterMultiply(0, 255, 0, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterConvolutionEmboss()
	{
		$file = 'Tests/filter/filter-convolution-emboss.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$matrix = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterConvolution($matrix, 1, 127) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}

	public function testFilterConvolutionBlur()
	{
		$file = 'Tests/filter/filter-convolution-blur.jpeg';

		if (file_exists($file)) {
			unlink($file);
		}

		$matrix = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));

		$paint = Paint::create();
		$paint->input('Tests/carlos.jpeg');
		$paint->addFilter( new \Paint\Filter\FilterConvolution($matrix, 16, 0) );
		$paint->output($file);
		$paint->generate(new \Paint\Format\JPEG());

		$this->assertEquals(true, file_exists($file));
	}
}