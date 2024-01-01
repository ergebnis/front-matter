<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter;

use Ergebnis\FrontMatter\Exception\FrontMatterHasInvalidKeys;
use Symfony\Component\Yaml;

final class YamlParser implements Parser
{
    private const PATTERN = "{^(?P<frontMatterWithDelimiters>(?:---)[\r\n|\n]*(?P<frontMatterWithoutDelimiters>.*?)[\r\n|\n]+(?:---)[\r\n|\n]{0,1})(?P<bodyMatter>.*)$}s";

    public function hasFrontMatter(Content $content): bool
    {
        return 1 === \preg_match(self::PATTERN, $content->toString());
    }

    public function parse(Content $content): Parsed
    {
        if (1 !== \preg_match(self::PATTERN, $content->toString(), $matches)) {
            return Parsed::create(
                FrontMatter::empty(),
                BodyMatter::create($content),
            );
        }

        $bodyMatter = BodyMatter::create(Content::fromString($matches['bodyMatter']));

        if ('' === $matches['frontMatterWithoutDelimiters']) {
            return Parsed::create(
                FrontMatter::create(
                    Content::fromString($matches['frontMatterWithDelimiters']),
                    Data::empty(),
                ),
                $bodyMatter,
            );
        }

        try {
            $data = Yaml\Yaml::parse($matches['frontMatterWithoutDelimiters']);
        } catch (Yaml\Exception\ParseException) {
            throw Exception\FrontMatterCanNotBeParsed::create();
        }

        if (!\is_array($data)) {
            throw Exception\FrontMatterIsNotAnObject::create();
        }

        try {
            $frontMatter = FrontMatter::create(
                Content::fromString($matches['frontMatterWithDelimiters']),
                Data::fromArray($data),
            );
        } catch (FrontMatterHasInvalidKeys) {
            throw Exception\FrontMatterIsNotAnObject::create();
        }

        return Parsed::create(
            $frontMatter,
            $bodyMatter,
        );
    }
}
