<?php

class RotaManager
{
    private $storage;

    private $rota;

    private $shopper;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $this->shopper = new \Shopper(isset($data['clubbers']) ? $data['clubbers'] : []);
        $this->rota = new \Rota($this->shopper, isset($data['rota']) ? $data['rota'] : []);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save(array(
                'clubbers' => $this->shopper->getShoppers(),
                'rota' => $this->rota->getCurrentRota()
            ));
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
