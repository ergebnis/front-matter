<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter\Exception;

final class DataDoesNotHaveKey extends \InvalidArgumentException
{
    public static function named(string $key): self
    {
        return new self(\sprintf(
            'Data does not have a key named "%s".',
            $key,
        ));
    }
}
