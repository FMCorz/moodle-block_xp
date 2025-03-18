@block @block_xp
Feature: The log pages contains logs of points
  In order to analyse the source of points
  As a teacher
  I can visit use the logs

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | s1       | Dylan     | Murphy   |
      | s2       | Maddy     | Cloud    |
      | s3       | Ralph     | Dalton   |
      | t1       | Teacher   | One      |
    And the following "courses" exist:
      | fullname  | shortname | groupmode |
      | Course 1  | c1        | 2         |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | s2       | c1     | student |
      | s3       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |
    And the following "block_xp > config" exist:
      | worldcontext | name             | value |
      | c1           | enablecheatguard | 0     |
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
    And I navigate to post "Discussion one" in "Test forum name" forum
    And I reply "Discussion one" post from "Test forum name" forum with:
      | Subject | Reply with text   |
      | Message | This is the body  |
    And I am on the "Course 1" "Course" page logged in as "s3"

  Scenario: Filter logs by participant name
    Given I am on the "c1" "block_xp > log" page logged in as "t1"
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

  Scenario: Filter logs by group
    Given I am on the "c1" "block_xp > log" page logged in as "t1"
    And I should see "Dylan Murphy"
    And I should see "Maddy Cloud"
    And I should see "Ralph Dalton"
    When I set the field "Visible groups" to "Group A"
    And I press "Go"
    Then I should see "Dylan Murphy"
    And I should not see "Maddy Cloud"
    And I should not see "Ralph Dalton"
    And I set the field "Visible groups" to "Group B"
    And I press "Go"
    And I should not see "Dylan Murphy"
    And I should see "Maddy Cloud"
    And I should not see "Ralph Dalton"
