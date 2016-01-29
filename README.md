# DOM Class Name

[![Latest Stable Version][stable-image]][stable-url]
[![Latest Unstable Version][unstable-image]][unstable-url]
[![License][license-image]][license-url]
[![Build Status][travis-image]][travis-url]
[![Coverage Status][coveralls-image]][coveralls-url]

## Install via Composer

```sh
composer require ecailles/dom-class-name
```

## Usage

### Block

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block--block-modifier'
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->value();
```

### Element

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block--block-modifier__element-modifier'
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->value();
```

### Classes

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block--block-modifier__element-modifier class1 class2'
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->classname(['class1', 'class2'])
    ->value();
```

### Class names as array

```php
use Ecailles\DomClassName\Bem\Bem;

// ['block--block-modifier__element-modifier', 'class1', 'class2']
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->classname(['class1', 'class2'])
    ->value();
```

[stable-image]: https://poser.pugx.org/ecailles/dom-class-name/v/stable
[stable-url]: https://packagist.org/packages/ecailles/dom-class-name

[unstable-image]: https://poser.pugx.org/ecailles/dom-class-name/v/unstable
[unstable-url]: https://packagist.org/packages/ecailles/dom-class-name

[license-image]: https://poser.pugx.org/ecailles/dom-class-name/license
[license-url]: https://packagist.org/packages/ecailles/dom-class-name

[travis-image]: https://travis-ci.org/ecailles/dom-class-name.svg?branch=master
[travis-url]: https://travis-ci.org/ecailles/dom-class-name

[coveralls-image]: https://coveralls.io/repos/ecailles/dom-class-name/badge.svg?branch=master&service=github
[coveralls-url]: https://coveralls.io/github/ecailles/dom-class-name?branch=master
