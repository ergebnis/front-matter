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
use Ergebnis\FrontMatter\Content;
use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\FrontMatter;
use Ergebnis\FrontMatter\ParsedContent;
use Ergebnis\FrontMatter\Test;
use Ergebnis\FrontMatter\YamlParser;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(YamlParser::class)]
#[Framework\Attributes\UsesClass(BodyMatter::class)]
#[Framework\Attributes\UsesClass(Content::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterCanNotBeParsed::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterHasInvalidKeys::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterIsNotAnObject::class)]
#[Framework\Attributes\UsesClass(FrontMatter::class)]
#[Framework\Attributes\UsesClass(ParsedContent::class)]
final class YamlParserTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testHasFrontMatterReturnsFalseWhenContentDoesNotHaveFrontMatter(string $value): void
    {
        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter(Content::fromString($value)));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---

TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
<h1>
    Hello
</h1>
TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
---

<h1>
    Hello
</h1>
TXT);

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsFalseWhenContentIsAlmostEmptyFrontMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
----
--
TXT
        );

        $parser = new YamlParser();

        self::assertFalse($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsEmptyFrontMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
---

TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---


---
TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---


---

TXT
        );

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsNonEmptyFrontMatter(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testHasFrontMatterReturnsTrueWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(
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

        self::assertTrue($parser->hasFrontMatter($content));
    }

    public function testParseReturnsParsedContentWhenContentIsFrontMatterDelimiter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($content->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($content->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentIsFrontMatterDelimiterWithBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
            <<<'TXT'
---
<h1>
    Hello
</h1>
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($content->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentIsFrontMatterDelimiterWithTrailingWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---

<h1>
    Hello
</h1>
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($content->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentIsAlmostEmptyFrontMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
----
--
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString($content->toString()),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentIsEmptyFrontMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentHasEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentHasEmptyFrontMatterAndBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

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

    public function testParseReturnsParsedContentWhenContentIsEmptyFrontMatterWithWhitespace(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---


---
TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentHasEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---


---

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

        $expected = ParsedContent::create(
            FrontMatter::fromArray([]),
            BodyMatter::fromString(''),
        );

        self::assertEquals($expected, $parsedContent);
    }

    public function testParseReturnsParsedContentWhenContentHasEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

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
        $content = Content::fromString(
            <<<'TXT'
---
foo: bar
baz
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterCanNotBeParsed::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsFalse(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
false
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsTrue(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
true
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsString(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
foo
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsInt(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
123
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsInvalidFrontMatterWhenFrontMatterIsFloat(): void
    {
        $content = Content::fromString(<<<'TXT'
---
3.14
---
TXT);

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseThrowsFrontMatterIsNotAnObjectWhenFrontMatterIsArray(): void
    {
        $content = Content::fromString(
            <<<'TXT'
---
- "foo"
- "bar"
---
TXT
        );

        $parser = new YamlParser();

        $this->expectException(Exception\FrontMatterIsNotAnObject::class);

        $parser->parse($content);
    }

    public function testParseReturnsParsedContentWhenContentIsNonEmptyFrontMatter(): void
    {
        $content = Content::fromString(
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

        $parsedContent = $parser->parse($content);

        self::assertEquals($frontMatter, $parsedContent->frontMatter());
        self::assertEquals(BodyMatter::fromString(''), $parsedContent->bodyMatter());
    }

    public function testParseReturnsParsedContentWhenContentHasNonEmptyFrontMatterAndBlankBodyMatter(): void
    {
        $content = Content::fromString(
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

        $parsedContent = $parser->parse($content);

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

    public function testParseReturnsParsedContentWhenContentHasNonEmptyFrontMatterAndBodyMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
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

        $parsedContent = $parser->parse($content);

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

    public function testParseReturnsParsedContentWhenContentIsNonEmptyFrontMatterWithWhitespace(): void
    {
        $content = Content::fromString(
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

        $parsedContent = $parser->parse($content);

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

    public function testParseReturnsParsedContentWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBlankBodyMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
---

foo: bar
baz:
    - qux
    - quz

---


TXT);

        $parser = new YamlParser();

        $parsedContent = $parser->parse($content);

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

    public function testParseReturnsParsedContentWhenContentHasNonEmptyFrontMatterWithWhitespaceAndBodyMatter(): void
    {
        $content = Content::fromString(
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

        $parsedContent = $parser->parse($content);

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
