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

namespace Ergebnis\FrontMatter\Test\Unit;

use Ergebnis\FrontMatter;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FrontMatter\BodyMatter::class)]
#[Framework\Attributes\UsesClass(FrontMatter\Content::class)]
final class BodyMatterTest extends Framework\TestCase
{
    use FrontMatter\Test\Util\Helper;

    public function testCreateReturnsBodyMatter(): void
    {
        $content = FrontMatter\Content::fromString(self::faker()->realText());

        $bodyMatter = FrontMatter\BodyMatter::create($content);

        self::assertSame($content, $bodyMatter->content());
    }
}
