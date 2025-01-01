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

namespace Ergebnis\FrontMatter;

/**
 * @psalm-immutable
 */
final class FrontMatter
{
    private function __construct(
        private readonly Content $content,
        private readonly Data $data,
    ) {
    }

    public static function empty(): self
    {
        return new self(
            Content::empty(),
            Data::empty(),
        );
    }

    public static function create(
        Content $content,
        Data $data,
    ): self {
        return new self(
            $content,
            $data,
        );
    }

    public function content(): Content
    {
        return $this->content;
    }

    public function data(): Data
    {
        return $this->data;
    }
}
