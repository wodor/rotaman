<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use RgpJones\Lunchbot\Application;
use RgpJones\Lunchbot\RotaManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
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
        $this->application['rota_manager']->addShopper($this->username);
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
        $manager->addShopper($username);
        while ($manager->getShopperForDate(clone $today) != $username) {
            $manager->skipShopperForDate(new DateTime);
        }
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
}
