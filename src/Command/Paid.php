<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use DateTime;
use RgpJones\Lunchbot\Slack;

class Paid implements Command
{
    const DEFAULT_AMOUNT = 30.00;
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    public function __construct(RotaManager $rotaManager)
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`paid` [amount]: Mark yourself as having paid for the current month. If amount is less than the full '
            . 'amount, also specify [amount] paid.';
    }

    public function run(array $args, $username)
    {
        $amount = isset($args[0]) ? $args[0] : self::DEFAULT_AMOUNT;

        if ($this->rotaManager->shopperPaid(new DateTime(), $username, $amount)) {
            $response = 'Recorded as paid';
        } else {
            $response = "Failed to record as paid without an error";
        }

        return $response;
    }
}