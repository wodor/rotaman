<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Who implements Command
{
    protected $rotaManager;

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`who`: Whose turn it is to shop';
    }

    public function run()
    {
        $shopper = $this->rotaManager->getShopperForDate(new \DateTime());

        return "Today's shopper is {$shopper}";
    }
}
