Level up! (block_xp) ![GitHub tag](https://img.shields.io/github/tag/FMCorz/moodle-block_xp.svg) ![Travis branch](https://img.shields.io/travis/FMCorz/moodle-block_xp/master.svg)
====================

Engage your students! A Moodle plugin to gamify your students' learning experience by allowing them to _level up_ in their courses.

Features
--------

- Automatically captures and attributes experience points to students' actions
- Block that displays current level and progress towards next level
- Report for teachers to get an overview of their students' levels
- Notifications to congratulate students as they level up
- A ladder to display the ranking of the students
- Ability to set the number of levels and the experience required to get to them
- Images can be uploaded to customise for the appearance of the levels
- The amount of experience points earned per event is customizable
- Page to display the list of levels and a description
- Experience points are earned per course
- An event is fired when a student levels up (for developers)

### Additional features

Purchasing the _Level up! Plus_ add-on unlocks additional features. [Click here for more details](http://levelup.branchup.tech?utm_source=blockxp&utm_medium=github_readme&utm_campaign=github).

Requirements
------------

Moodle 3.1 or greater.

Installation
------------

Simply install the plugin and add the block to a course page.

The plugin can be installed using [composer](https://getcomposer.org/) at [fmcorz/moodle-block_xp](https://packagist.org/packages/fmcorz/moodle-block_xp).

How are experience points calculated?
-------------------------------------

This block listens to events triggered in Moodle and captures some according to pre-defined rules. It then attributes experience points based on the information contained in the event. For more information about the events, refer to the [documentation](http://docs.moodle.org/dev/Event_2#Information_contained_in_events).

Only the users with the capability _block/xp:earnxp_ can earn experience points. This capability is given by default to students. Also actions triggered by guests, non-logged in users or administrators are ignored.

The following events are always __ignored__:

- Events from another context than course or module
- Events having another _educational level_ than _participating_
- Events flagged as _anonymous_, e.g. in an anonymous Feedback

The rest is up to you. By default the plugin comes with a minimal set of rules to attribute experience points to actions, and to ignore some redundant ones. Visit the _Rules_ page in the plugin itself to view or override them.

_Note_: Repeated actions within a short time interval are ignored to prevent cheating.

Restricting access based on students' levels
--------------------------------------------

Have a look at this availability plugin [Level](https://github.com/FMCorz/moodle-availability_xp).

Level-based enrolment
---------------------

Have a look at this enrolment plugin [Level](https://github.com/branchup/moodle-enrol_xp).

Shortcodes supported
--------------------

_What are those? Shortcodes can be used in editors throughout Moodle to include or modify the content. The plugin [Shortcodes](https://github.com/branchup/moodle-filter_shortcodes) must be installed to enable them._

### xpbadge

Displays the badge matching the level of current user.

`[xpbadge]`

### xpiflevel

Displays the content within its brackets according to the user's level. When a level is stricly specified, the content will be displayed regardless of the other rules. The greater and less than rules must all match for the content to be displayed, watch out as that may sometimes result in the content to never be displayed! Note that teachers, or otherwise users with editing capabilities, will always see everything.

```
[xpiflevel 1 3 5]
    Displayed if the user's level is exactly 1, 3 or 5.
[/xpiflevel]

[xpiflevel >3]
    Displayed if the user's level is greater than 3.
[/xpiflevel]

[xpiflevel >=3]
    Displayed if the user's level is greater or equal to 3.
[/xpiflevel]

[xpiflevel >=10 <20 30]
    Displayed if the user's level is greater or equal to 10 AND is strictly less than 20
    OR is exactly equal to 30.
[/xpiflevel]

[xpiflevel <=10 >=20]
    Never displayed because the user's level can never be less or equal to 10 AND more or equal to 20.
[/xpiflevel]
```

Note that these shortcodes CANNOT be nested within one another.

### xpprogressbar

Displays the current user's progress bar towards the next level.

`xpprogressbar`

How to use one block for all courses
------------------------------------

In order to gather experience points from all the courses a student is participating in, you have to set the admin setting _Where are experience points used?_ to 'For the whole site'. This setting is located under "Site administration > Plugins > Blocks > Level up!". Once set, any block newly or previously added will display the total experience points of your student.

Todo
----

- Awarding a badge when a student reaches a level (blocked by [MDL-39864](https://tracker.moodle.org/browse/MDL-39864))

Provided by
-----------

[![Branch Up](https://branchup.tech/branch-up-logo-x30.svg)](https://branchup.tech)

License
-------

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
