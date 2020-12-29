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

namespace Ergebnis\FrontMatter\Test\Unit\Exception;

use Ergebnis\FrontMatter\Exception\InvalidFrontMatter;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Exception\InvalidFrontMatter
 */
final class InvalidFrontMatterTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = InvalidFrontMatter::create();

        self::assertSame('Front matter is invalid.', $exception->getMessage());
    }

    public function testKeysCanNotBeNumericReturnsException(): void
    {
        $exception = InvalidFrontMatter::keysCanNotBeNumeric();

        self::assertSame('The keys of front matter data can not be numeric.', $exception->getMessage());
    }
}
