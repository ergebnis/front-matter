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
final class Parsed
{
    private function __construct(
        private readonly FrontMatter $frontMatter,
        private readonly Content $content,
    ) {
    }

    public static function fromFrontMatterAndContent(
        FrontMatter $frontMatter,
        Content $content,
    ): self {
        return new self(
            $frontMatter,
            $content,
        );
    }

    public function frontMatter(): FrontMatter
    {
        return $this->frontMatter;
    }

    public function content(): Content
    {
        return $this->content;
    }
}
