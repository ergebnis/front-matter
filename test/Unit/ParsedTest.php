<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\FrontMatter\Parsed;
use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Parsed
 *
 * @uses \Ergebnis\FrontMatter\Parsed\Content
 * @uses \Ergebnis\FrontMatter\Parsed\FrontMatter
 */
final class ParsedTest extends Framework\TestCase
{
    use Util\Helper;

    public function testFromFrontMatterAndContentReturnsParsed(): void
    {
        $faker = self::faker();

        $frontMatter = Parsed\FrontMatter::fromArray([
            'foo' => $faker->words,
            'bar' => $faker->randomFloat(),
            'baz' => $faker->randomNumber(),
        ]);

        $content = Parsed\Content::fromString($faker->realText());

        $parsed = Parsed::fromFrontMatterAndContent(
            $frontMatter,
            $content
        );

        self::assertSame($frontMatter, $parsed->frontMatter());
        self::assertSame($content, $parsed->content());
    }
}
