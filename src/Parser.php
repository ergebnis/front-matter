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

interface Parser
{
    public function hasFrontMatter(string $value): bool;

    /**
     * @throws Exception\InvalidFrontMatter
     */
    public function parse(string $value): Parsed;
}
