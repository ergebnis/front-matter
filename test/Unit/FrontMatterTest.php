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

use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\FrontMatter;
use Ergebnis\FrontMatter\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\FrontMatter
 *
 * @uses \Ergebnis\FrontMatter\Exception\FrontMatterDoesNotHaveKey
 * @uses \Ergebnis\FrontMatter\Exception\FrontMatterHasInvalidKeys
 */
final class FrontMatterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromArrayRejectsArrayWhereKeysAreNumeric(): void
    {
        $value = self::faker()->words();

        $this->expectException(Exception\FrontMatterHasInvalidKeys::class);

        FrontMatter::fromArray($value);
    }

    public function testFromArrayReturnsFrontMatter(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $frontMatter = FrontMatter::fromArray($value);

        self::assertSame($value, $frontMatter->toArray());
    }

    public function testHasReturnsFalseWhenFrontMatterIsEmpty(): void
    {
        $key = self::faker()->word();

        $frontMatter = FrontMatter::fromArray([]);

        self::assertFalse($frontMatter->has($key));
    }

    public function testHasReturnsFalseWhenFrontMatterDoesNotHaveKey(): void
    {
        $faker = self::faker();

        $key = $faker->word();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $frontMatter = FrontMatter::fromArray($value);

        self::assertFalse($frontMatter->has($key));
    }

    public function testHasReturnsTrueWhenFrontMatterHasKey(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $key = $faker->randomElement(\array_keys($value));

        $frontMatter = FrontMatter::fromArray($value);

        self::assertTrue($frontMatter->has($key));
    }

    public function testGetThrowsFrontMatterDoesNotHaveKeyExceptionWhenFrontMatterIsEmpty(): void
    {
        $key = self::faker()->word();

        $frontMatter = FrontMatter::fromArray([]);

        $this->expectException(Exception\FrontMatterDoesNotHaveKey::class);

        $frontMatter->get($key);
    }

    public function testGetThrowsFrontMatterDoesNotHaveValueExceptionWhenFrontMatterDoesNotHaveKey(): void
    {
        $faker = self::faker();

        $key = $faker->word();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $frontMatter = FrontMatter::fromArray($value);

        $this->expectException(Exception\FrontMatterDoesNotHaveKey::class);

        $frontMatter->get($key);
    }

    public function testGetReturnsValueWhenFrontMatterHasKey(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $key = $faker->randomElement(\array_keys($value));

        $frontMatter = FrontMatter::fromArray($value);

        self::assertSame($value[$key], $frontMatter->get($key));
    }
}
