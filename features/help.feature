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
`kick` <person>: Remove person from lunchclub
`leave`: Leave lunch club
`paid` <amount> [date]: Mark yourself as having paid <amount> for the current month. Specify [date] of month if not for current month. e.g. 2014-12-01 for December
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
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
`kick` <person>: Remove person from lunchclub
`leave`: Leave lunch club
`paid` <amount> [date]: Mark yourself as having paid <amount> for the current month. Specify [date] of month if not for current month. e.g. 2014-12-01 for December
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
`who`: Whose turn it is to shop
`whopaid`: Report who has paid money this month. This only reports that a person has paid some amount of money.
    """
