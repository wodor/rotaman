<?php
namespace RgpJones\Lunchbot;

use DateInterval;
use DateTime;

class Rota
{
    private $shopper;

    private $interval;

    private $currentRota;

    private $dateValidator;

    public function __construct(Shopper $shopper, DateValidator $dateValidator, array $currentRota = array())
    {
        $this->shopper = $shopper;
        $this->interval = new DateInterval('P1D');
        $this->dateValidator = $dateValidator;
        $this->currentRota = $currentRota;
    }

    public function generate(DateTime $date, $days)
    {
        $date = clone $date;

        $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        while (count($rota) < $days) {
            $date = $date->add($this->interval);
            $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        }
        $this->currentRota = array_merge($this->currentRota, $rota);

        return $rota;
    }

    public function getCurrentRota()
    {
        return $this->currentRota;
    }

    public function getShopperForDate(DateTime $date)
    {
        if (!isset($this->currentRota[$this->getDateKey($date)])) {
            $this->generate($date, 1);
        }

        return $this->currentRota[$this->getDateKey($date)];
    }

    public function skipShopperForDate(DateTime $date)
    {
        while (isset($this->currentRota[$this->getDateKey($date)])) {
            $currentDate = clone $date;
            $this->currentRota[$this->getDateKey($currentDate)] = $this->getNextShopper($date->add($this->interval));
        }
    }

    public function cancelOnDate(DateTime $cancelDate)
    {
        if ($this->dateValidator->isDateValid($cancelDate)) {
            $date = clone $cancelDate;
            if (isset($this->currentRota[$this->getDateKey($date)])) {
                $shopper = $this->currentRota[$this->getDateKey($date)];
                unset($this->currentRota[$this->getDateKey($date)]);
                while (isset($this->currentRota[$this->getDateKey($date->add($this->interval))])) {
                    $nextShopper = $this->currentRota[$this->getDateKey($date)];
                    $this->currentRota[$this->getDateKey($date)] = $shopper;
                    $shopper = $nextShopper;
                }
                $this->currentRota[$this->getDateKey($date)] = $shopper;
                $this->dateValidator->addCancelledDate($cancelDate);
            }
            return true;
        }
        return false;
    }

    protected function getNextShopper(\DateTime $date)
    {
        if (isset($this->currentRota[$this->getDateKey($date)])) {
            $shopper = $this->currentRota[$this->getDateKey($date)];
            $this->shopper->setCurrentShopper($this->currentRota[$this->getDateKey($date)]);

            return $shopper;
        } else {
            return $this->shopper->next();
        }
    }

    protected function getDateKey(DateTime $date)
    {
        return $this->dateValidator->getNextValidDate($date)->format('Y-m-d');
    }
}
