<?php

class RotaManager
{
    private $storage;

    private $clubbers = [];

    private $rota;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();
        if (isset($data['clubbers'])) {
            $this->clubbers = $data['clubbers'];
        }
        if (isset($data['rota'])) {
            $this->rota = $data['rota'];
        }
    }

    public function __destruct()
    {
        $this->storage->save(array(
            'clubbers' => $this->clubbers,
            'rota' => $this->rota
        ));
    }

    public function addClubber($name)
    {
        if (in_array($name, $this->clubbers)) {
            throw InvalidArgumentException('Clubber already exists in list');
        }
        $this->clubbers[] = $name;
    }

    public function getClubbers()
    {
        return $this->clubbers;
    }

    public function getRota(DateTime $date, $days)
    {
        $rota = new Rota(new Shopper($this->clubbers));
        $this->rota = $rota->generateRota($date, $days);
        return $this->rota;
    }


}
