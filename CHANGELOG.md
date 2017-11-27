# Release Notes

## v2.0.4

### Added
- Added ability to configure and control what queue data is sent to browsers via ajax


## v2.0.3

### Changed
- Improved eyewitness install command to allow for better upgrades


## v2.0.2

### Changed
- Allow for PHP5.6 compatability by slightly modifying the event traits


## v2.0.1

### Fixed
- Gracefully handle servers that do not allow for reboot checks


## v2.0.0

### Added
- Completely refactored scheduling event tracking to add more detailed tracking options
- Allow remote pausing, resuming and mutex handling of scheduler events
- Add environment variable to all API calls
- Add additional variable tracking in server monitoring
- Add "backoff" retry attempts on failed API calls
- Add support for monitoring of multiple databases
- Track volume of emails sent by application
- Ability to monitor database replication status

### Fixed
- Allow scheduler ping to only run from one server

### Changed
- Refactored structure layout of package
- Refactored some core eyewitness command names
- Tweaked request tracking timer
- Improved failed queue data
- Improved queue tracking logic
- Add log file sorting
- Add offset to cron poll scheduler to help balance out server load


## v1.7.1 (2017-07-17)

### Fixed
- Fix bug with scheduler updates and compatability with some older versions of Laravel


## v1.7.0 (2017-07-17)

### Added
- Cron schedules can now include their "output" from the last job displayed on the Eyewitness website. *Note:* if you are upgrading from a previous eyewitness package version, you need to add `'capture_cron_output' => true,` to your `eyewitness.php` config file to enable the package to send the output data to the server.
- Package now supports closure based Cron Schedules calls. Please ensure you have a `->name()` or short `->description()` on the closure in order to be able to easily identify it on the website and on any alerts generated (otherwise it will be labelled *Unknown*.


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
