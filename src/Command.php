<?php
interface Command
{
    public function __construct(array $args = array());
    public function processArgs();
    public function getUsage();
    public function run();
}
