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

#[Framework\Attributes\CoversClass(FrontMatter::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterDoesNotHaveKey::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterHasInvalidKeys::class)]
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

    #[Framework\Attributes\DataProvider('provideFrontMatterWhereValueIsMissingWhenKeyUsesDotNotation')]
    public function testHasReturnsFalseWhenFrontMatterDoesNotHaveValueWhenKeyUsesDotNotation(array $value): void
    {
        $frontMatter = FrontMatter::fromArray($value);

        self::assertFalse($frontMatter->has('head.meta.author'));
    }

    /**
     * @return \Generator<string, array{0: array}>
     */
    public static function provideFrontMatterWhereValueIsMissingWhenKeyUsesDotNotation(): \Generator
    {
        $faker = self::faker();

        $values = [
            'head-not-present' => [
                'other' => $faker->word(),
            ],
            'head-present-but-value-is-string' => [
                'head' => $faker->word(),
            ],
            'head-present-but-value-is-array-where-keys-are-integers' => [
                'head' => $faker->words(),
            ],
            'head.meta-present-but-value-is-string' => [
                'head' => [
                    'meta' => $faker->word(),
                ],
            ],
            'head.meta-present-but-value-is-array-where-keys-are-integers' => [
                'head' => [
                    'meta' => $faker->words(),
                ],
            ],
            'head.meta-present-as-key' => [
                'head.meta' => [
                    'author' => $faker->word(),
                ],
            ],
            'head.meta.author-present-as-key' => [
                'head.meta.author' => $faker->word(),
            ],
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testHasReturnsTrueWhenFrontMatterHasFullyExplodedPartsAndKeyUsesDotNotation(): void
    {
        $faker = self::faker();

        $value = [
            'head' => [
                'meta' => [
                    'author' => $faker->name(),
                ],
            ],
        ];

        $frontMatter = FrontMatter::fromArray($value);

        self::assertTrue($frontMatter->has('head.meta.author'));
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

    #[Framework\Attributes\DataProvider('provideFrontMatterWhereValueIsMissingWhenKeyUsesDotNotation')]
    public function testGetThrowsFrontMatterDoesNotHaveValueExceptionWhenFrontMatterDoesNotHaveValueAndKeyUsesDotNotation(array $value): void
    {
        $frontMatter = FrontMatter::fromArray($value);

        $this->expectException(Exception\FrontMatterDoesNotHaveKey::class);

        $frontMatter->get('head.meta.author');
    }

    public function testGetReturnsValueWhenFrontMatterHasFullyExplodedPartsAndKeyUsesDotNotation(): void
    {
        $author = self::faker()->name();

        $value = [
            'head' => [
                'meta' => [
                    'author' => $author,
                ],
            ],
        ];

        $frontMatter = FrontMatter::fromArray($value);

        self::assertSame($author, $frontMatter->get('head.meta.author'));
    }
}
