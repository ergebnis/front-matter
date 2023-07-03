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

namespace Ergebnis\FrontMatter;

use Ergebnis\FrontMatter\Exception\FrontMatterHasInvalidKeys;
use Symfony\Component\Yaml;

final class YamlParser implements Parser
{
    private const PATTERN = "{^(?:---)[\r\n|\n]*(?P<frontMatter>.*?)[\r\n|\n]+(?:---)[\r\n|\n]{0,1}(?P<bodyMatter>.*)$}s";

    public function hasFrontMatter(UnparsedContent $content): bool
    {
        return 1 === \preg_match(self::PATTERN, $content->toString());
    }

    public function parse(UnparsedContent $unparsedContent): ParsedContent
    {
        if (1 !== \preg_match(self::PATTERN, $unparsedContent->toString(), $matches)) {
            return ParsedContent::create(
                FrontMatter::fromArray([]),
                BodyMatter::fromString($unparsedContent->toString()),
            );
        }

        $bodyMatter = BodyMatter::fromString($matches['bodyMatter']);

        $rawFrontMatter = $matches['frontMatter'];

        if ('' === $rawFrontMatter) {
            return ParsedContent::create(
                FrontMatter::fromArray([]),
                $bodyMatter,
            );
        }

        try {
            $data = Yaml\Yaml::parse($rawFrontMatter);
        } catch (Yaml\Exception\ParseException) {
            throw Exception\FrontMatterCanNotBeParsed::create();
        }

        if (!\is_array($data)) {
            throw Exception\FrontMatterIsNotAnObject::create();
        }

        try {
            $frontMatter = FrontMatter::fromArray($data);
        } catch (FrontMatterHasInvalidKeys) {
            throw Exception\FrontMatterIsNotAnObject::create();
        }

        return ParsedContent::create(
            $frontMatter,
            $bodyMatter,
        );
    }
}
