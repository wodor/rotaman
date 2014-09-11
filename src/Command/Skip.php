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
        return '`skip` <name>: Skip shopping duty for <name>, and pull remaining rota forwards';
    }

    public function run()
    {
        $this->rotaManager->skipShopperForDate(new \DateTime());
        require_once __DIR__ . '/Who.php';
        $command = new Command\Who($this->rotaManager);
        return $command->run();
    }
}