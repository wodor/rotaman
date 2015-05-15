<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Forwarder;
use DateTime;

class Swap implements Command
{
    protected $rotaManager;
    protected $forwarder;

    public function __construct(RotaManager $rotaManager, Forwarder $forwarder)
    {
        $this->rotaManager = $rotaManager;
        $this->forwarder = $forwarder;
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

        $this->rotaManager->swapShopperByDate($toDate, $fromDate);

        $this->forwarder->send("Shoppers swapped for dates {$fromDate->format('l, jS F Y')} and "
            . "{$toDate->format('l, jS F Y')}");
    }
}
