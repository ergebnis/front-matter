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

namespace Ergebnis\FrontMatter\Test\Unit\Exception;

use Ergebnis\FrontMatter\Exception;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\FrontMatterCanNotBeParsed::class)]
final class FrontMatterCanNotBeParsedTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = Exception\FrontMatterCanNotBeParsed::create();

        self::assertSame('Front matter can not be parsed.', $exception->getMessage());
    }
}
