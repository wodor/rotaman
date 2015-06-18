<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 11/06/15
 * Time: 17:26
 */

namespace Lunchbot\Infrastructure;


interface CommandHandler
{
    public function handle(Command $command);
}
