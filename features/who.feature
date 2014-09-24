Feature: Lunchclubbers can se who's turn it is today
  As a Lunchclubber
  I want to see who's turn it is to shop
  So I don't starve

  Scenario: Who command issued
    Given I am a lunchclub user
    And "test2" is shopping today
    When I type "/lunchclub who"
    Then I should see in the channel
    """
    Today's shopper is test2
    """
