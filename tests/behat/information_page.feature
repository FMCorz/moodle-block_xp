@block @block_xp
Feature: The information page can show students the list of students
  In order to convey information about the levels to my students
  As a teacher
  I can use the information page

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | s1       | Student   | One      | s1@example.com |
      | t1       | Teacher   | One      | t1@example.com |
    And the following "courses" exist:
      | fullname  | shortname |
      | Course 1  | c1        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | t1       | c1     | editingteacher |
    And I am on the "c1" "Course" page logged in as "t1"
    And I turn editing mode on
    And I add the "Level Up XP" block

  Scenario: Information page shows the levels
    When I am on the "c1" "block_xp > info" page logged in as "t1"
    Then the "title" attribute of "h3 [title]" "css_element" should contain "This page is currently visible to students"
    And I am on the "c1" "block_xp > info" page logged in as "s1"
    And "h3 [title]" "css_element" should not exist
    And I should see "#1"
    And I should see "#10"

  @javascript
  Scenario: Information page can contain additional information
    And I am on the "c1" "block_xp > info" page logged in as "t1"
    And I follow "Page settings" in the XP page menu
    And I set the field "Instructions" to "Get to level 10 and you'll be a champ!"
    And I press "Save changes"
    When I am on the "c1" "Course" page logged in as "s1"
    Then I should see "Info" in the "Level up!" "block"
    And I click on "Info" "link" in the "Level up!" "block"
    And I should see "Get to level 10 and you'll be a champ!"

  @javascript
  Scenario: Information page can be disabled
    And I click on "Info" "link" in the "Level up!" "block"
    And I follow "Page settings" in the XP page menu
    And I set the field "Enable info page" to "No"
    And I press "Save changes"
    When I am on the "c1" "Course" page logged in as "s1"
    Then I should not see "Info" in the "Level up!" "block"
