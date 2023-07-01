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
use Ergebnis\FrontMatter\ParsedContent;
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
#[Framework\Attributes\UsesClass(ParsedContent::class)]
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
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsFalseWhenUnparsedContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
<h1>
    Hello
</h1>
TXT
        );

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
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
----
--
TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
---

TXT
        );

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
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---


---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---


---

TXT
        );

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
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testHasFrontMatterReturnsTrueWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($unparsedContent));
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsFrontMatterDelimiter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($unparsedContent->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($unparsedContent->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
<h1>
    Hello
</h1>
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($unparsedContent->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

<h1>
    Hello
</h1>
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($unparsedContent->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsAlmostEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
----
--
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($unparsedContent->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(
                <<<'TXT'

<h1>
    Hello
</h1>

TXT
            ),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---


---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---


---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseThrowsFrontMatterCanNotBeParsedWhenFrontMatterCanNotBeParsed(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterCanNotBeParsed::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsFalse(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
false
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsTrue(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
true
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsString(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsInt(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
123
---
TXT
        );

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
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
- "foo"
- "bar"
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($unparsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsNonEmptyFrontMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---
TXT
        );

        $frontMatter = FrontMatter::fromArray([
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quz',
            ],
        ]);

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        self::assertEquals($frontMatter, $parsedContent->frontMatter());
        self::assertEquals(BodyMatter::fromString(''), $parsedContent->bodyMatter());
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---
foo: bar
baz:
    - qux
    - quz
---


TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
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

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasNonEmptyFrontMatterAndBodyMatter(): void
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

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
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

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([
                'foo' => 'bar',
                'baz' => [
                    'qux',
                    'quz',
                ],
            ]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
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

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
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

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenUnparsedContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $unparsedContent = UnparsedContent::fromString(
            <<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($unparsedContent);

        $expected = ParsedContent::create(
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

        self::assertEquals($expected, $parsedContent);
    }
}
