# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
## Changed
- Adapt transport files for katana 1.2.14 format. Warning!!! BC break.

## [1.2.5] - 2017-10-10
## Changed
- Added default values in transport mapper to avoid notices.

## [1.2.4] - 2017-10-02
## Added
- Allow CLI option *timeout* to be passed without effect.
- Header conversion to deal with certain message formats.

## [1.2.3] - 2017-09-12
## Added
- Register runtime calls in the transport with duration
## Changed
- Refactor and test runtime-call mappers
## Fixed
- Fix getting normalized http headers from response

## [1.2.2] - 2017-09-07
## Fixed
- Skip merge of 0 duration calls

## [1.2.1] - 2017-09-06
## Fixed
- Treat headers as an array of values on response

## [1.2.0] - 2017-09-01
## Added
- Start support for katana 1.2
## Fixed
- Catch msgpack Packing Exceptions

## [1.1.10] - 2017-08-14
## Fixed
- Fixed Response accessors for attributes

## [1.1.9] - 2017-08-14
## Added
- Support for request attributes from katana 1.1.12
- Accessors for request id and timestamp

## [1.1.8] - 2017-07-13
## Added
- New header methods

## [1.1.7] - 2017-07-12
## Fixed
- Fix default property in getProperty()

## [1.1.6] - 2017-07-10
## Fixed
- Fix meta byte for communicating calls

## Added
- Add `Api:hasVariable()`

## [1.1.5] - 2017-07-02
## Fixed
- Fixed url methods return type when a part is not found

## [1.1.4] - 2017-06-28
## Added
- Pass component to `setResource()` callback
## Fixed
- Version pattern with wildcard at the end

## [1.1.3] - 2017-06-16
## Fixed
- Fixed merge of errors in the transport

## [1.1.2] - 2017-06-14
## Changed
- Replaced msgpack dependency to avoid misbehaviour with references

## [1.1.1] - 2017-06-07
## Fixed
- Fixed optional parameters in Request::newResponse() method

## [1.1.0] - 2017-06-01
### Changed
- Updated CONTRIBUTING.md and README.md

## [1.0.6] - 2017-05-29
### Fixed
- Cast object parameters recursively
- Fix Transport::newEmpty() call from Request::newResponse()

## [1.0.5] - 2017-05-23
### Added
- Add support for binary type

### Fixed
- Fix type hinting for newParam

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
