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

#[Framework\Attributes\CoversClass(FrontMatter\Parsed::class)]
#[Framework\Attributes\UsesClass(FrontMatter\BodyMatter::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Content::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Data::class)]
#[Framework\Attributes\UsesClass(FrontMatter\FrontMatter::class)]
final class ParsedTest extends Framework\TestCase
{
    use FrontMatter\Test\Util\Helper;

    public function testCreateReturnsParsed(): void
    {
        $faker = self::faker();

        $frontMatter = FrontMatter\FrontMatter::create(
            FrontMatter\Content::fromString(<<<'TXT'
---
foo: "bar"
bar:
  - "baz"
---
TXT),
            FrontMatter\Data::fromArray([
                'bar' => [
                    'baz',
                ],
                'foo' => 'bar',
            ]),
        );

        $bodyMatter = FrontMatter\BodyMatter::create(FrontMatter\Content::fromString($faker->realText()));

        $parsed = FrontMatter\Parsed::create(
            $frontMatter,
            $bodyMatter,
        );

        self::assertSame($frontMatter, $parsed->frontMatter());
        self::assertSame($bodyMatter, $parsed->bodyMatter());
    }
}
