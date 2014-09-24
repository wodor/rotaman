<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Skip implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Who
     */
    private $whoCommand;

    public function __construct(RotaManager $rotaManager, Who $whoCommand)
    {
        $this->rotaManager = $rotaManager;
        $this->whoCommand = $whoCommand;
    }

    public function getUsage()
    {
        return '`skip`: Skip current shopper, and pull remaining rota forwards';
    }

    public function run(array $args, $username)
    {
        $this->rotaManager->skipShopperForDate(new DateTime());
        $this->whoCommand->run([], $username);
    }
}
