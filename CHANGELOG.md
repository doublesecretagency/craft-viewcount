# Changelog

## Unreleased

### Changed
- Craft 5 compatibility.

## 1.2.3 - 2024-02-21

### Changed
- Improved performance of `sort` method for larger datasets.

## 1.2.2 - 2023-09-13

### Fixed
- Fixed bug which caused excessive logging from the `Craft::$app->user->getIdentity()` method. ([#27](https://github.com/doublesecretagency/craft-viewcount/issues/27))

## 1.2.1 - 2023-04-14

### Changed
- Remove elements with zero views from cookie and database.

## 1.2.0 - 2023-03-01

### Added
- Added Twig/PHP methods to [`decrement`](https://plugins.doublesecretagency.com/view-count/how-to-decrement-the-counter/) a counter. ([#15](https://github.com/doublesecretagency/craft-viewcount/issues/15))
- Added Twig/PHP methods to [`setCounter`](https://plugins.doublesecretagency.com/view-count/setting-or-resetting-a-counter/). ([#23](https://github.com/doublesecretagency/craft-viewcount/issues/23))
- Added Twig/PHP methods to [`resetCounter`](https://plugins.doublesecretagency.com/view-count/setting-or-resetting-a-counter/). ([#2](https://github.com/doublesecretagency/craft-viewcount/issues/2))

## 1.1.1 - 2022-08-02

### Fixed
- Fixed bug which occurred when user was not logged in.
- Fixed bug where field type prevented new elements from being created.

## 1.1.0 - 2022-04-09

### Added
- Craft 4 compatibility.

## 1.0.6 - 2022-01-15

### Changed
- New plugin icon.

## 1.0.5 - 2021-04-12

### Changed
- Updated settings message.

## 1.0.4 - 2020-08-19

### Changed
- Craft 3.5 is now required.

### Fixed
- Adjusted raw HTML output on settings page.

## 1.0.3 - 2020-03-26

### Fixed
- Guard against missing elements.

## 1.0.2 - 2020-02-08

### Fixed
- Fixed PHP 7.4 compatibility issues.

## 1.0.1 - 2019-08-22

### Fixed
- Allow anonymous views via AJAX.

## 1.0.0 - 2019-01-21

Initial release.
