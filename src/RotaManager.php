<?php
namespace RgpJones\Lunchbot;

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

        $this->shopper = new Shopper(isset($data['clubbers']) ? $data['clubbers'] : []);
        $this->dateValidator = new DateValidator(isset($data['cancelledDates']) ? $data['cancelledDates'] : []);
        $this->rota = new Rota($this->shopper, $this->dateValidator, isset($data['rota']) ? $data['rota'] : []);
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

    public function generateRota(\DateTime $date, $days)
    {
        return $this->rota->generate($date, $days);
    }

    public function getShopperForDate(\DateTime $date)
    {
        return $this->rota->getShopperForDate($date);
    }

    public function skipShopperForDate(\DateTime $date)
    {
        return $this->rota->skipShopperForDate($date);
    }
}
