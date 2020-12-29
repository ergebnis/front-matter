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

namespace Ergebnis\FrontMatter\Test\Unit\Parsed;

use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\Parsed\FrontMatter;
use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Parsed\FrontMatter
 *
 * @uses \Ergebnis\FrontMatter\Exception\InvalidFrontMatter
 */
final class FrontMatterTest extends Framework\TestCase
{
    use Util\Helper;

    public function testFromArrayRejectsArrayWhereKeysAreNumeric(): void
    {
        $faker = self::faker();

        $value = [
            $faker->word,
            $faker->word,
            $faker->word,
        ];

        $this->expectException(Exception\InvalidFrontMatter::class);

        FrontMatter::fromArray($value);
    }

    public function testFromArrayReturnsFrontMatter(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word,
            'title' => $faker->sentence,
        ];

        $frontMatter = FrontMatter::fromArray($value);

        self::assertSame($value, $frontMatter->toArray());
    }
}
