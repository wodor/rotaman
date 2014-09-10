<?php
require_once 'src/Rota.php';
require_once 'src/RotaManager.php';
require_once 'src/Shopper.php';
require_once 'src/Storage.php';
require_once 'src/Command.php';

/**
 * <?xml version="1.0" encoding="utf8"?>
 * <config>
 *   <webhook>https://...</webhook>
 *   <token>...</token>
 *   <channel>...</channel>
 *   <channel_id>...</channel_id>
 * </config>
 */

$config = simplexml_load_file(__DIR__ . '/config.xml');

$slack = true;
if (!isset($_POST['token']) || $_POST['token'] != (string) $config->token
    || !isset($_POST['command']) || $_POST['command'] != '/lunchbot'
    || $_POST['channel_name'] != (string) $config->channel
    || $_POST['channel_id'] != (string) $config->channel_id) {

    $slack = false;
}
/**
 * [token] => ...
 * [team_id] => ...
 * [channel_id] => ...
 * [channel_name] => ...
 * [user_id] => ...
 * [user_name] => ...
 * [command] => ...
 * [text] => ...
 */
$command = 'help';
if ($slack) {
    $argv = explode(' ', trim($_POST['text']));
} else {
    array_shift($argv);
}

$command = (!empty($argv))
    ? $argv[0]
    : 'help';

$command = ucfirst(strtolower($command));

$file = "src/Command/{$command}.php";
if (!file_exists($file)) {
    die("Command not found\n");
}

require_once($file);

$command = 'Command\\' . $command;
$command = new $command(new RotaManager(new Storage(__DIR__ . '/.lunchbot')), array_merge($argv, $_POST));
$response = $command->run();

if (!is_null($response)) {
    if ($slack) {
        $content['username'] = 'Lunchbot';
        $content['text'] = $response;
        $content['icon_emoji'] = ':sandwich:';

        $payload = sprintf("payload=%s", json_encode($content));

        $hook = "";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, (string) $config->webhook);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);

        curl_close($ch);
    } else {
        echo "{$response}";
    }
}
