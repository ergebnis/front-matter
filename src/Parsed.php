<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter;

final class Parsed
{
    private function __construct(
        private array $frontMatter,
        private string $content,
    ) {
    }

    public static function fromFrontMatterAndContent(
        array $frontMatter,
        string $content,
    ): self {
        return new self(
            $frontMatter,
            $content,
        );
    }

    public function frontMatter(): array
    {
        return $this->frontMatter;
    }

    public function content(): string
    {
        return $this->content;
    }
}
