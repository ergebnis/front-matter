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
final class ParsedContent
{
    private function __construct(
        private readonly FrontMatter $frontMatter,
        private readonly BodyMatter $bodyMatter,
    ) {
    }

    public static function create(
        FrontMatter $frontMatter,
        BodyMatter $bodyMatter,
    ): self {
        return new self(
            $frontMatter,
            $bodyMatter,
        );
    }

    public function frontMatter(): FrontMatter
    {
        return $this->frontMatter;
    }

    public function bodyMatter(): BodyMatter
    {
        return $this->bodyMatter;
    }
}
