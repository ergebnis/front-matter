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

namespace Ergebnis\FrontMatter\Test\Unit\Parsed;

use Ergebnis\FrontMatter\Parsed\Content;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Parsed\Content
 */
final class ContentTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::arbitrary()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::blank()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::empty()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::trimmed()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::untrimmed()
     */
    public function testFromStringReturnsContent(string $value): void
    {
        $content = Content::fromString($value);

        self::assertSame($value, $content->toString());
    }
}
