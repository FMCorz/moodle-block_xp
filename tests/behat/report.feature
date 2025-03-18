@block @block_xp @javascript
Feature: A report displays students' progress
  In order to know how my students are doing
  As a teacher
  I can visit the report

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | s1       | Student   | One      | s1@example.com |
      | s2       | Student   | Two      | s2@example.com |
      | s3       | Student   | Three    | s3@example.com |
      | t1       | Teacher   | One      | t1@example.com |
    And the following "courses" exist:
      | fullname  | shortname | groupmode      |
      | Course 1  | c1        | 2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | s2       | c1     | student |
      | s3       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |

  Scenario: Report with visible groups and resetting data
    And the following "groups" exist:
      | name     | course | idnumber |
      | Group A  | c1     | ga |
      | Group B  | c1     | gb |
    And the following "group members" exist:
      | user | group |
      | s1   | ga    |
      | s2   | gb    |
    And the following "activity" exists:
      | course    | c1                             |
      | section   | 1                              |
      | activity  | forum                          |
      | name      | Test forum name                |
      | forumtype | Standard forum for general use |
      | intro     | Test forum description         |
    And I am on the "Course 1" "Course" page logged in as "s1"
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Discussion one    |
      | Message | This is the body  |
    And I am on the "Course 1" "Course" page logged in as "s2"
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Discussion two    |
      | Message | This is the body  |
    And I reply "Discussion two" post from "Test forum name" forum with:
      | Subject | Reply with text   |
      | Message | This is the body  |
    And I am on the "Course 1" "Course" page logged in as "s3"
    And I am on the "Course 1" "Course" page logged in as "t1"
    When I click on "Report" "link" in the "Level up!" "block"
    Then the following should exist in the "block_xp-report-table" table:
      | First name    | Level | Total |
      | Student One   | 1     | 63    |
      | Student Two   | 1     | 117   |
      | Student Three | 1     | 9     |
    And I set the field "Visible groups" to "Group A"
    And I should not see "Student Two"
    And the following should exist in the "block_xp-report-table" table:
      | First name  | Level | Total |
      | Student One | 1     | 63    |
    And I set the field "Visible groups" to "Group B"
    And I should not see "Student One"
    And the following should exist in the "block_xp-report-table" table:
      | First name  | Level | Total |
      | Student Two | 1     | 117   |
    And I follow "Reset group data" in the XP page menu
    And I press "Reset"
    And the following should exist in the "block_xp-report-table" table:
      | First name  | Level | Total |
      | Student Two | -     | -     |
    And I set the field "Visible groups" to "All participants"
    And the following should exist in the "block_xp-report-table" table:
      | First name    | Level | Total |
      | Student One   | 1     | 63    |
      | Student Two   | -     | -     |
      | Student Three | 1     | 9     |
    And I follow "Reset course data" in the XP page menu
    And I press "Reset"
    And the following should exist in the "block_xp-report-table" table:
      | First name    | Level | Total |
      | Student One   | -     | -     |
      | Student Two   | -     | -     |
      | Student Three | -     | -     |

  Scenario: Use the report to edit a student's total
    And I am on the "c1" "block_xp > report" page logged in as "t1"
    And the following should exist in the "block_xp-report-table" table:
      | First name    | Level | Total |
      | Student One   | -     | -     |
      | Student Two   | -     | -     |
    And I follow "Edit" for "Student One" in XP report
    And I wait until "Edit Student One" "dialogue" exists
    When I set the field "Total" to "512"
    And I press "Save changes"
    Then the following should exist in the "block_xp-report-table" table:
      | First name    | Level | Total |
      | Student One   | 4     | 512   |
      | Student Two   | -     | -     |

  Scenario: Use the report to delete an entry
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | s4       | Student   | Four     | s4@example.com |
    And the following "block_xp > xp" exist:
      | worldcontext | user | xp   |
      | c1           | s1   | 123  |
      | c1           | s4   | 456  |
    And I am on the "c1" "block_xp > report" page logged in as "t1"
    And the following should exist in the "block_xp-report-table" table:
      | First name     | Total   |
      | Student One    | 123     |
      | Student Four   | 456     |
    When I follow "Delete" for "Student Four" in XP report
    And I press "Delete"
    Then the following should exist in the "block_xp-report-table" table:
      | First name    | Total |
      | Student One   | 123   |
    And the following should not exist in the "block_xp-report-table" table:
      | First name    |
      | Student Four  |

  Scenario: Filter participants in the report
    Given the following "users" exist:
      | username | firstname | lastname |
      | s4       | Dylan     | Murphy   |
      | s5       | Maddy     | Cloud    |
      | s6       | Ralph     | Dalton   |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | s4       | c1     | student        |
      | s5       | c1     | student        |
      | s6       | c1     | student        |
    And I am on the "c1" "block_xp > report" page logged in as "t1"
    And I should see "Dylan Murphy"
    And I should see "Maddy Cloud"
    And I should see "Ralph Dalton"
    And I set the field "Filter participants" to "Dylan Murphy"
    When I press "Apply"
    Then I should see "Dylan Murphy"
    And I should not see "Maddy Cloud"
    And I should not see "Ralph Dalton"
    And I set the field "Filter participants" to "Clo"
    And I press "Apply"
    And I should not see "Dylan Murphy"
    And I should see "Maddy Cloud"
    And I should not see "Ralph Dalton"
    And I set the field "Filter participants" to "r d"
    And I press "Apply"
    And I should not see "Dylan Murphy"
    And I should not see "Maddy Cloud"
    And I should see "Ralph Dalton"
