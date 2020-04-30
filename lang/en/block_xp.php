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

$string['actions'] = 'Actions';
$string['activityoresourceis'] = 'The activity or resource is {$a}';
$string['addacondition'] = 'Add a condition';
$string['addarule'] = 'Add a rule';
$string['admindefaultrulesintro'] = 'The following rules will be used as default for courses in which the block is added.';
$string['admindefaultsettingsintro'] = 'The settings below will be used as defaults when the block is newly added to a course.';
$string['admindefaultvisualsintro'] = 'The following will be used as defaults when the block is newly added to a course.';
$string['addinstructions'] = 'Add more information';
$string['anonymity'] = 'Anonymity';
$string['anonymity_help'] = 'This setting controls whether participants can see each other\'s name and avatar.';
$string['awardaxpwhen'] = '<strong>{$a}</strong> points are earned when:';
$string['basexp'] = 'Algorithm base';
$string['blockappearance'] = 'Block appearance';
$string['cachedef_filters'] = 'Level filters';
$string['cachedef_ruleevent_eventslist'] = 'List of some events';
$string['cannotshowblockconfig'] = 'I would usually display the appearance settings here, but I could not find your block. To change the block\'s appearance, head back [here]({$a}) (or where you added the block), turn editing mode on, and follow the "Configure" option in the block\'s dropdown. If you cannot find the block, add it to your course again.';
$string['cannotshowblockconfigsys'] = 'I would usually display the appearance settings here, but I could not find your block. It may be missing from the [front page]({$a->fp}) and the [default dashboard]({$a->mysys}) of your users, or present in both. To edit the settings from here, make sure it only appears in one of them.';
$string['cheatguard'] = 'Cheat guard';
$string['clicktoselectcm'] = 'Click to select an activity or resource';
$string['coefxp'] = 'Algorithm coefficient';
$string['colon'] = '{$a->a}: {$a->b}';
$string['cmselector'] = 'Course module selector';
$string['configdescription'] = 'Introduction';
$string['configdescription_help'] = 'A short introduction displayed in the block, below the student\'s level. Students have the ability to dismiss the message, in which case they won\'t see it again.';
$string['configheader'] = 'Settings';
$string['configtitle'] = 'Title';
$string['configtitle_help'] = 'The title of the block.';
$string['configrecentactivity'] = 'Display recent rewards';
$string['configrecentactivity_help'] = 'When enabled, the block will display a short list of recent events which rewarded the student with points.';
$string['congratulationsyouleveledup'] = 'Congratulations!';
$string['coolthanks'] = 'Cool, thanks!';
$string['courselog'] = 'Course log';
$string['coursereport'] = 'Course report';
$string['courserules'] = 'Course rules';
$string['courseselectedcolon'] = 'Course selected:';
$string['coursesettings'] = 'Course settings';
$string['coursevisuals'] = 'Course visuals';
$string['customizelevels'] = 'Customize the levels';
$string['dangerzone'] = 'Danger zone';
$string['defaultlevels'] = 'Default levels';
$string['defaultrules'] = 'Default rules';
$string['defaultrulesformhelp'] = 'Those are the default rules provided by the plugin, they automatically give default points and ignore some redundant events. Your own rules take precedence over them.';
$string['defaultsettings'] = 'Default settings';
$string['defaultvisuals'] = 'Default visuals';
$string['deletecondition'] = 'Delete condition';
$string['deleterule'] = 'Delete rule';
$string['description'] = 'Description';
$string['difference'] = 'Diff.';
$string['discoverlevelupplus'] = 'Discover Level up! Plus';
$string['dismissnotice'] = 'Dismiss notice';
$string['displayeveryone'] = 'Display everyone';
$string['displaynneighbours'] = 'Display {$a} neighbours';
$string['displayoneneigbour'] = 'Display one neighbour';
$string['displayparticipantsidentity'] = 'Display participants identity';
$string['displayrank'] = 'Display rank';
$string['displayrelativerank'] = 'Display a relative rank';
$string['editinstructions'] = 'Edit information';
$string['enablecheatguard'] = 'Enable cheat guard';
$string['enablecheatguard_help'] = 'The cheat guard offers a simple inexpensive mechanism for preventing students to abuse the system using obvious techniques, such as refreshing the same page endlessly, or repeating the same action over and over again.';
$string['enableinfos'] = 'Enable info page';
$string['enableinfos_help'] = 'When set to \'No\', students will not be able to view the information page.';
$string['enableladder'] = 'Enable the ladder';
$string['enableladder_help'] = 'When set to \'No\', students will not be able to view the ladder.';
$string['enablelevelupnotif'] = 'Enable level up notification';
$string['enablelevelupnotif_help'] = 'When set to \'Yes\', students will be displayed a popup congratulating them for the new level reached.';
$string['enablexpgain'] = 'Enable points gain';
$string['enablexpgain_help'] = 'When set to \'No\', nobody will earn points in the course. This is useful to freeze the points gained, or to enable it at a certain point in time.

