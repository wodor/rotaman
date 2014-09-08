<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 07/09/14
 * Time: 19:48
 */

namespace Command;
use Command;
use RotaManager;

class Help implements Command
{
    private $rotaManager;

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '\`help\`: Display this help text';
    }

    public function run()
    {
        echo "/lunchbot <command>\n";
        foreach ($this->getCommands() as $command) {
            echo $command->getUsage() . "\n";
        }
        return null;
    }

    protected function getCommands()
    {
        $commands = array();

        $dir = Dir(__DIR__);
        while ($entry = $dir->read()) {
            if (preg_match('/^(?P<name>.*).php$/', $entry, $match)) {
                require_once $entry;
                $command = 'Command\\' . $match[1];
                $commands[] = new $command($this->rotaManager);
            }
        }

        return $commands;
    }
} 