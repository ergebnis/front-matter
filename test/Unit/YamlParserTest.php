<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\FrontMatter\BodyMatter;
use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\FrontMatter;
use Ergebnis\FrontMatter\Parsed;
use Ergebnis\FrontMatter\Test;
use Ergebnis\FrontMatter\YamlParser;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(YamlParser::class)]
#[Framework\Attributes\UsesClass(BodyMatter::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterCanNotBeParsed::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterHasInvalidKeys::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterIsNotAnObject::class)]
#[Framework\Attributes\UsesClass(FrontMatter::class)]
#[Framework\Attributes\UsesClass(Parsed::class)]
final class YamlParserTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testHasFrontMatterReturnsFalseWhenValueDoesNotHaveFrontMatter(string $value): void
    {
        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiter(): void
    {
        $value = <<<'TXT'
---
TXT;

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $value = <<<'TXT'
---

TXT;

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $value = <<<'TXT'
---
<h1>
    Hello
</h1>
TXT;

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---

<h1>
    Hello
</h1>
TXT;

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsFalseWhenValueIsAlmostEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
----
--
TXT;

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueIsEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
---
---
TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---
---

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterAndBodyMatter(): void
    {
        $value = <<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueIsEmptyFrontMatterWithWhitespace(): void
    {
        $value = <<<'TXT'
---


---
TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---


---

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueIsNonEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiter(): void
    {
        $value = <<<'TXT'
---
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString($value), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $value = <<<'TXT'
---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString($value), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $value = <<<'TXT'
---
<h1>
    Hello
</h1>
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString($value), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---

<h1>
    Hello
</h1>
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString($value), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueIsAlmostEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
----
--
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString($value), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueIsEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
---
---
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString(''), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---
---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterAndBodyMatter(): void
    {
        $value = <<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(
                <<<'TXT'

<h1>
    Hello
</h1>

TXT
            ),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueIsEmptyFrontMatterWithWhitespace(): void
    {
        $value = <<<'TXT'
---


---
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---


---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseThrowsFrontMatterCanNotBeParsedWhenFrontMatterCanNotBeParsed(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterCanNotBeParsed::class);

        $parser->parse($value);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsFalse(): void
    {
        $value = <<<'TXT'
---
false
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsTrue(): void
    {
        $value = <<<'TXT'
---
true
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsString(): void
    {
        $value = <<<'TXT'
---
foo
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsInt(): void
    {
        $value = <<<'TXT'
---
123
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseThrowsInvalidFrontMatterWhenFrontMatterIsFloat(): void
    {
        $value = <<<'TXT'
---
3.14
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsArray(): void
    {
        $value = <<<'TXT'
---
- "foo"
- "bar"
---
TXT;

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($value);
    }

    public function testParseReturnsParsedWhenValueIsNonEmptyFrontMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT;

        $frontMatter = FrontMatter::fromArray([
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quz',
            ],
        ]);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals(BodyMatter::fromString(''), $parsed->bodyMatter());
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(<<<'TXT'


TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $value = <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(<<<'TXT'


TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $value = <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        $expected = Parsed::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT),
        );

        self::assertEquals($expected, $parsed);
    }
}
