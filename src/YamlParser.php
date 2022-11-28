<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/front-matter
 */

namespace Ergebnis\FrontMatter;

use Symfony\Component\Yaml;

final class YamlParser implements Parser
{
    private const PATTERN = "{^(?:---)[\r\n|\n]*(?P<frontMatter>.*?)[\r\n|\n]+(?:---)[\r\n|\n]{0,1}(?P<content>.*)$}s";

    public function hasFrontMatter(string $value): bool
    {
        return \preg_match(self::PATTERN, $value) === 1;
    }

    public function parse(string $value): Parsed
    {
        if (\preg_match(self::PATTERN, $value, $matches) !== 1) {
            return Parsed::fromFrontMatterAndContent(
                [],
                $value,
            );
        }

        $content = $matches['content'];

        $rawFrontMatter = $matches['frontMatter'];

        if ('' === $rawFrontMatter) {
            return Parsed::fromFrontMatterAndContent(
                [],
                $content,
            );
        }

        try {
            $data = Yaml\Yaml::parse($rawFrontMatter);
        } catch (Yaml\Exception\ParseException) {
            throw Exception\InvalidFrontMatter::create();
        }

        $frontMatter = (array) $data;

        $keys = \array_keys($frontMatter);

        $keysThatAreNotStrings = \array_filter($keys, static function ($key): bool {
            return !\is_string($key);
        });

        if ([] !== $keysThatAreNotStrings) {
            throw Exception\InvalidFrontMatter::notAllKeysAreStrings();
        }

        return Parsed::fromFrontMatterAndContent(
            $frontMatter,
            $content,
        );
    }
}
