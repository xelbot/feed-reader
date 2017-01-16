Feature: Contact us
  Check contact us functionality

  Scenario: Send contact us email
    Given I am on homepage
    When I follow "Contact"
    And  I fill in the following:
      | Name    | donald.kreiger             |
      | Email   | donald.kreiger@labadie.com |
      | Subject | test subject               |
      | Message | test message               |
    And press "Submit"
    Then I should see "Thank you for contacting us. We will respond to you as soon as possible."

  Scenario: Send empty contact form
    Given I am on homepage
    When I follow "Contact"
    And press "Submit"
    Then I should see "Name cannot be blank."
    And I should see "Email cannot be blank."
    And I should see "Subject cannot be blank."
    And I should see "Message cannot be blank."
