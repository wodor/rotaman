<?php
class Shopper
{
    private $currentShopper;
    private $shoppers;

    public function __construct(array $shoppers, $currentShopper = null)
    {
        if (empty($shoppers)) {
            throw new \InvalidArgumentException('No shoppers were provided');
        }
        if (!is_null($currentShopper) && !in_array($currentShopper, $shoppers)) {
            throw new \InvalidArgumentException('Current Shopper must be in shoppers list');
        }
        $this->currentShopper = $currentShopper;
        $this->shoppers = $shoppers;
    }

    public function setCurrentShopper($shopper)
    {
        $this->currentShoper = $shopper;
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
