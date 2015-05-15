<?php

use RgpJones\Lunchbot\Application;
use RgpJones\Lunchbot\Forwarder\Text;

require_once __DIR__ . '/vendor/autoload.php';

$config = simplexml_load_file(__DIR__ . '/config.xml');

$storageFile = __DIR__ . '/.lunchbot-test';

$app = new Application(
    [
        'config' => $config,
        'storage_file' => $storageFile,
        'forwarder' => new Text($config)
    ]
);
$app->run();
