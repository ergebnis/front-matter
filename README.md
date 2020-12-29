# front-matter

[![Integrate](https://github.com/ergebnis/front-matter/workflows/Integrate/badge.svg?branch=main)](https://github.com/ergebnis/front-matter/actions)
[![Prune](https://github.com/ergebnis/front-matter/workflows/Prune/badge.svg?branch=main)](https://github.com/ergebnis/front-matter/actions)
[![Release](https://github.com/ergebnis/front-matter/workflows/Release/badge.svg?branch=main)](https://github.com/ergebnis/front-matter/actions)
[![Renew](https://github.com/ergebnis/front-matter/workflows/Renew/badge.svg?branch=main)](https://github.com/ergebnis/front-matter/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/front-matter/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/front-matter)
[![Type Coverage](https://shepherd.dev/github/ergebnis/front-matter/coverage.svg)](https://shepherd.dev/github/ergebnis/front-matter)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/front-matter/v/stable)](https://packagist.org/packages/ergebnis/front-matter)
[![Total Downloads](https://poser.pugx.org/ergebnis/front-matter/downloads)](https://packagist.org/packages/ergebnis/front-matter)

Provides a front matter parser.

## Installation

Run

```sh
$ composer require ergebnis/front-matter
```

## Usage

This packages comes with an [`Ergebnis\FrontMatter\Parser`](src/Parser.php) interface and provides the following parsers:

 - [`Ergebnis\FrontMatter\YamlParser`](#yamlparser).

### `YamlParser`

With the `YamlParser`, you can test if a `string` has YAML front matter:

```php
<?php

use Ergebnis\FrontMatter;

$parser = new FrontMatter\YamlParser();

$valueWithoutFrontMatter = 'Hello, how are you today?';

$parser->hasFrontMatter($valueWithoutFrontMatter); // false

$valueWithFrontMatter = <<<TXT
---
page:
  title: "Hello"
  description: "Good to see you, how can I help you?"
---
TXT;

$parser->hasFrontMatter($valueWithFrontMatter); // true
```

With the `YamlParser`, you can parse a `string`, regardless of whether it has YAML front matter or not.

```php
<?php

use Ergebnis\FrontMatter;

$parser = new FrontMatter\YamlParser();

$valueWithoutFrontMatter = 'Hello, how are you today?';

/** @var FrontMatter\Parsed $parsed */
$parsed = $parser->parse($valueWithoutFrontMatter);

$valueWithFrontMatter = <<<TXT
---
page:
  title: "Hello"
  description: "Good to see you, how can I help you?"
---
TXT;

/** @var FrontMatter\Parsed $parsed */
$parsed = $parser->parse($valueWithoutFrontMatter);
```

:exclamation: The `YamlParser` will throw an [`Ergebnis\FrontMatter\Exception\InvalidFrontMatter`](src/Exception/InvalidFrontMatter.php) exception when

- the front matter cannot be parsed because it is invalid YAML
- the front matter data does not describe an associative array

:bulb: When the value does not contain front matter or contains valid YAML front matter, the `YamlParser` returns an [`Ergebnis\FrontMatter\Parsed`](src/Parsed.php) value object. This object composes an instance of [`Ergebnis\FrontMatter\Parsed\FrontMatter`](src/Parsed/FrontMatter.php) and an instance of [`Ergebnis\FrontMatter\Parsed\Content`](src/Parsed/Content.php).

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

This package is inspired by [`webuni/front-matter`](https://github.com/webuni/front-matter), originally licensed under MIT by [Martin Hasoň](https://github.com/hason).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
