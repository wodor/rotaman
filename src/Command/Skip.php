<?php
namespace Command;
use Command;
use RotaManager;

class Skip implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`skip` <name>: Skip shopping duty for <name> (to-do)';
    }

    public function run()
    {

    }
}