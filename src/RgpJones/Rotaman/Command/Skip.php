<?php
namespace RgpJones\Rotaman\Command;

use DateTime;
use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;

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
        return '`skip`: Skip current member, and pull remaining rota forwards';
    }

    public function run(array $args, $username)
    {
        $this->rotaManager->skipMemberForDate(new DateTime());
        $this->whoCommand->run([], $username);
    }
}
