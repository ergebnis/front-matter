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

use Ergebnis\FrontMatter\Data;
use Ergebnis\FrontMatter\Exception;
use Ergebnis\FrontMatter\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Data::class)]
#[Framework\Attributes\UsesClass(Exception\DataDoesNotHaveKey::class)]
#[Framework\Attributes\UsesClass(Exception\FrontMatterHasInvalidKeys::class)]
final class DataTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testEmptyReturnsData(): void
    {
        $data = Data::empty();

        self::assertSame([], $data->toArray());
    }

    public function testFromArrayRejectsArrayWhereKeysAreNumeric(): void
    {
        $value = self::faker()->words();

        $this->expectException(Exception\FrontMatterHasInvalidKeys::class);

        Data::fromArray($value);
    }

    public function testFromArrayReturnsFrontMatter(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $data = Data::fromArray($value);

        self::assertSame($value, $data->toArray());
    }

    public function testHasReturnsFalseWhenDataIsEmpty(): void
    {
        $key = self::faker()->word();

        $data = Data::fromArray([]);

        self::assertFalse($data->has($key));
    }

    public function testHasReturnsFalseWhenDataDoesNotHaveKey(): void
    {
        $faker = self::faker();

        $key = $faker->word();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $data = Data::fromArray($value);

        self::assertFalse($data->has($key));
    }

    public function testHasReturnsTrueWhenDataHasKey(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $key = $faker->randomElement(\array_keys($value));

        $data = Data::fromArray($value);

        self::assertTrue($data->has($key));
    }

    #[Framework\Attributes\DataProvider('provideDataWhereValueIsMissingWhenKeyUsesDotNotation')]
    public function testHasReturnsFalseWhenDataDoesNotHaveValueWhenKeyUsesDotNotation(array $value): void
    {
        $data = Data::fromArray($value);

        self::assertFalse($data->has('head.meta.author'));
    }

    /**
     * @return \Generator<string, array{0: array}>
     */
    public static function provideDataWhereValueIsMissingWhenKeyUsesDotNotation(): iterable
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

    public function testHasReturnsTrueWhenDataHasFullyExplodedPartsAndKeyUsesDotNotation(): void
    {
        $value = [
            'head' => [
                'meta' => [
                    'author' => self::faker()->name(),
                ],
            ],
        ];

        $data = Data::fromArray($value);

        self::assertTrue($data->has('head.meta.author'));
    }

    public function testGetThrowsDataDoesNotHaveKeyExceptionWhenDataIsEmpty(): void
    {
        $key = self::faker()->word();

        $data = Data::fromArray([]);

        $this->expectException(Exception\DataDoesNotHaveKey::class);

        $data->get($key);
    }

    public function testGetThrowsFrontMatterDoesNotHaveValueExceptionWhenDataDoesNotHaveKey(): void
    {
        $faker = self::faker();

        $key = $faker->word();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $data = Data::fromArray($value);

        $this->expectException(Exception\DataDoesNotHaveKey::class);

        $data->get($key);
    }

    public function testGetReturnsValueWhenDataHasKey(): void
    {
        $faker = self::faker();

        $value = [
            'layout' => $faker->word(),
            'title' => $faker->sentence(),
        ];

        $key = $faker->randomElement(\array_keys($value));

        $data = Data::fromArray($value);

        self::assertSame($value[$key], $data->get($key));
    }

    #[Framework\Attributes\DataProvider('provideDataWhereValueIsMissingWhenKeyUsesDotNotation')]
    public function testGetThrowsFrontMatterDoesNotHaveValueExceptionWhenDataDoesNotHaveValueAndKeyUsesDotNotation(array $value): void
    {
        $data = Data::fromArray($value);

        $this->expectException(Exception\DataDoesNotHaveKey::class);

        $data->get('head.meta.author');
    }

    public function testGetReturnsValueWhenDataHasFullyExplodedPartsAndKeyUsesDotNotation(): void
    {
        $author = self::faker()->name();

        $value = [
            'head' => [
                'meta' => [
                    'author' => $author,
                ],
            ],
        ];

        $data = Data::fromArray($value);

        self::assertSame($author, $data->get('head.meta.author'));
    }
}
