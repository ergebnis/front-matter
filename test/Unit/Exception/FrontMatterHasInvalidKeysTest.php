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

namespace Ergebnis\FrontMatter\Test\Unit\Exception;

use Ergebnis\FrontMatter\Exception;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Exception\FrontMatterHasInvalidKeys
 */
final class FrontMatterHasInvalidKeysTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = Exception\FrontMatterHasInvalidKeys::create();

        self::assertSame('Front matter contains keys that are not string.', $exception->getMessage());
    }
}
