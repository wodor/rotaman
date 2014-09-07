<?php

class Rota
{
    private $shopper;

    private $interval;

    private $currentRota;

    public function __construct(Shopper $shopper, array $currentRota = array())
    {
        $this->shopper = $shopper;
        $this->interval = new DateInterval('P1D');
        $this->currentRota = $currentRota;
    }

    public function generateRota(DateTime $date, $days)
    {
        $date = clone $date;

        $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        while (count($rota) < $days) {
            $date = $date->add($this->interval);
            $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        }
        return $rota;
    }

    public function getShopperForDate(DateTime $date)
    {
        if (!isset($this->currentRota[$date->format('Y-m-d')])) {
            $this->generateRota($date, count($this->clubbers));
        }
        return $this->currentRota[$date->format('Y-m-d')];
    }

    protected function getNextShopper(\DateTime $date)
    {
        return isset($this->currentRota[$this->getDateKey($date)])
            ? $this->currentRota[$this->getDateKey($date)]
            : $this->shopper->next();
    }

    protected function getNextValidDate(DateTime $date)
    {
        while (in_array($date->format('l'), array('Saturday', 'Sunday'))) {
            $date->add($this->interval);
        }
        return $date;
    }


    protected function getDateKey(DateTime $date)
    {
        return $this->getNextValidDate($date)->format('Y-m-d');
    }

}
