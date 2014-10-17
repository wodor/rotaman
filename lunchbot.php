<?php

use RgpJones\Lunchbot\Application;

require_once __DIR__ . '/vendor/autoload.php';

$config = simplexml_load_file(__DIR__ . '/config.xml');
if (!in_array($_POST['channel_id'], array((string) $config->channel_id, (string) $config->channel_id_test))) {
    throw new RunTimException('Invalid channel source');
}

$config->addChild('testMode', $_POST['channel_id'] != (string) $config->channel_id);

$app = new Application(
    [
        'config'       => $config,
        'storage_file' => __DIR__ . '/.lunchbot',
    ]
);
$app->run();
