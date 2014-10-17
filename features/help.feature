Feature: Get Command Help
  As a lunchclubber
  I want to use the help command
  To see what commands are available to me

  Scenario: User sends help command
    Given I am a lunchclub user
    When I type "/lunchclub help"
    Then I should see
    """
/lunchbot <command>
`cancel` [date]: Cancel lunchclub for today, or on date specified
`help`: Display this help text
`join`: Join lunch club
`leave`: Leave lunch club (to-do)
`rota` [days]: Show the upcoming rota for the number of days specified
`skip`: Skip current shopper, and pull remaining rota forwards
`swap` <name>: Swap shopping duty with <name> (to-do)
`who`: Whose turn it is to shop
    """

    Scenario: User sends incorrect command
      When I type "/lunchclub asiudhoaisdh"
      Then I should see
      """
/lunchbot <command>
`cancel` [date]: Cancel lunchclub for today, or on date specified
`help`: Display this help text
`join`: Join lunch club
`leave`: Leave lunch club (to-do)
`rota` [days]: Show the upcoming rota for the number of days specified
`skip`: Skip current shopper, and pull remaining rota forwards
`swap` <name>: Swap shopping duty with <name> (to-do)
`who`: Whose turn it is to shop
    """
