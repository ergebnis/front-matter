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

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\FrontMatter\Content;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Content::class)]
final class ContentTest extends Framework\TestCase
{
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testFromStringReturnsContent(string $value): void
    {
        $content = Content::fromString($value);

        self::assertSame($value, $content->toString());
    }
}
