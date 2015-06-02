<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Slack;
use DateTime;

class Swap implements Command
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
        return '`swap` <toDate> [fromDate]: Swap shopping duty to specified date (Y-m-d).';
    }

    public function run(array $args, $username)
    {
        if (!isset($args[0])) {
            throw new RunTimeException('You must provide a date to swap to');
        }

        $toDate = new DateTime($args[0]);
        $fromDate = isset($args[1])
            ? new DateTime($args[1])
            : new DateTime();

        $this->rotaManager->swapMemberByDate($toDate, $fromDate);

        $this->slack->send("Members swapped for dates {$fromDate->format('l, jS F Y')} and "
            . "{$toDate->format('l, jS F Y')}");
    }
}
