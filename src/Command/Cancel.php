<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use DateTime;

class Cancel implements Command
{
    protected $rotaManager;
    protected $args = [];

    public function __construct(RotaManager $rotaManager, array $args = [])
    {
        $this->rotaManager = $rotaManager;
        $this->args = $args;
    }

    public function getUsage()
    {
        return '`cancel` [date]: Cancel lunchclub for today, or on date specified';
    }

    public function run()
    {
        $date = isset($this->args[1])
            ? new DateTime($this->args[1])
            : new DateTime();

        if ($this->rotaManager->cancelOnDate($date)) {
            $message = 'Lunchclub has been cancelled on ';
        } else {
            $message = "Couldn't cancel Lunchclub on ";
        }

        return $message . $date->format('l, jS F Y');
    }
}
