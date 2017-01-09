Feature: Contact us
  Check contact us functionality

  Scenario: Send contact us email
    Given I am on homepage
    When I follow "Contact"
    And  I fill in the following:
      | contact_form[name]    | donald.kreiger             |
      | contact_form[email]   | donald.kreiger@labadie.com |
      | contact_form[subject] | test subject               |
      | contact_form[message] | test message               |
    And press "Submit"
    Then I should see "Thank you for contacting us. We will respond to you as soon as possible."