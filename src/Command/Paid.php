<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use DateTime;
use RgpJones\Lunchbot\Slack;

class Paid implements Command
{
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
        return '`paid` [amount] <date>: Mark yourself as having paid for the current month. If amount is less than the '
            . 'full amount, also specify [amount] paid. Specify <date> of month if not for current month. e.g. '
            . '2014-12-01 for December';
    }

    public function run(array $args, $username)
    {
        $amount = null;
        if (isset($args[0])) {
            $amount = $this->checkAmount($args[0]);
        }

        $date = (isset($args[1]) ? new DateTime($args[1]) : new DateTime());

        if ($amount) {
            if ($this->rotaManager->shopperPaidForDate($date, $username, $amount)) {
                $response = 'Recorded as paid';
            } else {
                $response = "Failed to record as paid without an error";
            }
        } else {
            $response = sprintf(
                'You have paid £%.02f this month',
                $this->rotaManager->getAmountShopperPaidForDate(new DateTime(), $username)
            );
        }

        return $response;
    }

    protected function checkAmount($amount)
    {
        $amount = str_replace('£', '', $amount);
        if (!is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('You must provide a valid amount for payment');
        }
        return $amount;
    }
}