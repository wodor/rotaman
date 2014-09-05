<?php

class Rota
{
    /**
     * @var Shopper
     */
    private $shopper;

    private $interval;

    public function __construct(Shopper $shopper)
    {
        $this->shopper = $shopper;
        $this->interval = new DateInterval('P1D');
    }

    public function generateRota(DateTime $date, $days)
    {
        $date = clone $date;

        $rota[$this->getDateKey($date)] = $this->shopper->next();
        while (count($rota) < $days) {
            $date = $date->add($this->interval);
            $rota[$this->getDateKey($date)] = $this->shopper->next();
        }
        return $rota;
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
