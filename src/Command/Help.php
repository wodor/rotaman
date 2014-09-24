<?php
namespace RgpJones\Lunchbot\Command;

use Pimple\Container;
use RgpJones\Lunchbot\Command;

class Help implements Command
{
    /**
     * @var Container
     */
    private $commands;

    public function __construct(Container $commands)
    {
        $this->commands = $commands;
    }

    public function getUsage()
    {
        return '`help`: Display this help text';
    }

    public function run(array $args, $username)
    {
        $response = "/lunchbot <command>\n";

        foreach ($this->commands->keys() as $key) {
            $response .= $this->commands[$key]->getUsage() . "\n";
        }

        return $response;
    }
}
