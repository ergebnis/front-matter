# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`3.3.0...main`][3.3.0...main].

### Changed

- Allowed installation on PHP 8.5 ([#747]), by [@localheinz]

## [`3.3.0`][3.3.0]

For a full diff see [`3.2.0...3.3.0`][3.2.0...3.3.0].

### Changed

- Allowed installation of `symfony/yaml:^7.0.0` ([#712]), by [@localheinz]

## [`3.2.0`][3.2.0]

For a full diff see [`3.1.0...3.2.0`][3.1.0...3.2.0].

### Added

- Added support for PHP 8.4 ([#677]), by [@localheinz]

## [`3.1.0`][3.1.0]

For a full diff see [`3.0.0...3.1.0`][3.0.0...3.1.0].

### Changed

- Added support for PHP 8.3 ([#485]), by [@localheinz]

## [`3.0.0`][3.0.0]

For a full diff see [`2.1.0...3.0.0`][2.1.0...3.0.0].

### Changed

- Dropped support for PHP 8.0 ([#393]), by [@localheinz]
- Renamed `Parsed::fromFrontMatterAndContent()` to `Parsed::create()` ([#397]), by [@localheinz]
- Extracted `BodyMatter` as a value object ([#398]), by [@localheinz]
- Started composing `Content` and `Data` into `FrontMatter` ([#409]), by [@localheinz]
- Renamed `Exception\FrontMatterDoesNotHaveKey` to `Exception\DataDoesNotHaveKey` ([#410]), by [@localheinz]
- Started composing `Content` into `BodyMatter` ([#411]), by [@localheinz]

## [`2.1.0`][2.1.0]

For a full diff see [`2.0.0...2.1.0`][2.0.0...2.1.0].

### Changed

- Allowed using dot notation to access values in `FrontMatter` ([#346]), by [@localheinz]

## [`2.0.0`][2.0.0]

For a full diff see [`1.0.0...2.0.0`][1.0.0...2.0.0].

### Changed

- Extracted `Content` as a value object ([#341]), by [@localheinz]
- Extracted `FrontMatter` as a value object ([#342]), by [@localheinz]
- Split `InvalidFrontMatter` exception into `FrontMatterCanNotBeParsed` and `FrontMatterIsNotAnObject` exceptions ([#344]), by [@localheinz]

## [`1.0.0`][1.0.0]

For a full diff see [`0.4.0...1.0.0`][0.4.0...1.0.0].

### Changed

- Dropped support for PHP 7.4 ([#301]), by [@localheinz]

## [`0.4.0`][0.4.0]

### Changed

- Allowed installation with `symfony/yaml:^6.0.0` ([#181]), by [@localheinz]

## [`0.3.0`][0.3.0]

For a full diff see [`0.2.0...0.3.0`][0.2.0...0.3.0].

### Changed

- Dropped support for PHP 7.3 ([#157]), by [@localheinz]

## [`0.2.0`][0.2.0]

For a full diff see [`0.1.0...0.2.0`][0.1.0...0.2.0].

### Changed

- Renamed `Exception\InvalidFrontMatter::keysCanNotBeNumeric()` to `Exception\InvalidFrontMatter::notAllKeysAreStrings()` ([#9]), by [@localheinz]

### Removed

- Removed `Parsed\FrontMatter` and `Parsed\Content` ([#8]), by [@localheinz]

## [`0.1.0`][0.1.0]

For a full diff see [`4e97e14...0.1.0`][4e97e14...0.1.0].

### Added

- Added `YamlParser` ([#2]), by [@localheinz]

[0.1.0]: https://github.com/ergebnis/front-matter/releases/tag/0.1.0
[0.2.0]: https://github.com/ergebnis/front-matter/releases/tag/0.2.0
[0.3.0]: https://github.com/ergebnis/front-matter/releases/tag/0.3.0
[0.4.0]: https://github.com/ergebnis/front-matter/releases/tag/0.4.0
[1.0.0]: https://github.com/ergebnis/front-matter/releases/tag/1.0.0
[2.0.0]: https://github.com/ergebnis/front-matter/releases/tag/2.0.0
[2.1.0]: https://github.com/ergebnis/front-matter/releases/tag/2.1.0
[3.0.0]: https://github.com/ergebnis/front-matter/releases/tag/3.0.0
[3.1.0]: https://github.com/ergebnis/front-matter/releases/tag/3.1.0
[3.2.0]: https://github.com/ergebnis/front-matter/releases/tag/3.2.0
[3.3.0]: https://github.com/ergebnis/front-matter/releases/tag/3.3.0

[4e97e14...0.1.0]: https://github.com/ergebnis/front-matter/compare/4e97e14...0.1.0
[0.1.0...0.2.0]: https://github.com/ergebnis/front-matter/compare/0.1.0...0.2.0
[0.2.0...0.3.0]: https://github.com/ergebnis/front-matter/compare/0.2.0...0.3.0
[0.3.0...0.4.0]: https://github.com/ergebnis/front-matter/compare/0.3.0...0.4.0
[0.4.0...1.0.0]: https://github.com/ergebnis/front-matter/compare/0.4.0...1.0.0
[1.0.0...2.0.0]: https://github.com/ergebnis/front-matter/compare/1.0.0...2.0.0
[2.0.0...2.1.0]: https://github.com/ergebnis/front-matter/compare/2.0.0...2.1.0
[2.1.0...3.0.0]: https://github.com/ergebnis/front-matter/compare/2.1.0...3.0.0
[3.0.0...3.1.0]: https://github.com/ergebnis/front-matter/compare/3.0.0...3.1.0
[3.1.0...3.2.0]: https://github.com/ergebnis/front-matter/compare/3.1.0...3.2.0
[3.2.0...3.3.0]: https://github.com/ergebnis/front-matter/compare/3.2.0...3.3.0
[3.3.0...main]: https://github.com/ergebnis/front-matter/compare/3.3.0...main

[#2]: https://github.com/ergebnis/front-matter/pull/2
[#8]: https://github.com/ergebnis/front-matter/pull/8
[#9]: https://github.com/ergebnis/front-matter/pull/9
[#157]: https://github.com/ergebnis/front-matter/pull/157
[#181]: https://github.com/ergebnis/front-matter/pull/181
[#301]: https://github.com/ergebnis/front-matter/pull/301
[#341]: https://github.com/ergebnis/front-matter/pull/341
[#342]: https://github.com/ergebnis/front-matter/pull/342
[#344]: https://github.com/ergebnis/front-matter/pull/344
[#346]: https://github.com/ergebnis/front-matter/pull/346
[#393]: https://github.com/ergebnis/front-matter/pull/393
[#397]: https://github.com/ergebnis/front-matter/pull/397
[#398]: https://github.com/ergebnis/front-matter/pull/398
[#399]: https://github.com/ergebnis/front-matter/pull/399
[#400]: https://github.com/ergebnis/front-matter/pull/400
[#407]: https://github.com/ergebnis/front-matter/pull/407
[#409]: https://github.com/ergebnis/front-matter/pull/409
[#410]: https://github.com/ergebnis/front-matter/pull/410
[#411]: https://github.com/ergebnis/front-matter/pull/411
[#485]: https://github.com/ergebnis/front-matter/pull/485
[#677]: https://github.com/ergebnis/front-matter/pull/677
[#712]: https://github.com/ergebnis/front-matter/pull/712
[#747]: https://github.com/ergebnis/front-matter/pull/747

[@localheinz]: https://github.com/localheinz
