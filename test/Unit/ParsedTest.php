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

use Ergebnis\FrontMatter\Content;
use Ergebnis\FrontMatter\FrontMatter;
use Ergebnis\FrontMatter\Parsed;
use Ergebnis\FrontMatter\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Parsed::class)]
#[Framework\Attributes\UsesClass(Content::class)]
#[Framework\Attributes\UsesClass(FrontMatter::class)]
final class ParsedTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsParsed(): void
    {
        $faker = self::faker();

        $frontMatter = FrontMatter::fromArray([
            'foo' => $faker->words(),
            'bar' => $faker->randomFloat(),
            'baz' => $faker->randomNumber(),
        ]);

        $content = Content::fromString($faker->realText());

        $parsed = Parsed::create(
            $frontMatter,
            $content,
        );

        self::assertSame($frontMatter, $parsed->frontMatter());
        self::assertSame($content, $parsed->content());
    }
}
