<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Dispatcher;

class Who implements Command
{
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
        return '`who`: Whose turn it is to shop';
    }

    public function run(array $args, $username)
    {
        $shopper = $this->rotaManager->getShopperForDate(new DateTime());
        $this->dispatcher->send(sprintf('Today\'s shopper is %s', $shopper));
    }
}
