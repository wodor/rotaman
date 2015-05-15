<?php

use RgpJones\Lunchbot\Application;
use RgpJones\Lunchbot\Forwarder\Slack;

require_once __DIR__ . '/vendor/autoload.php';

$config = simplexml_load_file(__DIR__ . '/config.xml');
if (!in_array($_POST['channel_id'], array((string) $config->channel_id, (string) $config->channel_id_test))) {
    throw new RunTimeException('Invalid channel source');
}

$testMode = (int) ($_POST['channel_id'] != (string) $config->channel_id);
$config->addChild('testMode', $testMode);
$storageFile = $testMode ? __DIR__ . '/.lunchbot-test' : __DIR__ . '/.lunchbot';

$app = new Application(
    [
        'config' => $config,
        'storage_file' => $storageFile,
        'forwarder' => new Slack($config)
    ]
);
$app->run();
