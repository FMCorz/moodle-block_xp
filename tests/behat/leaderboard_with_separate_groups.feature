@block @block_xp
Feature: The leaderboard can isolate students with separate groups
  In order to divide the rankings
  As a teacher
  I can use groups

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | s1       | Tony      | Pierce   | s1@example.com |
      | s2       | Nathaniel | Chambers | s2@example.com |
      | s3       | Allison   | Black    | s3@example.com |
      | s4       | Sara      | Kim      | s4@example.com |
      | t1       | Teacher   | One      | t1@example.com |
    And the following "courses" exist:
      | fullname  | shortname | groupmode |
      | Course 1  | c1        | 1         |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | s2       | c1     | student |
      | s3       | c1     | student |
      | s4       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "groups" exist:
      | course | name | idnumber | description |
      | c1     | g1   | g1       | Group 1     |
      | c1     | g2   | g2       | Group 2     |
      | c1     | g3   | g3       | Group 3     |
    And the following "group members" exist:
      | group | user |
      | g1    | s1   |
      | g1    | s2   |
      | g2    | s3   |
      | g2    | s4   |
      | g3    | s1   |
      | g3    | s4   |
    And the following "groupings" exist:
      | course | name    | idnumber | description |
      | c1     | ging1   | ging1    | Grouping 1     |
    And the following "grouping groups" exist:
      | grouping | group |
      | ging1    | g1    |
      | ging1    | g2    |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |
    And the following "block_xp > xp" exist:
      | worldcontext | user | xp  |
      | c1           | s1   | 5   |
      | c1           | s2   | 10  |
      | c1           | s3   | 20  |
      | c1           | s4   | 50  |

  Scenario: Students can view their groups leaderboard
    When I am on the "c1" "block_xp > leaderboard" page logged in as "s1"
    And the field "Separate groups" matches value "g1"
    Then the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Nathaniel Chambers | 1    | 10    |
     | Tony Pierce        | 2    | 5     |
    And the following should not exist in the "table" table:
     | Participant        |
     | Allison Black      |
     | Sara Kim           |
    And the "Separate groups" select box should not contain "All participants"
    And the "Separate groups" select box should not contain "g2"
    And I set the field "Separate groups" to "g3"
    And I press "Go"
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 50    |
     | Tony Pierce        | 2    | 5     |
    And the following should not exist in the "table" table:
     | Participant        |
     | Allison Black      |
     | Nathaniel Chambers |

  Scenario: Teachers can view all groups
    When I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    And the field "Separate groups" matches value "All participants"
    Then the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 50    |
     | Allison Black      | 2    | 20    |
     | Nathaniel Chambers | 3    | 10    |
     | Tony Pierce        | 4    | 5     |
    And I set the field "Separate groups" to "g1"
    And I press "Go"
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Nathaniel Chambers | 1    | 10    |
     | Tony Pierce        | 2    | 5     |
    And the following should not exist in the "table" table:
     | Participant        |
     | Allison Black      |
     | Sara Kim           |
    And I set the field "Separate groups" to "g2"
    And I press "Go"
    And the following should not exist in the "table" table:
     | Participant        |
     | Nathaniel Chambers |
     | Tony Pierce        |
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 50    |
     | Allison Black      | 2    | 20    |
    And I set the field "Separate groups" to "g3"
    And I press "Go"
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 50    |
     | Tony Pierce        | 2    | 5     |
    And the following should not exist in the "table" table:
     | Participant        |
     | Allison Black      |
     | Nathaniel Chambers |

  Scenario: The groups available depend on the default grouping
    Given I am on the "c1" "course editing" page logged in as "t1"
    And I expand all fieldsets
    And I set the field "Default grouping" to "ging1"
    And I press "Save and display"
    When I am on the "c1" "block_xp > leaderboard" page logged in as "s1"
    Then "Separate groups" "field" should not exist
    And I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    And the "Separate groups" select box should contain "All participants"
    And the "Separate groups" select box should contain "g1"
    And the "Separate groups" select box should contain "g2"
    And the "Separate groups" select box should not contain "g3"
