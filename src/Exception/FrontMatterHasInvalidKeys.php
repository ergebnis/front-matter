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

namespace Ergebnis\FrontMatter\Exception;

final class FrontMatterHasInvalidKeys extends \InvalidArgumentException
{
    public static function create(): self
    {
        return new self('Front matter contains keys that are not string.');
    }
}
