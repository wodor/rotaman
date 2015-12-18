<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 10/06/15
 * Time: 20:39
 */

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use bootstrap\ContextProvidingTrait;

class MemberContext implements SnippetAcceptingContext
{
    use ContextProvidingTrait;

    private $commandBus;

    private $eventBus;

    public function __construct()
    {
        $this->eventBus = new Lunchbot\Infrastructure\EventBus();

        $this->commandBus = new Lunchbot\Infrastructure\CommandBus([
            new Lunchbot\Application\AddMemberHandler(
                $this->eventBus,
                new Lunchbot\Persistent\MembersInMemory(
                    new \Everzet\PersistedObjects\InMemoryRepository(
                        new \Everzet\PersistedObjects\AccessorObjectIdentifier('getId')
                    )
                )
            )
        ]);
    }

    /**
     * @When I add user to Lunchclub:
     */
    public function iAddUserToLunchclub(TableNode $table)
    {
        $row = $table->getHash()[0];
        $command = new Lunchbot\Model\Member\AddMember(
            new \Lunchbot\ValueObject\Member\MemberId($row['username']),
            new \Lunchbot\ValueObject\Member\Name($row['name'])
        );

        $this->commandBus->dispatch($command);
    }

    /**
     * @Then the list of members contains user:
     */
    public function theListOfMembersContainsUser(TableNode $table)
    {
        $row = $table->getHash()[0];
        $event = new \Lunchbot\Model\Member\MemberAdded(
            new \Lunchbot\ValueObject\Member\MemberId($row['username']),
            new \Lunchbot\ValueObject\Member\Name($row['name'])
        );

        $events = $this->eventBus->getEvents();

        if ($events[0] != $event) {
            throw new \RuntimeException('The event was not found');
        }
    }
}