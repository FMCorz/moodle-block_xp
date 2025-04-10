@block @block_xp @filter_shortcodes
Feature: The xpiflevel shortcode controls content appearance

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

  Scenario: Teachers can display content at specific levels
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel 2 10]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should not see "Abracadabra"

  Scenario: Teachers can display content at less than levels
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel <5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should see "Abracadabra"

  Scenario: Teachers can display content at less than or equal to levels
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel <=5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should see "Abracadabra"

  Scenario: Teachers can display content at greater than levels
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel >5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should not see "Abracadabra"

  Scenario: Teachers can display content at greater than or equal levels
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel >=5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should not see "Abracadabra"

  Scenario: Teachers can display content at level ranges
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel >=2 <5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should not see "Abracadabra"

  Scenario: Exclusive ranges do not display anything
    Given I am on the "PAGE1" "activity editing" page logged in as "t1"
    And I set the field "Page content" to "[xpiflevel <2 >=5]Abracadabra[/xpiflevel]"
    And I press "Save and display"
    When I am on the "PAGE1" "activity" page logged in as "s1"
    Then I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s2"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s3"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s4"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s5"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s6"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s7"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s8"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s9"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "s10"
    And I should not see "Abracadabra"
    And I am on the "PAGE1" "activity" page logged in as "sx"
    And I should not see "Abracadabra"
