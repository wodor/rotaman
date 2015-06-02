<?php
namespace RgpJones\Lunchbot;

class Shopper
{
    private $shoppers = [];

    public function __construct(array $shoppers)
    {
        $this->shoppers = $shoppers;
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

        unset($this->shoppers[array_search($name, $this->shoppers)]);

        $this->shoppers = array_values($this->shoppers);
    }

    public function getShoppers()
    {
        return $this->shoppers;
    }

    public function next()
    {
        $shopper = array_shift($this->shoppers);
        $this->shoppers[] = $shopper;

        return $this->shoppers[0];
    }

    public function prev()
    {
        $shopper = array_pop($this->shoppers);
        array_unshift($this->shoppers, $shopper);

        return $this->shoppers[0];
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
}
