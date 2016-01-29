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

## Basic Usage

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

// 'block--block-modifier__element--element-modifier'
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->value();
```

### Classes

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block--block-modifier__element--element-modifier class1 class2'
$classname = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->classname(['class1', 'class2'])
    ->value();
```

### Explicit Modifiers

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block--block-modifier__element--element-modifier'
$classname = (new Bem())
    ->blockModifier('block-modifier')
    ->elementModifier('element-modifier')
    ->block('block')->element('element')
    ->value();
```

### Class names as array

```php
use Ecailles\DomClassName\Bem\Bem;

// ['block--block-modifier__element--element-modifier', 'class1', 'class2']
$classnames = (new Bem())
    ->block('block')->modifier('block-modifier')
    ->element('element')->modifier('element-modifier')
    ->classname(['class1', 'class2'])
    ->get();
```

### Class names as string (implicit type conversion)

```php
use Ecailles\DomClassName\Bem\Bem;

$bem = (new Bem())->block('block')->classname('class');

// 'block class'
echo htmlspecialchars($bem, ENT_QUOTES, 'UTF-8');
```

## Prefixing

```php
use Ecailles\DomClassName\Bem\Bem;

// 'prefix-block'
$classname = (new Bem())->prefix('prefix')->block('block')->value();
```

or

```php
use Ecailles\DomClassName\Bem\Bem;

// 'prefix-block'
$classname = (new Bem('prefix'))->block('block')->value();
```

## Custom Separators

### Prefix Separator

```php
use Ecailles\DomClassName\Bem\Bem;

// 'prefix__block'
$classname = (new Bem())->prefix('prefix')->prefixSeparator('__')
    ->block('block')->value();
```

or

```php
use Ecailles\DomClassName\Bem\Bem;

// 'prefix__block'
$classname = (new Bem('prefix', '__'))->block('block')->value();
```

### Element Separator

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block-element'
$classname = (new Bem())->elementSeparator('-')
    ->block('block')->element('element')->value();
```

or

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block-element'
$classname = (new Bem(null, null, '-'))
    ->block('block')->element('element')->value();
```

### Modifier Separator

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block-modifier'
$classname = (new Bem())->modifierSeparator('-')
    ->block('block')->element('modifier')->value();
```

or

```php
use Ecailles\DomClassName\Bem\Bem;

// 'block-modifier'
$classname = (new Bem(null, null, null, '-'))
    ->block('block')->modifier('modifier')->value();
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
