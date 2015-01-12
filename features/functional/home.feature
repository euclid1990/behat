Feature: Framgia
  Test Framgia's home page

  Scenario: Testing for a Framgia's page
    Given I am on "/"
    Then I should see "フランジアが提供するソリューション"
    When I follow "ニュース"
    Then I should see "2015"