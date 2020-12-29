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

namespace Ergebnis\FrontMatter\Parsed;

use Ergebnis\FrontMatter\Exception;

final class FrontMatter
{
    /**
     * @var array<string, mixed>
     */
    private $value;

    /**
     * @param array<string, mixed> $value
     */
    private function __construct(array $value)
    {
        $this->value = $value;
    }

    /**
     * @param array<string, mixed> $value
     *
     * @throws Exception\InvalidFrontMatter
     */
    public static function fromArray(array $value): self
    {
        $keys = \array_keys($value);

        $invalidKeys = \array_filter($keys, static function ($key): bool {
            return !\is_string($key);
        });

        if ([] !== $invalidKeys) {
            throw Exception\InvalidFrontMatter::keysCanNotBeNumeric();
        }

        return new self($value);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->value;
    }
}
