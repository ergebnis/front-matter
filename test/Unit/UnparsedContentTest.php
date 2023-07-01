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

use Ergebnis\DataProvider;
use Ergebnis\FrontMatter\UnparsedContent;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(UnparsedContent::class)]
final class UnparsedContentTest extends Framework\TestCase
{
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'arbitrary')]
    public function testFromStringReturnsUnparsedContent(string $value): void
    {
        $unparsedContent = UnparsedContent::fromString($value);

        self::assertSame($value, $unparsedContent->toString());
    }
}
