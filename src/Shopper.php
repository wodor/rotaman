<?php
namespace RgpJones\Lunchbot;

class Shopper
{
    private $currentShopper;
    private $shoppers = [];

    public function __construct(array $shoppers)
    {
        $this->shoppers = $shoppers;
    }

    public function setCurrentShopper($shopper)
    {
        if (!in_array($shopper, $this->shoppers)) {
            throw new \InvalidArgumentException('Current Shopper must be in shoppers list');
        }
        $this->currentShopper = $shopper;
    }

    public function getCurrentShopper()
    {
        return $this->currentShopper;
    }

    public function addShopper($name)
    {
        if (in_array($name, $this->shoppers)) {
            throw new \InvalidArgumentException("'{$name}' is already subscribed to Lunch Club");
        }

        $this->shoppers[] = $name;
    }

    public function removeShopper($name)
    {
        if (!in_array($name, $this->shoppers)) {
            throw new \InvalidArgumentException("'{$name}' is not subscribed to Lunch Club");
        }
        if ($this->currentShopper == $name) {
            $this->next();
        }
        unset($this->shoppers[array_search($name, $this->shoppers)]);

        $this->shoppers = array_values($this->shoppers);
    }

    public function getShoppers()
    {
        return $this->shoppers;
    }

    public function next()
    {
        $this->currentShopper = is_null($this->currentShopper)
            ? $this->shoppers[0]
            : $this->getShopperAfter($this->currentShopper);

        return $this->currentShopper;
    }

    public function getShopperAfter($name)
    {
        if (!in_array($name, $this->shoppers)) {
            throw new \RuntimeException("Shopper {$name} not found");
        }

        $index = array_search($name, $this->shoppers) + 1;
        if ($index >= count($this->shoppers)) {
            $index = 0;
        }

        return $this->shoppers[$index];
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
