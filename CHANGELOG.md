Changelog
=========

v2.1.2
------

- Fixed a bug causing a blank ladder on older PHP versions
- Fixed a bug where some settings where not backed up
- Fixed a bug where some rules filters were not restored
- Other minor bug fixes and improvements

v2.1.1
------

- Fixed a bug where new settings were not saved on new installs

v2.1.0
------

- Level up notification always appears on the right course
- Ladder option to only display neighbours in the ranking
- Ladder option to hide the rank, or display a relative rank
- Ladder option to hide other participants identity
- Ladder opens on the page where the current user is located
- Performance improvements on ladder and report
- Fixed a bug where some students with 0 XP would appear at the top of the report
- Minor usability improvements

v2.0.1
------

- Fix composer installation directory
- Fix MySQL compability when resetting all course data
- Fix bug causing visuals not to be displayed when XP is used site-wide

v2.0.0
------

- Experience points can be used either for the whole site or per course
- Course groups are considered when displaying the report and the ladder
- \mod_book\event\course_module_viewed has been added to the list of redundant events
- Complete new user interface to set up rules (nested conditions, drag and drop, ...)
- Ability to filter events per activity
- Added a user friendly condition to filter per event
- Anonymous events are now always ignored
- Bug fixes

v1.5.2
------

- Plugin can be installed using composer
- Bug fixes

v1.5.1
------

- Fixed a bug causing cron to fail

v1.5
----

- Improving cheat guard
- Exposing some options of the cheat guard
- Report displays all the students, not just the ones with XP
- Introducing backup and restore functionality
- Removing associated data when a course is deleted

v1.4
----

- Ability to customise the appearance of the level badges

v1.3
----

- Notification popup when a student levels up
- Ability to customise the XP gained with rules

v1.2
----

- Events are not captured for guests and non-logged in users
- Added a ladder page to display their rank to the students
- New capability controlling who earns XP
- Admin users do not earn XP any more
- Customising the levels and the required XP
- New info page displaying the levels, a description and the XP required
- Ability to edit the XP of a user


v1.1
----

- Performance improvements

v1.0
----

- Display current level in block
- Display progress bar in block
- Report page to see the progress of everyone
- Log page to see the events that were captured
- 3 settings to set the number of levels, enable logging and how long to store the logs for
- Capture participating events that match CRU (from CRUD)
- Basic algorithm and xp attribution
- Event fired when someone levels up
