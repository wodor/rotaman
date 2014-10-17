<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use DateTime;
use RgpJones\Lunchbot\Slack;

class Cancel implements Command
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
        return '`cancel` [date]: Cancel lunchclub for today, or on date specified';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[1])
            ? new DateTime($args[1])
            : new DateTime();

        if ($this->rotaManager->cancelOnDate($date)) {
            $message = 'Lunchclub has been cancelled on ';
        } else {
            $message = "Couldn't cancel Lunchclub on ";
        }

        $this->slack->send($message . $date->format('l, jS F Y'));
    }
}
