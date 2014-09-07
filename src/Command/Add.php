<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 07/09/14
 * Time: 20:25
 */

namespace Command;
use Command;
use RotaManager;

class Add implements Command
{
    protected $rotaManager;
    protected $args = array();

    public function __construct(RotaManager $rotaManager, array $args = array())
    {
        $this->rotaManager = $rotaManager;
        $this->args = $args;
    }

    public function getUsage()
    {

    }

    public function run()
    {
        if (!isset($this->args[1])) {
            throw new \RunTimeException('No username found to add');
        }
        $this->rotaManager->addClubber($this->args[1]);
        return "{$this->args[1]} has been added to Lunchclub";
    }
} 