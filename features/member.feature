Feature: People can be members of Lunchclub
  As a member of lunchclub
  I can join the shopping rota and eat lunch

  Scenario: A member can be added to Lunchclub
    When I add user to Lunchclub:
      | username | name |
      | alfred | Alfred |
    Then the list of members contains user:
      | username | name |
      | alfred | Alfred |