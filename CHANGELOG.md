# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`0.2.0...main`][0.2.0...main].

## [`0.2.0`][0.2.0]

For a full diff see [`0.1.0...0.2.0`][0.1.0...0.2.0].

### Changed

* Renamed `Exception\InvalidFrontMatter::keysCanNotBeNumeric()` to `Exception\InvalidFrontMatter::notAllKeysAreStrings()` ([#9]), by [@localheinz]

### Removed

* Removed `Parsed\FrontMatter` and `Parsed\Content` ([#8]), by [@localheinz]

## [`0.1.0`][0.1.0]

For a full diff see [`4e97e14...0.1.0`][4e97e14...0.1.0].

### Added

* Added `YamlParser` ([#2]), by [@localheinz]

[0.1.0]: https://github.com/ergebnis/front-matter/releases/tag/0.1.0
[0.2.0]: https://github.com/ergebnis/front-matter/releases/tag/0.2.0

[4e97e14...0.1.0]: https://github.com/ergebnis/front-matter/compare/4e97e14...0.1.0
[0.1.0...0.2.0]: https://github.com/ergebnis/front-matter/compare/0.1.0...0.2.0
[0.2.0...main]: https://github.com/ergebnis/front-matter/compare/0.2.0...main

[#2]: https://github.com/ergebnis/front-matter/pull/2
[#8]: https://github.com/ergebnis/front-matter/pull/8
[#9]: https://github.com/ergebnis/front-matter/pull/9

[@localheinz]: https://github.com/localheinz
