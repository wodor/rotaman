<?php
namespace RgpJones\Lunchbot;

use DateTime;

class RotaManager
{
    private $storage;

    private $rota;

    private $dateValidator;

    private $shopper;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $currentRota = isset($data['rota']) ? $data['rota'] : [];
		$cancelledDates = isset($data['cancelledDates']) ? $data['cancelledDates'] : [];
        $clubbers = isset($data['clubbers']) ? $data['clubbers'] : [];

        // Maintains shoppers in order as they are in current rota
        $this->shopper = new Shopper(array_unique(array_values($currentRota) + $clubbers));
        $this->dateValidator = new DateValidator($cancelledDates);
        $this->rota = new Rota($this->shopper, $this->dateValidator, $currentRota);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save([
                'clubbers' => $this->shopper->getShoppers(),
                'cancelledDates' => $this->dateValidator->getCancelledDates(),
                'rota' => $this->rota->getCurrentRota()
            ]);
        }
    }

    public function addShopper($name)
    {
        $this->shopper->addShopper($name);
    }

    public function getShoppers()
    {
        return $this->shopper->getShoppers();
    }

    public function generateRota(DateTime $date, $days)
    {
        return $this->rota->generate($date, $days);
    }

    public function getShopperForDate(DateTime $date)
    {
        return $this->rota->getShopperForDate($date);
    }

    public function skipShopperForDate(DateTime $date)
    {
        return $this->rota->skipShopperForDate($date);
    }

    public function cancelOnDate(DateTime $date)
    {
        return $this->rota->cancelOnDate($date);
    }
}
