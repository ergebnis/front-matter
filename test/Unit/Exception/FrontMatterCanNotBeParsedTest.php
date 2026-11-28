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

namespace Ergebnis\FrontMatter\Test\Unit\Exception;

use Ergebnis\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter\Exception\FrontMatterCanNotBeParsed::class)]
final class FrontMatterCanNotBeParsedTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = FrontMatter\Exception\FrontMatterCanNotBeParsed::create();

        self::assertSame('Front matter can not be parsed.', $exception->getMessage());
    }
}
