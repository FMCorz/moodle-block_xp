Level up! (block_xp)
====================

Engage your students! A Moodle plugin to gamify your students' learning experience by allowing them to _level up_ in their courses.

Features
--------

- Automatically captures and attributes experience points to students' actions
- Block that displays current level and progress towards next level
- Report for teachers to get an overview of their students' levels
- Experience points are earned per course
- An event is fired when a student levels up (for developers)

Requirements
------------

Moodle 2.7 or greater.

Installation
------------

Simply install the plugin and add the block to a course page.

How are experience points calculated?
-------------------------------------

Thanks to the new Event system introduced in Moodle 2.6, the plugin captures the events having the educational level _participating_ and gives experience points based on whether they are _creating_, _updating_ or _reading_ content. Though due to the lack of _participating_ events in earlier versions, this plugin was developed for 2.7 or greater.

How to restrict access to a module according to the user's level?
-----------------------------------------------------------------

Have a look at this availability plugin [Level](https://github.com/FMCorz/moodle-availability_xp).

Todo
----

- Allowing teachers to define the experience points attributed for each action
- Customizable algorithm to determine the experience points needed for each level
- Add settings to customize the design of the levels
- A 'ladder' page to see other students' levels
- Awarding a badge when a student reaches a level (blocked by [MDL-39864](https://tracker.moodle.org/browse/MDL-39864))

License
-------

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html)
