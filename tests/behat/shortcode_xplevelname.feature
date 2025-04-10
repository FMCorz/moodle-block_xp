@block @block_xp @filter_shortcodes
Feature: The xplevelname shortcode displays a level's name

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | s1       | Student   | One      |
      | s2       | Student   | Two      |
      | t1       | Teacher   | One      |
    And the following "courses" exist:
      | fullname  | shortname |
      | Course 1  | c1        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | s2       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |
    And the following "block_xp > xp" exist:
      | worldcontext | user | xp    |
      | c1           | s1   | 10000 |
      | c1           | s2   | 120   |
    And the following "activities" exist:
      | activity | course | name   | idnumber  |
      | page     | c1     | Page 1 | PAGE1     |

  Scenario: Teachers can display the current user's level name
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xplevelname]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should see "Level #10"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should see "Level #2"

  Scenario: Teachers can display a specific level's name
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xplevelname level=5]"
    When I press "Save and display"
    Then I should see "Level #5"
    And I am on the "PAGE1" "activity" page logged in as "s1"
    And I should see "Level #5"
