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

use Ergebnis\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter\FrontMatter::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Data::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Content::class)]
final class FrontMatterTest extends Framework\TestCase
{
    public function testEmptyReturnsFrontMatter(): void
    {
        $frontMatter = FrontMatter\FrontMatter::empty();

        self::assertEquals(FrontMatter\Data::empty(), $frontMatter->data());
        self::assertEquals(FrontMatter\Content::empty(), $frontMatter->content());
    }

    public function testCreateReturnsFrontMatter(): void
    {
        $content = FrontMatter\Content::fromString(<<<'TXT'
---
foo: "bar"
bar:
  - "baz"
---
TXT);

        $data = FrontMatter\Data::fromArray([
            'bar' => [
                'baz',
            ],
            'foo' => 'bar',
        ]);

        $frontMatter = FrontMatter\FrontMatter::create(
            $content,
            $data,
        );

        self::assertSame($data, $frontMatter->data());
        self::assertSame($content, $frontMatter->content());
    }
}