Please note that this can also be controlled more granularly using the capability _block/xp:earnxp_.';
$string['entersearchterm'] = 'Enter a search term';
$string['errorcontextcoursemismatchforwholesite'] = 'The URL of this <em>Level up!</em> page does not match the current plugin configuration. Your current configuration declares <em>Level up!</em> to be used \'For the whole site\', however this page expected it to be used \'Per course\'. Please <a href="{$a->nexturl}">click here</a> to navigate to the right page. Search for the admin setting \'block_xp_context\' if you wish to change your configuration.';
$string['errorcontextcoursemismatchpercourse'] = 'The URL of this <em>Level up!</em> page does not match the current plugin configuration. Your current configuration declares <em>Level up!</em> to be used \'Per course\', but this page expects it to be used \'For the whole site\'. It most likely originates from a <em>block</em> that was added to the dashboard or front page while in a different configuration. You should remove the block from the latter pages, and only use the block from within individual courses.';
$string['errorformvalues'] = 'There are some issues in the form values, please fix them.';
$string['errorlevelsincorrect'] = 'The minimum number of levels is 2';
$string['errornotalllevelsbadgesprovided'] = 'Not all the level badges have been provided. Missing: {$a}';
$string['errorunknownevent'] = 'Error: unknown event';
$string['errorunknownmodule'] = 'Error: unknown module';
$string['errorxprequiredlowerthanpreviouslevel'] = 'The points required are lower than or equal to the previous level.';
$string['eventsrules'] = 'Events rules';
$string['eventsrules_help'] = 'This plugin is making use of the events to attribute points to actions performed by the students.
You can use the form below to add your own rules and modify the default ones.

It is advised to check the plugin\'s _Log_ page to identify which events are triggered as students perform actions in the course.

Additional resources:

