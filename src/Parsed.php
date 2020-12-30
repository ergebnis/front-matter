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

namespace Ergebnis\FrontMatter;

final class Parsed
{
    private $frontMatter;

    private $content;

    private function __construct(array $frontMatter, string $content)
    {
        $this->frontMatter = $frontMatter;
        $this->content = $content;
    }

    public static function fromFrontMatterAndContent(array $frontMatter, string $content): self
    {
        return new self(
            $frontMatter,
            $content
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
