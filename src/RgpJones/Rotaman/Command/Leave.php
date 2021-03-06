<?php
namespace RgpJones\Rotaman\Command;

use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;
use RgpJones\Rotaman\Slack;

class Leave implements Command
{
    protected $rotaManager;
    protected $slack;

    public function __construct(RotaManager $rotaManager, Slack $slack)
    {
        $this->rotaManager = $rotaManager;
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`leave`: Leave rota';
    }

    public function run(array $args, $username)
    {
        if (!isset($username)) {
            throw new \RunTimeException('No username found to leave');
        }
        $this->rotaManager->removeMember($username);

        $this->slack->send("{$username} has left the rota");
    }
}
