<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Forwarder;
use DateTime;

class Cancel implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Forwarder
     */
    private $forwarder;

    public function __construct(RotaManager $rotaManager, Forwarder $forwarder)
    {
        $this->rotaManager = $rotaManager;
        $this->forwarder = $forwarder;
    }

    public function getUsage()
    {
        return '`cancel` [date]: Cancel lunchclub for today, or on date specified (Y-m-d)';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[0])
            ? new DateTime($args[0])
            : new DateTime();

        if ($this->rotaManager->cancelOnDate($date)) {
            $message = 'Lunchclub has been cancelled on ';
        } else {
            $message = "Couldn't cancel Lunchclub on ";
        }

        $this->forwarder->send($message . $date->format('l, jS F Y'));
    }
}
