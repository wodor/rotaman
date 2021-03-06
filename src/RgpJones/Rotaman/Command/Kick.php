<?php
namespace RgpJones\Rotaman\Command;

use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;
use RgpJones\Rotaman\Slack;

class Kick implements Command
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
        return '`kick` <person>: Remove person from rota';
    }

    public function run(array $args, $username)
    {
        if (!isset($args[0])) {
            throw new \RunTimeException('No username found to leave');
        }
        $user = $args[0];

        $this->rotaManager->removeMember($user);

        $this->slack->send("{$username} kicked {$user} from rota");
    }
}
