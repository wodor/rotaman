<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Rota implements Command
{
    const MAX_DAYS = 20;

    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
        $this->args = $args;
    }

    public function getUsage()
    {
        return '`rota` [days]: Show the upcoming rota for the number of days specified';
    }

    public function run()
    {
        $days = isset($this->args[1])
            ? $this->args[1]
            : count($this->rotaManager->getShoppers());

        if ($days > self::MAX_DAYS) {
            throw new \LengthException('Cannot exceed more than ' . self::MAX_DAYS . ' days into the future.');
        }

        $rota = $this->rotaManager->generateRota(new \DateTime(), $days);
        $response = '';
        foreach ($rota as $date => $clubber) {
            $date = new \DateTime($date);
            $response .= "{$date->format('l')}: {$clubber}\n";
        }

        return $response;
    }
}
