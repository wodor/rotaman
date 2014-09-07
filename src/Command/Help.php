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
    public function __construct(RotaManager $rotaManager, array $args = array())
    {

    }

    public function getUsage()
    {

    }

    public function run()
    {
        echo <<<TEXT
/lunchbot <command>
*help*: Display help
*join*: Join Lunchclub
*who*:  Whose turn it is to go shopping
[more commands to be implemented]
TEXT;
        return null;
    }
} 