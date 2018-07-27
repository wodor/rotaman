<?php
namespace RgpJones\Rotaman\Command;

use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;
use DateTime;
use RgpJones\Rotaman\Slack;

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
        return '`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[0])
            ? new DateTime($args[0])
            : new DateTime();

        if ($this->rotaManager->cancelOnDate($date)) {
            $message = 'Rota has been cancelled on ';
        } else {
            $message = "Couldn't cancel rota on ";
        }

        $this->slack->send($message . $date->format('l, jS F Y'));
    }
}
