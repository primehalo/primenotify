# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.0.0 BETA 9] - 2018-08-17
### Changed
- Replaced my custom email template manager with the built-in one

## [1.0.0 BETA 8] - 2018-08-12
### Fixed
- The default notification settings would not be set for newly registered users.

### Changed
- When enabling the extension, a primenotify copy of existing user notification settings is created rather than converting the user notification setting.
- When disabling the extension, all existing primenotify notifications are changed into their board default equivalents and then all primenotify notification types are removed from the database rather than trying to directly convert primenotify notification types back into board default types which could cause an SQL error if the board default types already existed for any user.

## [1.0.0 BETA 7] - 2018-06-12
### Changed
- Validate and correct config form data before storing it
- Set the minimum value on the Limit Message Length field to zero

## [1.0.0 BETA 6] - 2018-04-21
### Added
- Hebrew translation

## [1.0.0 BETA 5] - 2018-04-20
### Added
- An admin setting to allow truncating messages that go over a specified character count
- Version checking

## [1.0.0 BETA 4] - 2018-04-18
### Fixed
- The PM notification would not be marked as read when visiting the private message

### Changed
- All files that used the Windows line ending format (CR/LF) were changed to use the UNIX (LF) line ending format.

## [1.0.0 BETA 3] - 2018-04-11
### Fixed
- The notification would not be marked as read when visiting the topic

## [1.0.0 BETA 2] - 2018-02-28
### Changed
- Lowered the phpBB version requirement from 3.2.2 to 3.2.1
- Improved caching of email template language files and paths
- Simplified the code that runs when enabling and disabling the extension

### Removed
- Old code that was not being used.

## [1.0.0 BETA] - 2018-02-19
- Initial test release for phpBB 3.2, ported from the Prime Notify MOD for phpBB 3.0.