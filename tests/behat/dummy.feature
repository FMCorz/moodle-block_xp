@block @block_xp
Feature: The dummy test executes
  In order to test Level Up XP
  As an admin
  I should get access to the full test suite

  Scenario: Admin can load the page
    Given I am logged in as "admin"
    When I wait until "body" "css_element" exists
    Then "body" "css_element" should exist
