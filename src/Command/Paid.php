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

    /**
     * @var \RgpJones\Lunchbot\Slack
     */
    protected $slack;

    public function __construct(RotaManager $rotaManager, Slack $slack)
    {
        $this->rotaManager = $rotaManager;
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`paid` [amount]: Mark yourself as having paid for the current month. If amount is less than the full '
            . 'amount, also specify [amount] paid.';
    }

    public function run(array $args, $username)
    {
        $amount = null;
        if (isset($args[0])) {
            $amount = $this->checkAmount($args[0]);
        }

        if ($amount) {
            if ($this->rotaManager->shopperPaidForDate(new DateTime(), $username, $amount)) {
                $response = 'Recorded as paid';
            } else {
                $response = "Failed to record as paid without an error";
            }
        } else {
            $response = sprintf(
                'You have paid £%02f this month',
                $this->rotaManager->getShopperPaidAmountForDate(new DateTime(), $username)
            );
        }

        return $response;
    }

    protected function checkAmount($amount)
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('You must provide a valid amount for payment');
        }
        return $amount;
    }
}