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

namespace Ergebnis\FrontMatter;

/**
 * @psalm-immutable
 */
final class FrontMatter
{
    /**
     * @param array<string, mixed> $value
     */
    private function __construct(private array $value)
    {
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
        return \array_key_exists($key, $this->value);
    }

    /**
     * @throws Exception\FrontMatterDoesNotHaveKey
     */
    public function get(string $key): mixed
    {
        if (!\array_key_exists($key, $this->value)) {
            throw Exception\FrontMatterDoesNotHaveKey::named($key);
        }

        return $this->value[$key];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->value;
    }
}
