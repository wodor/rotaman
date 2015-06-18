<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 10/06/15
 * Time: 20:39
 */

namespace bootstrap;

use Behat\Behat\Context\SnippetAcceptingContext;

class MemberContext implements SnippetAcceptingContext
{
    /**
     * @Given There are no members in Lunchclub
     */
    public function thereAreNoMembersInLunchclub()
    {
        
    }

    /**
     * @When I add :arg1 to Lunchclub
     */
    public function iAddToLunchclub($arg1)
    {

    }

    /**
     * @Then the list of members contains :arg1
     */
    public function theListOfMembersContains($arg1)
    {
        throw new PendingException();
    }
}