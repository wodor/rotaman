<?php
namespace Command;
use Command;
use RotaManager;

class Cancel implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`cancel`: Cancel lunchclub for the day (to-do)';
    }

    public function run()
    {

    }
}