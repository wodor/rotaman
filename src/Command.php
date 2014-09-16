<?php
namespace RgpJones\Lunchbot;

interface Command
{
    public function __construct(RotaManager $rotaManager, array $args = array());
    public function getUsage();
    public function run();
}
