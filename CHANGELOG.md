# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.0.7] - 2023-04-16
### Fixed
- Notifications would fail for User's Choice if the DB dropped support for the deprecated comma JOIN syntax
- topic_notify.txt is wrongly used for forum subscriptions (phpBB3-16544 fixed in phpBB 3.3.2)

### Changed
- Raised minimum required phpBB version from 3.2.2 to 3.3.2

## [1.0.6] - 2023-01-03
### Added
- Italian translations for previously untranslated text

## [1.0.5] - 2020-09-01
### Fixed
- Potential SQL error that could occur after disabling the extension and attempting to delete the extension data. This error would preventing the extension data from being deleted and the extension from being uninstalled.

## [1.0.4] - 2020-08-19
### Changed
- Changed the depends_on() method back to what it was in version 1.0.0, where it was requiring phpBB v3.2.1, and added a new migration file with the sole purpose of having the depends_on() method require phpBB v3.2.2. This change should not affect anyone, it is only for phpBB DB bureaucracy as they don't allow any changes to migration files that have already been approved.

## [1.0.3] - 2020-06-29
### Added
- Message in the ACP extension settings page telling where the User’s Choice options show up.

### Changed
- Raised minimum required phpBB version from 3.2.1 to 3.2.2

### Removed
- Unused variables
- Unneeded $config variable existence checks
- A commented out line of code

## [1.0.2] - 2020-06-19
### Added
- German translations for previously untranslated text

## [1.0.1] - 2020-05-29
### Added
- Russian translations for previously untranslated text

### Changed
- Updated some Russian translations

## [1.0.0] - 2020-03-27
### Changed
- Changed the hardcoded ellipse that is appended to truncated message to instead use the ellipse language string

## [1.0.0 BETA 16] - 2020-03-26
### Fixed
- Attempted to fix an SQL error when installing and uninstalling on a Postgress database.

## [1.0.0 BETA 15] - 2020-01-13
### Changed
- Changed the comment \phpbb\notification_manager to \phpbb\notification\manager in event/main_listener.php

### Fixed
- Fixed illegal characters in the French and Dutch email language files

### Removed
- Removed unused declared variables from core/prime_notify.php
- Removed specific comments from notification/type/post.php and nofitication/type/topic.php that marked code which was specific to this extension

## [1.0.0 BETA 14] - 2019-11-02
### Changed
- Used a new function introduced in phpBB 3.2.9 for encoding 4-byte characters, but only if the function exists.

## [1.0.0 BETA 13] - 2019-10-28
### Changed
- Converted the core prime_notify class into a service

## [1.0.0 BETA 12] - 2019-09-25
### Changed
- Encode 4-byte characters such as emojis so they can be stored in the database and later retrieved, decoded, and displayed within emails. Previously such characters were just being converted to a single utf8_bin supported character.

## [1.0.0 BETA 11] - 2019-04-04
### Fixed
- 4-byte characters such as emojis would cause a database error. To fix I change them to a single 3-byte character.

## [1.0.0 BETA 10] - 2019-01-08
### Added
- Czech translation (email template files only)

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