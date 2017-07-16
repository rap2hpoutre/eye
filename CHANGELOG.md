# Release Notes

## v1.6.2 (2017-07-17)

### Added
- Now support closure based Cron Schedules calls. Please ensure you have a `->name()` or short `->description()` on the closure in order to be able to easily identify it on the website and on any alerts generated.


## v1.6.1 (2017-07-16)

### Added
- New config option to set email send frequency. If you are upgrading and want to set a different value from the default (15) - you should add "email_frequency => 30," to your eyewitness config file (values can be 15, 30, 60, 90, 120 or 180).


## v1.6.0 (2017-07-13)

### Changed
- Moved API route to prevent possible classes with other packages that catch all `api/*` routes


## v1.5.0 (2017-07-12)

### Added
- Added support for Auto-Discovery for Laravel 5.5 (thanks @m1guelpf)
- Added `eyewitness:who-am-i` command for better remote assistance
- Added `eyewitness:test-connection` command for better remote assistance

### Fixed
- Improved error handling during Composer Lock file detection (thanks @mevtho)
- Improved log facade use in certain PHP environments (thanks @mevtho)
- Fixed issue with Scheduler on some older versions of Laravel 5.2

### Changed
- Modified commands to support new Laravel 5.5 core handle functions



## v1.4.3 (2017-05-17)

### Fixed
- Allow for PDOs that only return as arrays for database checking

### Changed
- Improve Guzzle version testing
- Improve database checking error tracking


## v1.4.2 (2017-05-17)

### Changed
- Provide better installation debug information during installation failures


## v1.4.1 (2017-05-02)

### Fixed
- Allow for PHP 5.6 support


## v1.4.0 (2017-04-30)

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
