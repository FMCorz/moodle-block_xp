<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['addrulesformhelp'] = 'The last column defines the amount of experience points gained when the criteria is met.';
$string['basexp'] = 'Algorithm base';
$string['coefxp'] = 'Algorithm coefficient';
$string['comparisonrule'] = 'Comparison rule';
$string['configdescription'] = 'Description to append';
$string['configheader'] = 'Settings';
$string['configtitle'] = 'Title';
$string['congratulationsyouleveledup'] = 'Congratulations!';
$string['coolthanks'] = 'Cool, thanks!';
$string['courselog'] = 'Course log';
$string['coursereport'] = 'Course report';
$string['courserules'] = 'Course rules';
$string['coursesettings'] = 'Course settings';
$string['customizelevels'] = 'Customize the levels';
$string['defaultrulesformhelp'] = 'Those are the default rules provided by the plugin, they automatically give default experience points and ignore some redundant events. Custom rules take precedence over them.';
$string['description'] = 'Description';
$string['enableinfos'] = 'Enable infos page';
$string['enableinfos_help'] = 'When set to \'No\', students will not be able to view the infos page.';
$string['enableladder'] = 'Enable the ladder';
$string['enableladder_help'] = 'When set to \'No\', students will not be able to view the ladder.';
$string['enablelevelupnotif'] = 'Enable level up notification';
$string['enablelevelupnotif_help'] = 'When set to \'Yes\', students will be displayed a popup congratulating them for the new level reached.';
$string['enablelogging'] = 'Enable logging';
$string['enablexpgain'] = 'Enable XP gain';
$string['enablexpgain_help'] = 'When set to \'No\', nobody will earn experience points in the course. This is useful to freeze the experience gained, or to enable it at a certain point in time.

Please note that this can also be controlled more granularly using the capability \'block/xp:earnxp\'.';
$string['errorformvalues'] = 'There are some issues in the form values, please fix them.';
$string['errorlevelsincorrect'] = 'The minimum number of levels is 2';
$string['errorxprequiredlowerthanpreviouslevel'] = 'The XP required is lower than or equal to the previous level.';
$string['event_user_leveledup'] = 'User leveled up';
$string['eventname'] = 'Event name';
$string['eventproperty'] = 'Event property';
$string['eventtime'] = 'Event time';
$string['existingruleformhelp'] = 'Use the first field to re-order the rules, the first rule found always takes precedence over the other ones. Delete a rule by removing its value.';
$string['for1day'] = 'For 1 day';
$string['for1month'] = 'For a month';
$string['for1week'] = 'For a week';
$string['for3days'] = 'For 3 days';
$string['forever'] = 'Forever';
$string['give'] = 'give';
$string['infos'] = 'Informations';
$string['invalidxp'] = 'Invalid XP value';
$string['keeplogs'] = 'Keep logs';
$string['ladder'] = 'Ladder';
$string['level'] = 'Level';
$string['levelcount'] = 'Level count';
$string['leveldesc'] = 'Level description';
$string['levels'] = 'Levels';
$string['levelswillbereset'] = 'Warning! Saving this form will recalculate the levels of everyone!';
$string['levelup'] = 'Level up!';
$string['levelx'] = 'Level #{$a}';
$string['logging'] = 'Logging';
$string['navinfos'] = 'Infos';
$string['navladder'] = 'Ladder';
$string['navlevels'] = 'Levels';
$string['navlog'] = 'Log';
$string['navreport'] = 'Report';
$string['navrules'] = 'Rules';
$string['navsettings'] = 'Settings';
$string['pluginname'] = 'Level up!';
$string['progress'] = 'Progress';
$string['property:action'] = 'Event action';
$string['property:component'] = 'Event component';
$string['property:crud'] = 'Event CRUD';
$string['property:eventname'] = 'Event name';
$string['property:target'] = 'Event target';
$string['participatetolevelup'] = 'Participate in the course to gain experience points and level up!';
$string['rank'] = 'Rank';
$string['reallyresetdata'] = 'Really reset the levels and experience points of everyone in this course?';
$string['resetcoursedata'] = 'Reset course data';
$string['rule'] = 'Rule';
$string['rule:contains'] = 'contains';
$string['rule:eq'] = 'is equal to';
$string['rule:eqs'] = 'is strictly equal to';
$string['rule:gt'] = 'is greater than';
$string['rule:gte'] = 'is greater or equal to';
$string['rule:lt'] = 'is less than';
$string['rule:lte'] = 'is less or equal to';
$string['rule:regex'] = 'matches the regex';
$string['rulesformhelp'] = '<p>This plugin is making use of the events to attribute experience points to actions performed by the students. You can use the form below to add your own rules and view the default ones.</p>
<p>It is advised to check the plugin\'s <a href="{$a->log}">log</a> to identify what events are triggered as you perform actions in the course, and also to read more about events themselves: <a href="{$a->list}">list of all events</a>, <a href="{$a->doc}">developer documentation</a>.</p>
<p>Finally, please note that the plugin always ignores:
<ul>
    <li>The actions performed by administrators, guests or non-logged in users.</li>
    <li>And the events of educational level not equal to \'Participating\'.</li>
</ul>
</p>';
$string['value'] = 'Value';
$string['valuessaved'] = 'The values have been successfully saved.';
$string['viewtheladder'] = 'View the ladder';
$string['updateandpreview'] = 'Update and preview';
$string['usealgo'] = 'Use the algorithm';
$string['when'] = 'When';
$string['xp'] = 'Experience points';
$string['xp:addinstance'] = 'Add a new XP block';
$string['xp:earnxp'] = 'Earning experience points';
$string['xprequired'] = 'XP required';
$string['xpgaindisabled'] = 'XP gain disabled';
$string['youreachedlevela'] = 'You reached level {$a}!';
