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
use Ergebnis\FrontMatter\UnparsedContent;
use Ergebnis\FrontMatter\YamlParser;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(YamlParser::class)]
#[Framework\Attributes\UsesClass(BodyMatter::class)]
#[Framework\Attributes\UsesClass(UnparsedContent::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterCanNotBeParsed::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterHasInvalidKeys::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterIsNotAnObject::class)]
#[Framework\Attributes\UsesClass(FrontMatter::class)]
#[Framework\Attributes\UsesClass(Parsed::class)]
final class YamlParserTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testHasFrontMatterReturnsFalseWhenUnparsedContentDoesNotHaveFrontMatter(string $value): void
    {
        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter(UnparsedContent::fromString($value)));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsFrontMatterDelimiter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

TXT);

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT);

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT);

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsAlmostEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
----
--
TXT);

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---
TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---
TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsNonEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
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

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
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

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testParseReturnsParsedWhenUnparsedContentIsFrontMatterDelimiter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'
---
TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'
---

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'
---
<h1>
    Hello
</h1>
TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsAlmostEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
----
--
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'
----
--
TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

        $expected = Parsed::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

TXT),
        );

        self::assertEquals($expected, $parsed);
    }

    public function testParseReturnsParsedWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo: bar
baz
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterCanNotBeParsed::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsFalse(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
false
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsTrue(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
true
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsString(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsInt(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
123
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsInvalidFrontMatterWhenFrontMatterIsFloat(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
3.14
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsArray(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
- "foo"
- "bar"
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseReturnsParsedWhenUnparsedContentIsNonEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
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

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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

    public function testParseReturnsParsedWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(<<<'TXT'
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

        $parser = new YamlParser();

        $parsed = $parser->parse($unparsedContent);

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
