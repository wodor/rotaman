<?php
namespace RgpJones\Lunchbot\Command;

use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;

class Rota implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
        $this->args = $args;
    }

    public function getUsage()
    {
        return '`rota`: Show the upcoming rota';
    }

    public function run()
    {
        $rota = $this->rotaManager->generateRota(new \DateTime(), count($this->rotaManager->getShoppers()));
        $response = '';
        foreach ($rota as $date => $clubber) {
            $date = new \DateTime($date);
            $response .= "{$date->format('l')}: {$clubber}\n";
        }

        return $response;
    }
}
