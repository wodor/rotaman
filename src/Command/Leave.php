<?php
namespace Command;
use Command;
use RotaManager;

class Leave implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`leave`: Leave lunch club (to-do)';
    }

    public function run()
    {

    }
}