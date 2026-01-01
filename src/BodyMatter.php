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

namespace Ergebnis\FrontMatter;

/**
 * @psalm-immutable
 */
final class BodyMatter
{
    private function __construct(private readonly Content $content)
    {
    }

    public static function create(Content $content): self
    {
        return new self($content);
    }

    public function content(): Content
    {
        return $this->content;
    }
}
