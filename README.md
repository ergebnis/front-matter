# front-matter

[![Integrate](https://github.com/ergebnis/front-matter/workflows/Integrate/badge.svg)](https://github.com/ergebnis/front-matter/actions)
[![Merge](https://github.com/ergebnis/front-matter/workflows/Merge/badge.svg)](https://github.com/ergebnis/front-matter/actions)
[![Release](https://github.com/ergebnis/front-matter/workflows/Release/badge.svg)](https://github.com/ergebnis/front-matter/actions)
[![Renew](https://github.com/ergebnis/front-matter/workflows/Renew/badge.svg)](https://github.com/ergebnis/front-matter/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/front-matter/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/front-matter)
[![Type Coverage](https://shepherd.dev/github/ergebnis/front-matter/coverage.svg)](https://shepherd.dev/github/ergebnis/front-matter)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/front-matter/v/stable)](https://packagist.org/packages/ergebnis/front-matter)
[![Total Downloads](https://poser.pugx.org/ergebnis/front-matter/downloads)](https://packagist.org/packages/ergebnis/front-matter)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/front-matter/d/monthly)](https://packagist.org/packages/ergebnis/front-matter)

This package provides a front matter parser.

## Installation

Run

```sh
composer require ergebnis/front-matter
```

## Usage

This packages comes with an [`Ergebnis\FrontMatter\Parser`](src/Parser.php) interface and provides the following parsers:

 - [`Ergebnis\FrontMatter\YamlParser`](#yamlparser)

### `YamlParser`

With the `YamlParser`, you can test if a `string` has YAML front matter:

```php
<?php

declare(strict_types=1);

use Ergebnis\FrontMatter;

$parser = new FrontMatter\YamlParser();

$unparsedContentWithoutFrontMatter = FrontMatter\Content::fromString('Hello, how are you today?');

$parser->hasFrontMatter($unparsedContentWithoutFrontMatter); // false

$unparsedContentWithFrontMatter = FrontMatter\Content::fromString(<<<TXT
---
page:
  title: "Hello"
  description: "Good to see you, how can I help you?"
---
TXT);

$parser->hasFrontMatter($unparsedContentWithFrontMatter); // true
```

With the `YamlParser`, you can parse a `string`, regardless of whether it has YAML front matter or not.

```php
<?php

declare(strict_types=1);

use Ergebnis\FrontMatter;

$parser = new FrontMatter\YamlParser();

$unparsedContentWithoutFrontMatter = FrontMatter\Content::fromString('Hello, how are you today?');

/** @var FrontMatter\Parsed $parsedWithoutFrontMatter */
$parsedWithoutFrontMatter = $parser->parse($unparsedContentWithoutFrontMatter);

$unparsedContentWithFrontMatter = FrontMatter\Content::fromString(<<<TXT
---
page:
  title: "Hello"
  description: "Good to see you, how can I help you?"
---
TXT);

/** @var FrontMatter\Parsed $parsedWithFrontMatter */
$parsedWithFrontMatter = $parser->parse($unparsedContentWithoutFrontMatter);

var_dump($parsedWithFrontMatter->frontMatter()->data()->has('page.title')); // true
var_dump($parsedWithFrontMatter->frontMatter()->data()->get('page.title')); // "Hello"
```

:exclamation: The `YamlParser` will throw an [`Ergebnis\FrontMatter\Exception\FrontMatterCanNotBeParsed`](src/Exception/FrontMatterCanNotBeParsed.php) exception when the front matter is invalid YAML and an [`Ergebnis\FrontMatter\Exception\FrontMatterIsNotAnObject`](src/Exception/FrontMatterIsNotAnObject.php) exception when the front matter does not describe an object.

:bulb: The `YamlParser` returns an [`Ergebnis\FrontMatter\Parsed`](src/Parsed.php) value object on success, regardless of whether the value has front matter or not.

## Changelog

The maintainers of this package record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this package suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this package ask contributors to follow the [code of conduct](.github/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this package provide limited support.

You can support the maintenance of this package by [sponsoring @localheinz](https://github.com/sponsors/localheinz) or [requesting an invoice for services related to this package](mailto:am@localheinz.com?subject=ergebnis/front-matter:%20Requesting%20invoice%20for%20services).

## PHP Version Support Policy

This package supports PHP versions with [active support](https://www.php.net/supported-versions.php).

The maintainers of this package add support for a PHP version following its initial release and drop support for a PHP version when it has reached its end of active support.

## Security Policy

This package has a [security policy](.github/SECURITY.md).

## License

This package uses the [MIT license](LICENSE.md).

## Credits

This package is inspired by [`webuni/front-matter`](https://github.com/webuni/front-matter), originally licensed under MIT by [Martin Hasoň](https://github.com/hason).

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
