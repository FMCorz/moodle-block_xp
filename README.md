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

Purchasing the _Level up! Plus_ add-on unlocks additional features. [Click here for more details](https://levelup.plus?ref=readme).

Requirements
------------

Moodle 3.1 or greater.

Installation
------------

Simply install the plugin and add the block to a course page. More details are available here: [Level up! documentation](https://levelup.plus/docs/topic/installation?ref=readme)

The plugin can also be installed using [composer](https://getcomposer.org/) at [fmcorz/moodle-block_xp](https://packagist.org/packages/fmcorz/moodle-block_xp).

How are experience points calculated?
-------------------------------------

Have a look at this [documentation page](https://levelup.plus/docs/article/how-are-experience-points-calculated?ref=readme).

Restricting access based on students' levels
--------------------------------------------

Have a look at this availability plugin [Level](https://github.com/FMCorz/moodle-availability_xp).

Level-based enrolment
---------------------

Have a look at this enrolment plugin [Level](https://github.com/branchup/moodle-enrol_xp).

Shortcodes
----------

_What are those? Shortcodes can be used in editors throughout Moodle to include or modify the content. The plugin [Shortcodes](https://github.com/branchup/moodle-filter_shortcodes) must be installed to enable them._

Check the [list of supported shortcodes](https://levelup.plus/docs/article/using-shortcodes?ref=readme).

How to use one block for all courses
------------------------------------

In order to gather experience points from all the courses a student is participating in, you have to set the admin setting _Where are experience points used?_ to 'For the whole site'. This setting is located under "Site administration > Plugins > Blocks > Level up!". Once set, any block newly or previously added will display the total experience points of your student.

Todo
----

- Awarding a badge when a student reaches a level (blocked by [MDL-39864](https://tracker.moodle.org/browse/MDL-39864))

Provided by
-----------

[![Branch Up](https://branchup.tech/branch-up-logo-x30.svg)](https://branchup.tech?ref=levelup_readme)

License
-------

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
