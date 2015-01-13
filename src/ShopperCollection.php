<?php
namespace RgpJones\Lunchbot;

use ArrayAccess;
use Countable;
use JsonSerializable;

class ShopperCollection implements ArrayAccess, Countable, JsonSerializable
{
    private $currentShopper;

    private $shoppers = [];

    /**
     * @param array $shoppers
     */
    public function __construct(array $shoppers)
    {
        foreach ($shoppers as $name) {

            $this->shoppers[] = $this->getShopper($name);
        }
    }

    /**
     * @param int $offset
     * @param string|Shopper $value
     */
    public function offsetSet($offset, $value)
    {
        $this->shoppers[$offset] = $this->getShopper($value);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->shoppers[$offset]);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->shoppers[$offset]);
    }

    /**
     * @param int $offset
     * @return Shopper
     */
    public function offsetGet($offset)
    {
        return $this->shoppers[$offset];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->shoppers);
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->shoppers;
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


    protected function getShopper($name)
    {
        if (!$name instanceOf Shopper) {
            $name = new Shopper($name);
        }
        return $name;
    }
}
