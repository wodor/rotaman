<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Dispatcher;

class Join implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(RotaManager $rotaManager, Dispatcher $dispatcher)
    {
        $this->rotaManager = $rotaManager;
        $this->dispatcher = $dispatcher;
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

        $this->dispatcher->send("{$args['user_name']} has been added to Lunchclub");
    }
}
