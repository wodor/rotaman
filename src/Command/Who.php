<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Forwarder;

class Who implements Command
{
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
        return '`who`: Whose turn it is to shop';
    }

    public function run(array $args, $username)
    {
        $shopper = $this->rotaManager->getShopperForDate(new DateTime());
        $this->forwarder->send(sprintf('Today\'s shopper is %s', $shopper));
    }
}
