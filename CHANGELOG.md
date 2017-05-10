# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.4] - 2017-05-10
### Added
- Add support for retrieving the return value in response **Middlewares**
- Add support for call start time, end time and duration

### Fixed
- Fix handling errors in the runtime call payload

## [1.0.3] - 2017-04-29
### Changed
- Rename method to `Transport::getOriginService`
- Catch `Throwable` to recover from more errors

## [1.0.2] - 2017-04-28
### Changed
- Fixed sending params to runtime call

## [1.0.1] - 2017-04-07
### Changed
- Fixed type hints and namespacing
- Changed CLI input to read from stdin
- Fix collection type validation

## [1.0.0] - 2017-03-13
- Initial release
