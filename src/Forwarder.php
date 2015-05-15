<?php
namespace RgpJones\Lunchbot;

interface Forwarder
{
    public function setDebug($boolean);

    public function send($message);
}