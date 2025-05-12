@block @block_xp @filter_shortcodes
Feature: The xpladder shortcode displays the leaderboard

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | s1       | Student   | One      |
      | s2       | Student   | Two      |
      | s3       | Student   | Three    |
      | s4       | Student   | Four     |
      | s5       | Student   | Five     |
      | s6       | Student   | Six      |
      | s7       | Student   | Seven    |
      | s8       | Student   | Eight    |
      | s9       | Student   | Nine     |
      | s10      | Student   | Ten      |
      | sx       | Student   | X        |
      | t1       | Teacher   | One      |
    And the following "courses" exist:
      | fullname  | shortname |
      | Course 1  | c1        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | s2       | c1     | student |
      | s3       | c1     | student |
      | s4       | c1     | student |
      | s5       | c1     | student |
      | s6       | c1     | student |
      | s7       | c1     | student |
      | s8       | c1     | student |
      | s9       | c1     | student |
      | s10      | c1     | student |
      | sx       | c1     | student |
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
      | g1    | s3   |
      | g1    | s4   |
      | g1    | s6   |
      | g1    | s7   |
      | g1    | s8   |
      | g1    | s9   |
      | g2    | s5   |
      | g2    | s10  |
      | g3    | s3   |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |
    And the following "block_xp > xp" exist:
      | worldcontext | user | xp    |
      | c1           | s1   | 0     |
      | c1           | s2   | 120   |
      | c1           | s3   | 276   |
      | c1           | s4   | 479   |
      | c1           | s5   | 742   |
      | c1           | s6   | 1085  |
      | c1           | s7   | 1531  |
      | c1           | s8   | 2110  |
      | c1           | s9   | 2863  |
      | c1           | s10  | 3842  |
    And the following "activities" exist:
      | activity | course | name   | idnumber  |
      | page     | c1     | Page 1 | PAGE1     |

  Scenario: Teachers can display the leaderboard
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpladder]"
    When I press "Save and display"
    Then the following should exist in the "table" table:
      | Participant  | Rank |
      | Student Ten  | 1    |
      | Student Nine | 2    |
    And I am on the "PAGE1" "activity" page logged in as "s1"
    And the following should exist in the "table" table:
      | Participant   | Rank |
      | Student Three | 8    |
      | Student Two   | 9    |
      | Student One   | 10   |
    And I should see "Go to full leaderboard"
    And the following should not exist in the "table" table:
      | Participant   |
      | Student Ten   |
      | Student Nine  |
      | Student Eight |
      | Student Seven |
      | Student Six   |
      | Student Five  |
      | Student Four  |

  Scenario: Teachers can display the top leaderboard
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpladder top=5]"
    When I press "Save and display"
    And I am on the "PAGE1" "activity" page logged in as "s1"
    Then the following should exist in the "table" table:
      | Participant   | Rank |
      | Student Ten   | 1    |
      | Student Nine  | 2    |
      | Student Eight | 3    |
      | Student Seven | 4    |
      | Student Six   | 5    |
    And I should see "Go to full leaderboard"
    And the following should not exist in the "table" table:
      | Participant   |
      | Student Five  |
      | Student Four  |
      | Student Three |
      | Student Two   |
      | Student One   |

  Scenario: The leaderboard ignores the neighbours setting with top
    Given the following "block_xp > config" exist:
     | worldcontext | name       | value |
     | c1           | neighbours | 2     |
    And I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpladder top=5]"
    When I press "Save and display"
    And I am on the "PAGE1" "activity" page logged in as "s1"
    Then the following should exist in the "table" table:
      | Participant   | Rank |
      | Student Ten   | 1    |
      | Student Nine  | 2    |
      | Student Eight | 3    |
      | Student Seven | 4    |
      | Student Six   | 5    |
    And I should see "Go to full leaderboard"

  Scenario: The leaderboard does not appear when disabled
    Given the following "block_xp > config" exist:
     | worldcontext | name         | value |
     | c1           | enableladder | 0     |
    And I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpladder]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then "table" "table" should not exist
    And I should not see "Student Two"
    And I should not see "Student Ten"
    And I should not see "Go to full leaderboard"

  Scenario: The leaderboard can hide the link to see more
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpladder hidelink]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then the following should exist in the "table" table:
      | Participant   | Rank |
      | Student Three | 8    |
      | Student Two   | 9    |
      | Student One   | 10   |
    And I should not see "Go to full leaderboard"
