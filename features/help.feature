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
`cancel` [date]: Cancel lunchclub for today, or on date specified (Y-m-d)
`help`: Display this help text
`join`: Join lunch club
`leave`: Leave lunch club (to-do)
`paid` <amount> [date]: Mark yourself as having paid <amount> for the current month. Specify [date] of month if not for current month. e.g. 2014-12-01 for December
`rota` [days]: Show the upcoming rota for the number of days specified
`skip`: Skip current shopper, and pull remaining rota forwards
`swap` <toDate> [fromDate]: Swap shopping duty to specified date (Y-m-d).
`who`: Whose turn it is to shop
`whopaid`: Report who has paid money this month. This only reports that a person has paid some amount of money.
    """

    Scenario: User sends incorrect command
      When I type "/lunchclub asiudhoaisdh"
      Then I should see
      """
/lunchbot <command>
`cancel` [date]: Cancel lunchclub for today, or on date specified (Y-m-d)
`help`: Display this help text
`join`: Join lunch club
`leave`: Leave lunch club (to-do)
`paid` <amount> [date]: Mark yourself as having paid <amount> for the current month. Specify [date] of month if not for current month. e.g. 2014-12-01 for December
`rota` [days]: Show the upcoming rota for the number of days specified
`skip`: Skip current shopper, and pull remaining rota forwards
`swap` <toDate> [fromDate]: Swap shopping duty to specified date (Y-m-d).
`who`: Whose turn it is to shop
`whopaid`: Report who has paid money this month. This only reports that a person has paid some amount of money.
    """
