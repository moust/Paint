Paint, a PHP library for image manipulation with GD
===================================================
[![Build Status](https://secure.travis-ci.org/moust/Paint.png?branch=master)](http://travis-ci.org/moust/Paint)

Paint is an Object Oriented library to manipulate image files in a variety of different image formats, according to your PHP GD extensions's version and capabilities.

## Basic Usage

```php
<?php
$paint = Paint\Paint::create();
$paint->input('source.jpeg');
$paint->output('destination.jpeg');
$paint->setOutputSize(960, 480);
$paint->addFilter( new Paint\Filter\Grayscale() );
$paint->generate(new Paint\Format\JPEG(60));
```

## Documentation

This documentation is an introduction to discover the API. It's recommended to browse the source code as it is self-documented.

`Paint\Paint` is the main object to use to manipulate medias. To build it, use the static `Paint\Paint::create` :

```php
$paint = Paint\Paint::create();
```

### Manipulate image

Paint\Paint creates ressource based on file paths. To open a file path, use the `Paint\Paint::input` method.

```php
$paint->input('source.jpeg');
```

Images's types that can be opened are GIF, JPEG, PNG, WBMP and XBM.

To define an output file path, use the `Paint\Paint::output` method.  

```php
$paint->output('destination.jpeg');
```


To generate the output image, use the `Paint\Paint::generate` method.

```php
$paint->generate();
```

If the output file path is not define, the raw image stream will be outputted directly, else it will be write.


`Paint\Paint` can writing a variety of different image formats. To define the desired output format, use `Paint\Paint::generate` method with an instance object which extends `FormatInterface` as extra parameter, like `Paint\Format\JPEG` or `Paint\Format\PNG`.

```php
$paint->generate( new Paint\Format\PNG() );
```

Some output format can take optional parameters like compression level for JPEG or PNG. Browse the source code for more information about it.

```php
$paint->generate( new Paint\Format\JPEG(60) );
```

*Writing formats supported are :*
- GIF
- JPEG
- PNG
- WBMP
- WebP (only supported since PHP 5.5 version)
- XBM

#### Resize

To define size of the output image, use the `Paint\Paint::setOutputSize` method. The parameters respectively corresponds to width and height of the image.

```php
$paint->setOutputSize(960, 480);
```

It's possible to pass an optional third parameter to define if the image must be crop or must fit the image size. This parameter must be `Paint::RESIZE_CROP` or `Paint::RESIZE_FIT`. 

```php
$paint->setOutputSize(960, 480, Paint::RESIZE_CROP);
```

By default, the image fits to the image's size.

#### Filters

You can apply filters on `Paint\Paint` with the `Paint\Paint::addFilter` method.

```php
$paint->addFilter( new Paint\Filter\Grayscale() );
```

Some filters take optionals parameters like `Paint\Filter\Colorize` which needs color's values. Browse the source code for more information about it.

```php
$paint->addFilter( new Paint\Filter\Colorize(255, 0, 0, 75) );
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