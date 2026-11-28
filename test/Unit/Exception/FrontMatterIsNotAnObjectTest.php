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

#[Framework\Attributes\CoversClass(FrontMatter\Exception\FrontMatterIsNotAnObject::class)]
final class FrontMatterIsNotAnObjectTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = FrontMatter\Exception\FrontMatterIsNotAnObject::create();

        self::assertSame('Front matter is not an object.', $exception->getMessage());
    }
}
