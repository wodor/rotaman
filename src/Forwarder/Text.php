<?php

namespace RgpJones\Lunchbot\Forwarder;

use SimpleXMLElement;
use RgpJones\Lunchbot\Forwarder;

class Text implements Forwarder
{
    /**
     * @var array
     */
    private $config;

    private $debug;
    private $messages;

    public function __construct(SimpleXMLElement $config)
    {
        $this->config = $config;
    }

    public function setDebug($boolean)
    {
        $this->debug = $boolean;
    }

    public function send($message)
    {
        $this->messages[] = $message;
        echo $message . "\n";
    }
} 