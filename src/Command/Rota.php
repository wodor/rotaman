<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Forwarder;

class Rota implements Command
{
    const MAX_DAYS = 20;

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
        return '`rota` [days]: Show the upcoming rota for the number of days specified';
    }

    public function run(array $args, $username)
    {
        $days = isset($args[0])
            ? $args[0]
            : count($this->rotaManager->getShoppers());

        if ($days > self::MAX_DAYS) {
            throw new \LengthException('Cannot exceed more than ' . self::MAX_DAYS . ' days into the future.');
        }

        $rota = $this->rotaManager->generateRota(new DateTime(), $days);
        $response = '';
        foreach ($rota as $date => $clubber) {
            $date = new DateTime($date);
            $response .= "{$date->format('l')}: {$clubber}\n";
        }

        $this->forwarder->send($response);
    }
}
