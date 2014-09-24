<?php

use RgpJones\Lunchbot\Application;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application(
    [
        'config'       => simplexml_load_file(__DIR__ . '/config.xml'),
        'storage_file' => __DIR__ . '/.lunchbot',
    ]
);
$app->run();
