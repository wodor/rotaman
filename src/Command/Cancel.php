<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Dispatcher;
use DateTime;

class Cancel implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(RotaManager $rotaManager, Dispatcher $dispatcher)
    {
        $this->rotaManager = $rotaManager;
        $this->dispatcher = $dispatcher;
    }

    public function getUsage()
    {
        return '`cancel` [date]: Cancel lunchclub for today, or on date specified';
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

        $this->dispatcher->send($message . $date->format('l, jS F Y'));
    }
}
