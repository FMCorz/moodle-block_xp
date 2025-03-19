@block @block_xp @javascript
Feature: The levels page allows customising the levels
  In order to customise my students experience
  As a teacher
  I can use the levels page

  Background:
    Given the following "courses" exist:
      | fullname  | shortname |
      | Course 1  | c1        |
    And the following "users" exist:
      | username | firstname | lastname |
      | s1       | Student   | One      |
      | t1       | Teacher   | One      |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |

  Scenario: The number of levels can be changed
    Given I am on the "c1" "block_xp > levels" page logged in as "t1"
    And I set the field "Number of levels" to "4"
    And I press tab
    And I press "Save changes"
    When I follow "Info"
    Then I should see "#1"
    And I should see "#4"
    And I should not see "#5"
    And I am on the "c1" "block_xp > info" page logged in as "s1"
    And I should see "#1"
    And I should see "#4"
    And I should not see "#5"

  Scenario: The points of the levels can be quickly edited
    Given I am on the "c1" "block_xp > levels" page logged in as "t1"
    And I press "Quick edit points"
    And I click on "Equal" "radio" in the "Quick edit points" "dialogue"
    And I set the field "Points per level" to "10"
    And I press tab
    When I press "Apply"
    Then the field "Start" in the "Level #1" "fieldset" matches value "0"
    And the field "Length" in the "Level #1" "fieldset" matches value "10"
    And the field "Start" in the "Level #2" "fieldset" matches value "10"
    And the field "Length" in the "Level #2" "fieldset" matches value "10"
    And the field "Start" in the "Level #3" "fieldset" matches value "20"
    And the field "Length" in the "Level #3" "fieldset" matches value "10"
