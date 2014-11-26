<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Slack;

class Whopaid implements Command
{
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
        return '`whopaid`: Report who has paid money this month. This only reports that a person has paid some amount '
            . 'of money.';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[0])
            ? new DateTime($args[0])
            : new DateTime();

        $paidShoppers = $this->rotaManager->getWhoPaidForDate($date);
        $this->slack->send(
            "Lunchclub payments for " . $date->format('%F')
                . $this->formatWhoPaid($paidShoppers)
        );
    }

    protected function formatWhoPaid($paidShoppers)
    {
        $shoppers = $this->rotaManager->getShoppers();
        $unpaidShoppers = array_diff($shoppers, $paidShoppers);

        $message = '';
        if (count($paidShoppers) == 0) {
            $message .= "No members have paid so far.\n";
        } else {
            $message .= sprintf("Members that have paid: %s\n", implode(', ', $paidShoppers));
        }

        if (count($unpaidShoppers) == 0) {
            $message .= "All members have paid.\n";
        } else {
            $message .= sprintf("Members that haven't paid: %s\n", implode(', ', $unpaidShoppers));
        }

        return $message;
    }
}
