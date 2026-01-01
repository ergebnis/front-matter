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

use Ergebnis\FrontMatter\BodyMatter;
use Ergebnis\FrontMatter\Content;
use Ergebnis\FrontMatter\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(BodyMatter::class)]
#[Framework\Attributes\UsesClass(Content::class)]
final class BodyMatterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsBodyMatter(): void
    {
        $content = Content::fromString(self::faker()->realText());

        $bodyMatter = BodyMatter::create($content);

        self::assertSame($content, $bodyMatter->content());
    }
}
