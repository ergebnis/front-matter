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

#[Framework\Attributes\CoversClass(FrontMatter\Exception\DataDoesNotHaveKey::class)]
final class DataDoesNotHaveKeyTest extends Framework\TestCase
{
    use FrontMatter\Test\Util\Helper;

    public function testNamedReturnsException(): void
    {
        $key = self::faker()->word();

        $exception = FrontMatter\Exception\DataDoesNotHaveKey::named($key);

        $message = \sprintf(
            'Data does not have a key named "%s".',
            $key,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
