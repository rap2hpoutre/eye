# Release Notes

## Unreleased

### Added
- Now track the status of `config:cache` and `route:cache` for the application.
- Add `eyewitness:poll` command and add to scheduler. Package will now autopush server status when required, rather
  than require manual polling. Allows for servers behind VPNs etc to still be monitored. Eyewitness.io will
  auto fall back to remote polling for applications that do not run a scheduler.

### Changed
- Refactored install command for new applications to be simplier.
- Include current application environment when polling.
- Include eyewitness config setup when polling.


## v1.3.1 (2017-04-24)

### Added
- Added a changelog to package.

### Changed
- Refactored API folder location.

### Fixed
- Fixed a bug with API to provide simultaneous support for both Guzzle v5 & v6 for file support.
- Fixed a bug where failing queue names would sometimes not be captured correctly.
