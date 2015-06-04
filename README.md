Paint, a PHP library for image manipulation with GD
===================================================
[![Build Status](https://secure.travis-ci.org/moust/Paint.png?branch=master)](http://travis-ci.org/moust/Paint) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/079bf687-48d4-426a-af3d-3d374525e766/mini.png)](https://insight.sensiolabs.com/projects/079bf687-48d4-426a-af3d-3d374525e766)

Paint is an Object Oriented library to manipulate image files in a variety of different image formats, according to your PHP GD extensions's version and capabilities.

## Basic Usage

```php
<?php
$image = new Pain\Image('source.jpeg');
$image->addTransformation( new Transformation\Resize(300, 200) );
$image->addFilter( new Paint\Filter\Grayscale() );
$image->generate( new Paint\Format\JPEG(60), 'destination.jpeg' );
```

## Documentation

This documentation is an introduction to discover the API. It's recommended to browse the source code as it is self-documented.

`Paint\Image` is the main object to use to manipulate images :

```php
$image = new Pain\Image('source.jpeg');
```

### Manipulate image

Images's types that can be opened are GIF, JPEG, PNG, WBMP and XBM.

To generate the output image, use the `Paint\Image::generate` method.

```php
$image->generate();
```

`Paint\Image` can writing a variety of different image formats. To define the desired output format, use `Paint\Image::generate` method with an instance object which extends `FormatInterface` as extra parameter, like `Paint\Format\JPEG` or `Paint\Format\PNG`.

```php
$image->generate( new Paint\Format\PNG(), 'destination.png' );
```

If the output file path is not define, the raw image stream will be outputted directly, else it will be write.

Some output format can take optional parameters like compression level for JPEG or PNG. Browse the source code for more information about it.

```php
$image->generate( new Paint\Format\JPEG(60) );
```

*Writing formats supported are :*
- GIF
- JPEG
- PNG
- WBMP
- WebP (only supported since PHP 5.5 version)
- XBM

### Transformations

Image can be resize or crop by adding transformation object.

```php
$image->addTransformation( new Paint\Transformation\Resize(960, 480) );
```

```php
$image->addTransformation( new Paint\Transformation\Crop(500, 500) );
```

*Transformations supported are :*
- Resize
- Crop
- Mirror
- Rotate

It exists a shortcut function to resize or crop an image. The parameters respectively corresponds to width and height of the image. :

```php
$image->setOutputSize(960, 480);
```

It's possible to pass an optional third parameter to define if the image must be crop or must fit the image size. This parameter must be `Paint::RESIZE_CROP` or `Paint::RESIZE_FIT`.

```php
$image->setOutputSize(960, 480, Paint::RESIZE_CROP);
```

By default, the image fits to the image's size.

#### Filters

You can apply filters on `Paint\Image` with the `Paint\Image::addFilter` method.

```php
$image->addFilter( new Paint\Filter\Grayscale() );
```

Some filters take optionals parameters like `Paint\Filter\Colorize` which needs color's values. Browse the source code for more information about it.

```php
$image->addFilter( new Paint\Filter\Colorize(255, 0, 0, 75) );
```

*Filter availables :*
- Brightness
- Colorize
- Contrast
- Convolution
- Edgedetect
- Emboss
- GaussianBlur
- Grayscale
- MeanRemoval
- Multiply
- Negate
- Pixelate
- SelectiveBlur
- Smoothness

You can build your own additional filters by creating objects that implements `Paint\Filter\FilterInterface` interface.

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ php composer.phar install --dev
    $ vendor/bin/phpunit

## License

Paint is released with MIT License :

Copyright (c) 2013 Quentin Aupetit

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
