<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Slack;

class Join implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Slack
     */
    private $slack;

    public function __construct(RotaManager $rotaManager, Slack $slack)
    {
        $this->rotaManager = $rotaManager;
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`join`: Join lunch club';
    }

    public function run(array $args, $username)
    {
        if (!isset($username)) {
            throw new \RunTimeException('No username found to join');
        }
        $this->rotaManager->addShopper($username);

        $this->slack->send("{$args['user_name']} has been added to Lunchclub");
    }
}
