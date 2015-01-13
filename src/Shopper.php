<?php
namespace RgpJones\Lunchbot;

use JsonSerializable;

class Shopper implements JsonSerializable
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return $this->__toString();
    }
}