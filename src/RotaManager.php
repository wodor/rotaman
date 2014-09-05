<?php

class RotaManager
{
    private $storage;

    private $shoppers = [];

    private $rota;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();
        if (isset($data['shoppers'])) {
            $this->shoppers = $data['shoppers'];
        }
        if (isset($data['rota'])) {
            $this->rota = $data['rota'];
        }
    }

    public function __destruct()
    {
        $this->storage->save(array(
            'shoppers' => $this->shoppers,
            'rota' => $this->rota
        ));
    }

    public function addClubber($name)
    {
        if (in_array($name, $this->shoppers)) {
            throw InvalidArgumentException('Clubber already exists in list');
        }
        $this->shoppers[] = $name;
    }

    public function getShoppers()
    {
        return $this->shoppers;
    }

    public function getRota(DateTime $date, $days)
    {
        $rota = new Rota(new Shopper($this->shoppers));
        $this->rota = $rota->generateRota($date, $days);
        return $this->rota;
    }

    public function getShopperForDate(DateTime $date)
    {
        if (empty($this->rota)) {
            $this->getRota($date, 3);
        }
        return $this->rota[$date->format('Y-m-d')];
    }
}
