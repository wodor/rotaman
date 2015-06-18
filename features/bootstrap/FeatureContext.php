<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use bootstrap\ContextProvidingTrait;

use RgpJones\Lunchbot\Application;
use RgpJones\Lunchbot\RotaManager;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    use ContextProvidingTrait;
    
    private $username;
    /**
     * @var Application
     */
    private $application;

    /**
     * @var Response
     */
    private $response;

    private $config;

    private $storage;

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
     * @BeforeScenario
     */
    public function setup()
    {
        $this->config = new SimpleXMLElement('<config/>');
        $this->config->webhook = 'http://example.com';
        $this->storage = tempnam(sys_get_temp_dir(), 'LC');

        $this->application = new Application(
            [
                'config'       => $this->config,
                'storage_file' => $this->storage,
                'debug'        => true
            ]
        );
    }


    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        unlink($this->storage);
    }

    /**
     * @Given I am a lunchclub user
     */
    public function iAmALunchclubUser()
    {
        $this->username = 'test';
        $this->application['rota_manager']->addMember($this->username);
    }

    /**
     * @When I type :command
     */
    public function iType($command)
    {
        $text = trim(strstr($command, ' '));

        $request = Request::create(
            '/',
            'POST',
            [
                'text'      => $text,
                'user_name' => $this->username,
            ]
        );

        $this->response = $this->application->handle($request);
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $string)
    {
        if (strpos($this->response->getContent(), (string) $string) === false) {
            throw new Exception(sprintf('Expected %s but got %s', $string, $this->response->getContent()));
        }
    }

    /**
     * @Given :username is shopping today
     */
    public function isShoppingToday($username)
    {
        $today = new DateTime;
        /** @var RotaManager $manager */
        $manager = $this->application['rota_manager'];
        $manager->addMember($username);
        $manager->setMemberForDate($today, $username);
    }

    /**
     * @Then I should see in the channel
     */
    public function iShouldSeeInTheChannel(PyStringNode $string)
    {
        $messages = $this->application['slack']->getMessages();

        if (strpos($messages[0], (string) $string) === false) {
            throw new Exception(sprintf('Expected %s but got %s', $string, $messages[0]));
        }
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
