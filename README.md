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

Provides a front matter parser.

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

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## Security Policy

Please have a look at [`SECURITY.md`](.github/SECURITY.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

This package is inspired by [`webuni/front-matter`](https://github.com/webuni/front-matter), originally licensed under MIT by [Martin Hasoň](https://github.com/hason).

## Curious what I am up to?

Follow me on [Twitter](https://twitter.com/intent/follow?screen_name=localheinz)!
