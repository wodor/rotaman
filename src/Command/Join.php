<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Forwarder;

class Join implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Forwarder
     */
    private $forwarder;

    public function __construct(RotaManager $rotaManager, Forwarder $forwarder)
    {
        $this->rotaManager = $rotaManager;
        $this->forwarder = $forwarder;
    }

    public function getUsage()
    {
        return '`join`: Join lunch club';
    }

    public function run(array $args, $username)
    {
        if (!isset($username)) {
            throw new \RunTimeException('No username found to join');
        }
        $this->rotaManager->addShopper($username);

        $this->forwarder->send("{$args['user_name']} has been added to Lunchclub");
    }
}
