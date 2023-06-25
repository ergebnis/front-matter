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
use Ergebnis\FrontMatter\Content;
use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\FrontMatter;
use Ergebnis\FrontMatter\Parsed;
use Ergebnis\FrontMatter\Test;
use Ergebnis\FrontMatter\YamlParser;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(YamlParser::class)]
#[Framework\Attributes\UsesClass(Content::class)]
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

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiterWithContent(): void
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

    public function testHasFrontMatterReturnsFalseWhenValueIsFrontMatterDelimiterWithTrailingWhitespaceAndContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterAndBlankContent(): void
    {
        $value = <<<'TXT'
---
---

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterAndContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterWithWhitespaceAndBlankContent(): void
    {
        $value = <<<'TXT'
---


---

TXT;

        $parser = new YamlParser();

        self::assertTrue($parser->hasFrontMatter($value));
    }

    public function testHasFrontMatterReturnsTrueWhenValueHasEmptyFrontMatterWithWhitespaceAndContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterAndBlankContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterAndContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBlankContent(): void
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

    public function testHasFrontMatterReturnsTrueWhenValueHasNonEmptyFrontMatterWithWhitespaceAndContent(): void
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
        self::assertEquals(Content::fromString($value), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithTrailingWhitespace(): void
    {
        $value = <<<'TXT'
---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(Content::fromString($value), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithContent(): void
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
        self::assertEquals(Content::fromString($value), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueIsFrontMatterDelimiterWithTrailingWhitespaceAndContent(): void
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
        self::assertEquals(Content::fromString($value), $parsed->content());
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
        self::assertEquals(Content::fromString($value), $parsed->content());
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
        self::assertEquals(Content::fromString(''), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterAndBlankContent(): void
    {
        $value = <<<'TXT'
---
---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(Content::fromString(''), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterAndContent(): void
    {
        $value = <<<'TXT'
---
---

<h1>
    Hello
</h1>

TXT;

        $content = Content::fromString(
            <<<'TXT'

<h1>
    Hello
</h1>

TXT
        );

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
    }

    public function testParseReturnsParsedWhenValueIsEmptyFrontMatterWithWhitespace(): void
    {
        $value = <<<'TXT'
---


---
TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(Content::fromString(''), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterWithWhitespaceAndBlankContent(): void
    {
        $value = <<<'TXT'
---


---

TXT;

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals(Content::fromString(''), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasEmptyFrontMatterWithWhitespaceAndContent(): void
    {
        $value = <<<'TXT'
---


---

<h1>
    Hello
</h1>

TXT;

        $content = Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals(FrontMatter::fromArray([]), $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
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
        self::assertEquals(Content::fromString(''), $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterAndBlankContent(): void
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

        $content = Content::fromString(<<<'TXT'


TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterAndContent(): void
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

        $frontMatter = FrontMatter::fromArray([
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quz',
            ],
        ]);

        $content = Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
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

        $frontMatter = FrontMatter::fromArray([
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quz',
            ],
        ]);

        $content = Content::fromString('');

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterWithWhitespaceAndBlankContent(): void
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

        $content = Content::fromString(<<<'TXT'


TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
    }

    public function testParseReturnsParsedWhenValueHasNonEmptyFrontMatterWithWhitespaceAndContent(): void
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

        $frontMatter = FrontMatter::fromArray([
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quz',
            ],
        ]);

        $content = Content::fromString(<<<'TXT'

<h1>
    Hello
</h1>

TXT);

        $parser = new YamlParser();

        $parsed = $parser->parse($value);

        self::assertEquals($frontMatter, $parsed->frontMatter());
        self::assertEquals($content, $parsed->content());
    }
}
