<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 14/10/14
 * Time: 12:58
 */

namespace RgpJones\Lunchbot\Dispatcher;

use stdClass;
use RgpJones\Lunchbot\Dispatcher;

class Text implements Dispatcher
{
    /**
     * @var array
     */
    private $config;

    private $debug;
    private $messages;

    public function __construct(stdClass $config, $debug = false)
    {
        $this->config = $config;
        $this->debug = $debug;
    }

    public function send($message)
    {
        echo $message . "\n";
    }
} 