<?php
namespace RgpJones\Lunchbot;

class Shopper
{
    private $currentShopper;
    private $shoppers = [];

    public function __construct(array $shoppers, $currentShopper = null)
    {
        $this->shoppers = $shoppers;
        if (!is_null($currentShopper)) {
            $this->setCurrentShopper($currentShopper);
        }
    }

    public function setCurrentShopper($shopper)
    {
        if (!in_array($shopper, $this->shoppers)) {
            throw new \InvalidArgumentException('Current Shopper must be in shoppers list');
        }
        $this->currentShopper = $shopper;
    }

    public function addShopper($name)
    {
        if (in_array($name, $this->shoppers)) {
            throw new \InvalidArgumentException("'{$name}' is already subscribed to Lunch Club");
        }
        $this->shoppers[] = $name;
    }

    public function getShoppers()
    {
        return $this->shoppers;
    }

    public function next()
    {
        $nextOffset = 0;
        if (!is_null($this->currentShopper)) {
            $nextOffset = array_search($this->currentShopper, $this->shoppers) + 1;
            if ($nextOffset >= count($this->shoppers)) {
                $nextOffset = 0;
            }
        }
        $this->currentShopper = $this->shoppers[$nextOffset];

        return $this->currentShopper;
    }

    public function prev()
    {
        $prevOffset = array_search($this->currentShopper, $this->shoppers) - 1;
        if ($prevOffset < 0) {
            $prevOffset = count($this->shoppers) - 1;
        }
        $this->currentShopper = $this->shoppers[$prevOffset];

        return $this->currentShopper;
    }
}
