<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Join implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`join`: Join lunch club';
    }

    public function run()
    {
        if (!isset($this->args['user_name'])) {
            throw new \RunTimeException('No username found to join');
        }
        $this->rotaManager->addShopper($_POST['user_name']);

        return "{$_POST['user_name']} has been added to Lunchclub";
    }
}
