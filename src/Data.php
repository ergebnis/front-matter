<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter;

/**
 * @psalm-immutable
 */
final class Data
{
    /**
     * @param array<string, mixed> $value
     */
    private function __construct(private readonly array $value)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param array<string, mixed> $value
     *
     * @throws Exception\FrontMatterHasInvalidKeys
     */
    public static function fromArray(array $value): self
    {
        $invalidKeys = \array_filter(\array_keys($value), static function ($key): bool {
            return !\is_string($key);
        });

        if ([] !== $invalidKeys) {
            throw Exception\FrontMatterHasInvalidKeys::create();
        }

        return new self($value);
    }

    public function has(string $key): bool
    {
        if (!\str_contains($key, '.')) {
            return \array_key_exists(
                $key,
                $this->value,
            );
        }

        /** @var array<int, string> $parts */
        $parts = \explode(
            '.',
            $key,
        );

        $count = \count($parts);
        $value = $this->value;

        for ($i = 0; $i < $count; ++$i) {
            $part = $parts[$i];

            if (!\array_key_exists($part, $value)) {
                return false;
            }

            $value = $value[$part];

            if (
                $count - 1 > $i
                && !\is_array($value)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws Exception\DataDoesNotHaveKey
     */
    public function get(string $key): mixed
    {
        if (!\str_contains($key, '.')) {
            if (!\array_key_exists($key, $this->value)) {
                throw Exception\DataDoesNotHaveKey::named($key);
            }

            return $this->value[$key];
        }

        /** @var array<int, string> $parts */
        $parts = \explode(
            '.',
            $key,
        );

        $count = \count($parts);
        $value = $this->value;

        for ($i = 0; $i < $count; ++$i) {
            $part = $parts[$i];

            if (!\array_key_exists($part, $value)) {
                throw Exception\DataDoesNotHaveKey::named($key);
            }

            $value = $value[$part];

            if (
                $count - 1 > $i
                && !\is_array($value)
            ) {
                throw Exception\DataDoesNotHaveKey::named($key);
            }
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->value;
    }
}
