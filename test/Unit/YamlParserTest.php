<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter\YamlParser::class)]
#[Framework\Attributes\UsesClass(FrontMatter\BodyMatter::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Content::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Data::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Exception\FrontMatterCanNotBeParsed::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Exception\FrontMatterHasInvalidKeys::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Exception\FrontMatterIsNotAnObject::class)]
#[Framework\Attributes\UsesClass(FrontMatter\FrontMatter::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Parsed::class)]
final class YamlParserTest extends Framework\TestCase
{
    use FrontMatter\Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testHasFrontMatterReturnsFalseWhenContentDoesNotHaveFrontMatter(string $value): void
    {
        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter(FrontMatter\Content::fromString($value)));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsAlmostEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
----
--
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsNonEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testParseReturnsParsedWhenContentIsFrontMatterDelimiter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::empty(),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'
---
TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::empty(),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'
---

TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::empty(),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::empty(),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsAlmostEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
----
--
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::empty(),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'
----
--
TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
---
TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
---

TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
---

TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---


---
TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---


---

TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---


---

TXT),
                FrontMatter\Data::empty(),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseThrowsFrontMatterCanNotBeParsedWhenFrontMatterCanNotBeParsed(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterCanNotBeParsed::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsFalse(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
false
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsTrue(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
true
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsString(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsInt(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
123
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsInvalidFrontMatterWhenFrontMatterIsFloat(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
3.14
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsArray(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
- "foo"
- "bar"
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $this->expectException(FrontMatter\Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseReturnsParsedWhenContentIsNonEmptyFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'


TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::empty()),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'


TXT)),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT);

        $parser = new FrontMatter\YamlParser();

        $parsed = $parser->parse($content);

        $expected = FrontMatter\Parsed::create(
            FrontMatter\FrontMatter::create(
                FrontMatter\Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

TXT),
                FrontMatter\Data::fromArray([
                    'baz' => [
                        'qux',
                        'quz',
                    ],
                    'foo' => 'bar',
                ]),
            ),
            FrontMatter\BodyMatter::create(FrontMatter\Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT)),
        );

        self::assertEquals($expected, $parsed);
    }
}
