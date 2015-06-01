<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Kick implements Command
{
    protected $rotaManager;

    public function __construct(RotaManager $rotaManager)
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`kick` <person>: Remove person from lunchclub';
    }

    public function run(array $args, $username)
    {
        if (!isset($args[0])) {
            throw new \RunTimeException('No username found to leave');
        }
        $user = $args[0];

        $this->rotaManager->removeShopper($user);

        $this->slack->send("{$username} kicked {$user} from Lunchclub");
    }
}
