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

use Ergebnis\DataProvider;
use Ergebnis\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter\Content::class)]
final class ContentTest extends Framework\TestCase
{
    public function testEmptyReturnsContent(): void
    {
        $content = FrontMatter\Content::empty();

        self::assertSame('', $content->toString());
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testFromStringReturnsContent(string $value): void
    {
        $content = FrontMatter\Content::fromString($value);

        self::assertSame($value, $content->toString());
    }
}
