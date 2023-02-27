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
use Ergebnis\FrontMatter\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\FrontMatter\Exception\FrontMatterDoesNotHaveKey
 */
final class FrontMatterDoesNotHaveKeyTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNamedReturnsException(): void
    {
        $key = self::faker()->word();

        $exception = Exception\FrontMatterDoesNotHaveKey::named($key);

        $message = \sprintf(
            'Front matter does not have a key named "%s".',
            $key,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
