@block @block_xp
Feature: The leaderboard shows a ranking of students based on their points
  In order to create a competitive enviroment
  As a teacher
  I can use the leaderboard

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | s1       | Tony      | Pierce   | s1@example.com |
      | s2       | Nathaniel | Chambers | s2@example.com |
      | s3       | Allison   | Black    | s3@example.com |
      | s4       | Sara      | Kim      | s4@example.com |
      | t1       | Teacher   | One      | t1@example.com |
    And the following "courses" exist:
      | fullname  | shortname |
      | Course 1  | c1        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | s1       | c1     | student |
      | t1       | c1     | editingteacher |
    And the following "blocks" exist:
      | blockname | contextlevel | reference |
      | xp        | Course       | c1        |
    And the following "block_xp > xp" exist:
      | worldcontext | user | xp  |
      | c1           | s1   | 5   |
      | c1           | s2   | 50  |
      | c1           | s3   | 25  |
      | c1           | s4   | 100 |

  Scenario: Teachers and students can visit the leaderboard
    And I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 100   |
     | Nathaniel Chambers | 2    | 50    |
     | Allison Black      | 3    | 25    |
     | Tony Pierce        | 4    | 5     |
    And "This page is currently visible to students" "text" should exist in the "h3 span" "css_element"
    When I am on the "c1" "block_xp > leaderboard" page logged in as "s1"
    Then "h3 [title]" "css_element" should not exist
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Sara Kim           | 1    | 100   |
     | Nathaniel Chambers | 2    | 50    |
     | Allison Black      | 3    | 25    |
     | Tony Pierce        | 4    | 5     |

  @javascript
  Scenario: Teachers can customise the leaderboard settings
    And I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    And I follow "Page settings" in the XP page menu
    And I set the field "Anonymity" to "Hide participants identity"
    And I press "Save changes"
    And the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Someone else       | 1    | 100   |
     | Someone else       | 2    | 50    |
     | Someone else       | 3    | 25    |
     | Someone else       | 4    | 5     |
    When I am on the "c1" "block_xp > leaderboard" page logged in as "s1"
    Then the following should exist in the "table" table:
     | Participant        | Rank | Total |
     | Someone else       | 1    | 100   |
     | Someone else       | 2    | 50    |
     | Someone else       | 3    | 25    |
     | Tony Pierce        | 4    | 5     |

  @javascript
  Scenario: Teachers can disable the leaderboard
    And I am on the "c1" "Course" page logged in as "s1"
    And I should see "Leaderboard" in the "Level up!" "block"
    And I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    And "This page is currently visible to students" "text" should exist in the "h3 span" "css_element"
    And I follow "Page settings" in the XP page menu
    And I set the field "Enable the leaderboard" to "No"
    When I press "Save changes"
    Then "This page is not currently visible to students" "text" should exist in the "h3 span" "css_element"
    And I am on the "c1" "Course" page logged in as "s1"
    And I should not see "Leaderboard" in the "Level up!" "block"

  @javascript
  Scenario: Users can navigate to other pages
    Given the following "users" exist:
      | username  | firstname | lastname | email          |
      | s5        | Student   | N5       | s5@example.com |
      | s6        | Student   | N6       | s6@example.com |
      | s7        | Student   | N7       | s7@example.com |
      | s8        | Student   | N8       | s8@example.com |
      | s9        | Student   | N9       | s9@example.com |
      | s10       | Student   | N10      | s10@example.com |
      | s11       | Student   | N11      | s11@example.com |
      | s12       | Student   | N12      | s12@example.com |
      | s13       | Student   | N13      | s13@example.com |
      | s14       | Student   | N14      | s14@example.com |
      | s15       | Student   | N15      | s15@example.com |
      | s16       | Student   | N16      | s16@example.com |
      | s17       | Student   | N17      | s17@example.com |
      | s18       | Student   | N18      | s18@example.com |
      | s19       | Student   | N19      | s19@example.com |
      | s20       | Student   | N20      | s20@example.com |
      | s21       | Student   | N21      | s21@example.com |
      | s22       | Student   | N22      | s22@example.com |
      | s23       | Student   | N23      | s23@example.com |
      | s24       | Student   | N24      | s24@example.com |
      | s25       | Student   | N25      | s25@example.com |
      | s26       | Student   | N26      | s26@example.com |
      | s27       | Student   | N27      | s27@example.com |
      | s28       | Student   | N28      | s28@example.com |
      | s29       | Student   | N29      | s29@example.com |
      | s30       | Student   | N30      | s30@example.com |
      | s31       | Student   | N31      | s31@example.com |
    And the following "block_xp > xp" exist:
      | worldcontext | user  | xp   |
      | c1           | s5    | 5    |
      | c1           | s6    | 6    |
      | c1           | s7    | 7    |
      | c1           | s8    | 8    |
      | c1           | s9    | 9    |
      | c1           | s10   | 10   |
      | c1           | s11   | 11   |
      | c1           | s12   | 12   |
      | c1           | s13   | 13   |
      | c1           | s14   | 14   |
      | c1           | s15   | 15   |
      | c1           | s16   | 16   |
      | c1           | s17   | 17   |
      | c1           | s18   | 18   |
      | c1           | s19   | 19   |
      | c1           | s20   | 20   |
      | c1           | s21   | 21   |
      | c1           | s22   | 22   |
      | c1           | s23   | 23   |
      | c1           | s24   | 24   |
      | c1           | s25   | 25   |
      | c1           | s26   | 26   |
      | c1           | s27   | 27   |
      | c1           | s28   | 28   |
      | c1           | s29   | 29   |
      | c1           | s30   | 30   |
      | c1           | s31   | 31   |
    When I am on the "c1" "block_xp > leaderboard" page logged in as "t1"
    Then the following should exist in the "table" table:
     | Participant        |
     | Student N15        |
     | Student N31        |
    And the following should not exist in the "table" table:
     | Participant        |
     | Student N5         |
     | Student N10        |
     | Student N14        |
    And I follow "Next page"
    And the following should not exist in the "table" table:
     | Participant        |
     | Student N15        |
     | Student N31        |
    And the following should exist in the "table" table:
     | Participant        |
     | Student N5         |
     | Student N10        |
     | Student N14        |

    # Automatically opens page where student is visible.
    And I am on the "c1" "block_xp > leaderboard" page logged in as "s1"
    And the following should not exist in the "table" table:
     | Participant        |
     | Student N15        |
     | Student N31        |
    And the following should exist in the "table" table:
     | Participant        |
     | Student N5         |
     | Student N10        |
     | Student N14        |
     | Tony Pierce        |
    And I follow "Previous page"
    And the following should exist in the "table" table:
     | Participant        |
     | Student N15        |
     | Student N31        |
    And the following should not exist in the "table" table:
     | Participant        |
     | Student N5         |
     | Student N10        |
     | Student N14        |
     | Tony Pierce        |
