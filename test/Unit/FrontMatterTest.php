<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\FrontMatter\Content;
use Ergebnis\FrontMatter\Data;
use Ergebnis\FrontMatter\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter::class)]
#[Framework\Attributes\UsesClass(Data::class)]
#[Framework\Attributes\UsesClass(Content::class)]
final class FrontMatterTest extends Framework\TestCase
{
    public function testEmptyReturnsFrontMatter(): void
    {
        $frontMatter = FrontMatter::empty();

        self::assertEquals(Data::empty(), $frontMatter->data());
        self::assertEquals(Content::empty(), $frontMatter->content());
    }

    public function testCreateReturnsFrontMatter(): void
    {
        $content = Content::fromString(<<<'TXT'
---
foo: "bar"
bar:
  - "baz"
---
TXT);

        $data = Data::fromArray([
            'foo' => 'bar',
            'bar' => [
                'baz',
            ],
        ]);

        $frontMatter = FrontMatter::create(
            $content,
            $data,
        );

        self::assertSame($data, $frontMatter->data());
        self::assertSame($content, $frontMatter->content());
    }
}
