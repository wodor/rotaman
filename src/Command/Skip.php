<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Skip implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`skip`: Skip current shopper, and pull remaining rota forwards';
    }

    public function run()
    {
        $this->rotaManager->skipShopperForDate(new \DateTime());
        $command = new Who($this->rotaManager);

        return $command->run();
    }
}
