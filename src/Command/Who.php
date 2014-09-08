<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 07/09/14
 * Time: 19:49
 */

namespace Command;
use Command;
use RotaManager;

class Who implements Command
{
    protected $rotaManager;

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '\`who\`: Whose turn it is to shop';
    }

    public function run()
    {
        $shopper = $this->rotaManager->getShopperForDate(new \DateTime());
        return "Today's shopper is {$shopper}";
    }
} 