- [How are experience points calculated?](https://levelup.plus/docs/article/how-are-experience-points-calculated?ref=blockxp_help)
- [Troubleshooting rules](https://levelup.plus/docs/article/event-rule-not-working?ref=blockxp_help)
';
$string['event_user_leveledup'] = 'User leveled up';
$string['eventis'] = 'The event is {$a}';
$string['eventname'] = 'Event name';
$string['eventproperty'] = 'Event property';
$string['eventtime'] = 'Event time';
$string['filtermodules'] = 'Filter modules';
$string['for1day'] = 'For 1 day';
$string['for1month'] = 'For a month';
$string['for1week'] = 'For a week';
$string['for3days'] = 'For 3 days';
$string['forever'] = 'Forever';
$string['forthewholesite'] = 'For the whole site';
$string['give'] = 'give';
$string['gotofullladder'] = 'Go to full ladder';
$string['hideparticipantsidentity'] = 'Hide participants identity';
$string['hiderank'] = 'Hide rank';
$string['incourses'] = 'In courses';
$string['ineffective'] = 'Ineffective';
$string['infos'] = 'Information';
$string['instructions'] = 'More information';
$string['invalidxp'] = 'Invalid points value';
$string['keeplogs'] = 'Keep logs';
$string['ladder'] = 'Ladder';
$string['ladderadditionalcols'] = 'Additional columns';
$string['ladderadditionalcols_help'] = 'This setting determines which additional columns are displayed on the ladder. Press the CTRL or CMD key while clicking to select more than one column, or to unselect a selected column.';
$string['ladderempty'] = 'The ladder is currently empty, make sure to come back later!';
$string['level'] = 'Level';
$string['levelbadges'] = 'Level badges';
$string['levelbadgesformhelp'] = 'Name the files [level].[file extension], for instance: 1.png, 2.jpg, etc... The recommended image size is 100x100.';
$string['levelcount'] = 'Level count';
$string['leveldesc'] = 'Level description';
$string['leveldesc_help'] = 'A short description of the level, this is displayed on the information page alongside the level itself. You may use this to describe a reward for learners who attain the level, to include instructions on how to work towards this level, to describe the level in a playful manner (e.g. _Only the bravest souls have been known to attain this level_), etc.';
$string['levelname'] = 'Level name';
$string['levelname_help'] = 'A short name to display instead of the default _Level #1_, _Level #2_, etc. that is sometimes displayed. If you give names to some levels, we recommend that you give a name to all of them!';
$string['levels'] = 'Levels';
$string['levelup'] = 'Level up!';
$string['levelupplus'] = 'Level up! Plus';
$string['levelx'] = 'Level #{$a}';
$string['likenotice'] = '<strong>Do you like the plugin?</strong> Please take a moment to <a href="{$a->moodleorg}" target="_blank">add it to your favourites</a> on Moodle.org and <a href="{$a->github}" target="_blank">star it on GitHub</a>.';
$string['limitparticipants'] = 'Limit participants';
$string['limitparticipants_help'] = 'This setting controls who is displayed in the leaderboard. Neighbours are the participants ranked above and below the current user. For instance, when choosing \'Display 2 neighbours\', only the two participants ranked directly higher and lower than the current user will be displayed.';
$string['logging'] = 'Logging';
$string['maxactionspertime'] = 'Max. actions in time frame';
$string['maxactionspertime_help'] = 'The maximum number of actions that will count for points during the time frame given. Any subsequent action will be ignored. When this value is empty, or equals to zero, it does not apply.';
$string['movecondition'] = 'Move condition';
$string['moverule'] = 'Move rule';
$string['navinfos'] = 'Info';
$string['navladder'] = 'Ladder';
$string['navlevels'] = 'Levels';
$string['navlog'] = 'Log';
$string['navpromo'] = 'Plus';
$string['navreport'] = 'Report';
$string['navrules'] = 'Rules';
$string['navsettings'] = 'Settings';
$string['navvisuals'] = 'Visuals';
$string['nologsrecordedyet'] = 'Logs have not been recorded yet.';
$string['participant'] = 'Participant';
$string['perpagecolon'] = 'Per page:';
$string['pickaconditiontype'] = 'Pick a condition type';
$string['pluginname'] = 'Level up!';
$string['pointsintimelinker'] = 'per';
$string['pointsrequired'] = 'Points required';
$string['privacy:path:addon'] = 'Add-on';
$string['privacy:path:level'] = 'Level';
$string['privacy:path:logs'] = 'Logs';
$string['privacy:metadata:log'] = 'Stores a log of events';
$string['privacy:metadata:log:eventname'] = 'The event name';
$string['privacy:metadata:log:time'] = 'The date at which it happened';
$string['privacy:metadata:log:userid'] = 'The user who gained the points';
$string['privacy:metadata:log:xp'] = 'The points awarded for the event';
$string['privacy:metadata:prefintro'] = 'Records whether the user dismissed the block\'s intro';
$string['privacy:metadata:preflevelup'] = 'Records whether the user should see the level up notification';
$string['privacy:metadata:prefnotices'] = 'Records whether the user closed the support notice';
$string['privacy:metadata:prefseenpromo'] = 'Records when the user viewed the promo page';
$string['privacy:metadata:prefladderpagesize'] = 'The user\'s preferred page size when viewing the ladder';
$string['privacy:metadata:xp'] = 'Stores the points and level of users';
$string['privacy:metadata:xp:xp'] = 'The user\'s points';
$string['privacy:metadata:xp:lvl'] = 'The user\'s level';
$string['privacy:metadata:xp:userid'] = 'The user';
$string['progress'] = 'Progress';
$string['progressbar'] = 'Progress bar';
$string['property:action'] = 'Event action';
$string['property:component'] = 'Event component';
$string['property:crud'] = 'Event CRUD';
$string['property:eventname'] = 'Event name';
$string['property:target'] = 'Event target';
$string['promointro'] = 'The add-on for _Level up!_ with features that can encourage learners to achieve their full potential!';
$string['promorulesdidyouknow'] = 'Did you know that with <em>Level up! Plus</em> students can receive points for <em>completing courses</em> and <em>activities</em>, or even receive points according to their <em>grades</em>? <a href="{$a->url}">Discover more here</a>.';
$string['participatetolevelup'] = 'Participate in the course to gain experience points and level up!';
$string['rank'] = 'Rank';
$string['ranking'] = 'Ranking';
$string['ranking_help'] = 'The rank is the absolute position of the current user in the ladder. The relative rank is the difference in experience points between a user and their neighbours.';
$string['reallydeleteuserstate'] = 'Deleting a user is only useful to remove them from the ladder. For any other reasons, we recommend setting their points to 0 instead. Note that deleting them does not affect their ability to earn points in the future.

Importantly, when using _Level up!_ for the whole site, deleting them will make them disappear from the report, in which case you will not be able to re-assign them points. However, if you are using _Level up!_ per course, the student may still appear in the report if they are enrolled in the course.

Do you really want to delete the points of this user?';
$string['reallyresetallcoursestodefaults'] = 'Really reset all courses rules to the default rules? This action is not reversible.';
$string['reallyresetcourserulestodefaults'] = 'Really reset the course rules to the default rules? This action is not reversible.';
$string['reallyresetdata'] = 'Really reset the levels and points of everyone in this course?';
$string['reallyresetgroupdata'] = 'Really reset the levels and points of everyone in this group?';
$string['reallyreverttopluginsdefaults'] = 'Really reset the default rules to the defaults suggested by the plugin? This action is not reversible.';
$string['recentrewards'] = 'Recent rewards';
$string['reportisempty'] = 'The report is empty, student have yet to earn points.';
$string['reportisemptyenrolstudents'] = 'The report is empty, have students been enrolled in this course?';
$string['resetcoursedata'] = 'Reset course data';
$string['resetallcoursestodefaults'] = 'Reset all courses to defaults';
$string['resetallcoursestodefaultsintro'] = 'Click the button below to reset all courses to the above defaults.';
$string['resetcourserulestodefaults'] = 'Reset course rules to defaults';
$string['resetgroupdata'] = 'Reset group data';
$string['reward'] = 'Reward';
$string['requires'] = 'Requires';
$string['reverttopluginsdefaults'] = 'Revert to plugin\'s defaults';
$string['reverttopluginsdefaultsintro'] = 'Use the button below if you would like to revert the above defaults to the plugin\'s defaults. This does not affect the rules in existing courses.';
$string['rule'] = 'Rule';
$string['rule:contains'] = 'contains';
$string['rule:eq'] = 'is equal to';
$string['rule:eqs'] = 'is strictly equal to';
$string['rule:gt'] = 'is greater than';
$string['rule:gte'] = 'is greater or equal to';
$string['rule:lt'] = 'is less than';
$string['rule:lte'] = 'is less or equal to';
$string['rule:regex'] = 'matches the regex';
$string['rulecm'] = 'Activity or resource';
$string['rulecm_help'] = 'This condition is met when the event occurs in the activity or resource specified.';
$string['rulecmdesc'] = 'The activity or resource is \'{$a->contextname}\'.';
$string['rulecmdescwithcourse'] = 'The activity or resource is: \'{$a->contextname}\' in \'{$a->coursename}\'.';
$string['ruleevent'] = 'Specific event';
$string['ruleeventdesc'] = 'The event is \'{$a->eventname}\'';
$string['ruleproperty'] = 'Event property';
$string['rulepropertydesc'] = 'The property \'{$a->property}\' {$a->compare} \'{$a->value}\'.';
$string['ruleset'] = 'Set of conditions';
$string['ruleset:all'] = 'ALL of the conditions are true';
$string['ruleset:any'] = 'ANY of the conditions are true';
$string['ruleset:none'] = 'NONE of the conditions are true';
$string['searchandselectcourse'] = 'Search and select a course';
$string['searchandselectmodule'] = 'Search and select an activity or resource';
$string['send'] = 'Send';
$string['shortcode:xpbadge'] = 'The badge matching the current user\'s level.';
$string['shortcode:xpladder'] = 'Display a portion of the ladder.';
$string['shortcode:xpladder_help'] = '
By default, a portion of the ladder surrounding the current user will be displayed.

```
[xpladder]
```

To display the top 10 students instead of the neighbours of the current user, set the parameter `top`. You can optionally set the number of users to display like so `top=20`.

```
[xpladder top]
[xpladder top=15]
```

A link to the full ladder will automatically be displayed below the table, if you do not want to display such link, add the argument `hidelink`.

```
[xpladder hidelink]
```

By default, the table does not include the progress column which displays the progress bar. If such column has been selected in the additional colums in the ladder\'s settings, you can use the argument `withprogress` to display it.

```
[xpladder withprogress]
```

Note that when a course is using groups, the ladder will best guess which group to display the ladder of.
';
$string['shortcode:xpiflevel'] = 'Display the content when the current user\'s level matches.';
$string['shortcode:xpiflevel_help'] = '
Refer to the examples below to format this shortcode. When a level is strictly specified, the content will be displayed regardless of the other rules.
The _greater_ and _less than_ rules must all match for the content to be displayed. Watch out as that may sometimes result in the content to never be displayed!
Note that teachers, or otherwise users with editing capabilities, will always see everything.

```
[xpiflevel 1 3 5]
    Displayed if the user\'s level is exactly 1, 3 or 5.
[/xpiflevel]

[xpiflevel >3]
    Displayed if the user\'s level is greater than 3.
[/xpiflevel]

[xpiflevel >=3]
    Displayed if the user\'s level is greater or equal to 3.
[/xpiflevel]

[xpiflevel >=10 <20 30]
    Displayed if the user\'s level is greater or equal to 10 AND is strictly less than 20
    OR is exactly equal to 30.
[/xpiflevel]

[xpiflevel <=10 >=20]
    Never displayed because the user\'s level can never be less or equal to 10 AND more or equal to 20.
[/xpiflevel]
```

Note that these shortcodes CANNOT be nested within one another.
';
$string['shortcode:xplevelname'] = 'Display the level name.';
$string['shortcode:xplevelname_help'] = '
By default the tag displays the name of the current user\'s level.
Alternatively, you can use the `level` argument to display the name of a specific level.

```
[xplevelname]
[xplevelname level=5]
```

If the `level` argument is provided and the level does not exist, nothing will be displayed.
';
$string['shortcode:xpprogressbar'] = 'The current user\'s progress bar towards the next level.';
$string['someoneelse'] = 'Someone else';
$string['somethinghappened'] = 'Something happened';
$string['taskcollectionloggerpurge'] = 'Purge collection logs';
$string['total'] = 'Total';
$string['timebetweensameactions'] = 'Time required between identical actions';
$string['timebetweensameactions_help'] = 'The minimum time required before an action that already happened previously is accepted again. An action is considered identical if it was placed in the same context and object, reading a forum post will be considered identifical if the same post is read again. When this value is empty, or equals to zero, it does not apply.';
$string['timeformaxactions'] = 'Time frame for max. actions';
$string['timeformaxactions_help'] = 'The time frame (in seconds) during which the user should not exceed a maximum number of actions.';
$string['tinytimenow'] = 'now';
$string['tinytimeseconds'] = '{$a}s';
$string['tinytimeminutes'] = '{$a}m';
$string['tinytimehours'] = '{$a}h';
$string['tinytimedays'] = '{$a}d';
$string['tinytimeweeks'] = '{$a}w';
$string['tinytimewithinayearformat'] = '%b %e';     // No, this is not a regex! @codingStandardsIgnoreLine.
$string['tinytimeolderyearformat'] = '%b %Y';
$string['value'] = 'Value';
$string['valuessaved'] = 'The values have been successfully saved.';
$string['visualsintro'] = 'Upload images to customise the appearance of the levels.';
$string['wherearexpused'] = 'Where are points used?';
$string['wherearexpused_desc'] = 'When set to \'In courses\', the points gained will only account for the course in which the block was added to. When set to \'For the whole site\', a user will "level up" in the site rather than selectively per course, all the points gained throughout the site will be used.';
$string['updateandpreview'] = 'Update and preview';
$string['urlaccessdeprecated'] = 'Access via this URL is deprecated, please update your links.';
$string['usealgo'] = 'Use the algorithm';
$string['usecustomlevelbadges'] = 'Use custom level badges';
$string['usecustomlevelbadges_help'] = 'When set to yes, you must provide an image for each level.';
$string['unknowneventa'] = 'Unknown event ({$a})';
$string['when'] = 'When';
$string['whoops'] = 'Whoops!';
$string['wewillreplyat'] = 'We will reply at: _{$a}_.';
$string['xp:addinstance'] = 'Add a new block';
$string['xp:earnxp'] = 'Earning points';
$string['xp:manage'] = 'Manage all aspects of experience points';
$string['xp:myaddinstance'] = 'Add the block to my dashboard';
$string['xp:view'] = 'View the block and its related pages';
$string['xptogo'] = '[[{$a}]] to go';
$string['xpgaindisabled'] = 'Points gain disabled';
$string['youreachedlevel'] = 'You have reached the level:';
$string['youreachedlevela'] = 'You have reached level {$a}!';
$string['yourmessage'] = 'Your message';
$string['yourownrules'] = 'Your own rules';

// Deprecated since 3.0.0.
$string['addrulesformhelp'] = 'The last column defines the amount of experience points gained when the criteria is met.';
$string['changelevelformhelp'] = 'If you change the number of levels, the custom level badges will be temporarily disabled to prevent levels without badges. If you change the level count go to the page \'Visuals\' to re-enable the custom badges once you have saved this form.';
$string['enablelogging'] = 'Enable logging';
$string['levelswillbereset'] = 'Warning! Saving this form will recalculate the levels of everyone!';
$string['viewtheladder'] = 'View the ladder';
$string['xp'] = 'Experience points';
$string['xprequired'] = 'XP required';

// Deprecated since 3.1.0.
$string['promocontactintro'] = 'Contact us for more information. We don\'t bite and we reply quickly!';
$string['promocontactus'] = 'Get in touch';
$string['promoemailusat'] = 'E-mail us at _levelup@branchup.tech_.';
$string['promoerrorsendingemail'] = 'Ouch! We could not send the message... please e-mail us directly at: {$a}. Thanks!';
$string['promoifpreferemailusat'] = 'Psst! If you prefer, e-mail us directly at _{$a}_.';
$string['promoyourmessagewassent'] = 'Thank you, your message was sent. We will get back to you very shortly.';

// Deprecated since 3.8.1.
$string['rulesformhelp'] = '<p>This plugin is making use of the events to attribute points to actions performed by the students. You can use the form below to add your own rules and view the default ones.</p>
<p>It is advised to check the plugin\'s <a href="{$a->log}">log</a> to identify what events are triggered as you perform actions in the course, and also to read more about events themselves: <a href="{$a->list}">list of all events</a>, <a href="{$a->doc}">developer documentation</a>.</p>
<p>Finally, please note that the plugin always ignores:
<ul>
    <li>The actions performed by administrators, guests or non-logged in users.</li>
    <li>The actions performed by users not having the capability <em>block/xp:earnxp</em>.</li>
    <li>Repeated actions within a short time interval, to prevent cheating.</li>
    <li>Events that are flagged as <em>anonymous</em>, e.g. in an anonymous Feedback.</li>
    <li>And the events of educational level not equal to <em>Participating</em>.</li>
</ul>
</p>';
