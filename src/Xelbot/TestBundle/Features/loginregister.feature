Feature: login and registration
  Check security

  Scenario: login
    Given I am on homepage
    When I follow "Sign in"
    And fill in "_username" with "donald.kreiger@labadie.com"
    And fill in "_password" with "test"
    And press "Login"
    Then I should see "Hello, paxton66"

  Scenario: disabled user
    Given I am on homepage
    When I follow "Sign in"
    And fill in "_username" with "mhamill"
    And fill in "_password" with "test"
    And press "Login"
    Then I should see "Account is disabled."

  Scenario: registration
    When I follow "Sign up"
    And I fill in the following:
      | Username | batman |
      | Email | batman@example.org |
      | Password | qwerty |
      | Repeat Password | qwerty |
    And I press "Create an account"
    Then I should see "We've sent a verification link to your email address. Please check your inbox and click the link to log in."

  Scenario: registration with used email
    When I follow "Sign up"
    And I fill in the following:
      | Username | mireille |
      | Email | mireille56@gmail.com |
      | Password | qwerty |
      | Repeat Password | qwerty |
    And I press "Create an account"
    Then I should see "Email already taken"
