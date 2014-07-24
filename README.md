Level up! (block_xp)
====================

Engage your students! A Moodle plugin to gamify your students' learning experience by allowing them to _level up_ in their courses.

Features
--------

- Automatically captures and attributes experience points to students' actions
- Block that displays current level and progress towards next level
- Report for teachers to get an overview of their students' levels
- A ladder to display the ranking of the students
- Ability to set the number of levels and the experience required to get to them
- Page to display the list of levels and a description
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

This block listens to events triggered in Moodle and captures some according to pre-defined rules. It then attributes experience points based on the information contained in the event. For more information about the events, refer to the [documentation](http://docs.moodle.org/dev/Event_2#Information_contained_in_events).

Only the users with the capability _block/xp:earnxp_ can earn experience points. This capability is given by default to students. Actions triggered by guests, non-logged in users or administrators are ignored.

The following actions are also __ignored__:

- Events from another context than course or module
- Events having another _educational level_ than _participating_
- Assessable submitted or uploaded events
- Delete events

Then, this is the default experience __point attribution__:

- Create event: 45 points
- Read event: 9 points
- Update event: 3 points

Restricting access based on students' levels
--------------------------------------------

Have a look at this availability plugin [Level](https://github.com/FMCorz/moodle-availability_xp).

Todo
----

- Allowing teachers to define the experience points attributed for each action
- Add settings to customize the design of the levels
- Handling of course groups in ladder and report
- Awarding a badge when a student reaches a level (blocked by [MDL-39864](https://tracker.moodle.org/browse/MDL-39864))

License
-------

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html)
