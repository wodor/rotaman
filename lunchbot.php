<?php
require_once 'src/Rota.php';
require_once 'src/RotaManager.php';
require_once 'src/Shopper.php';
require_once 'src/Storage.php';

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

if (!empty($argv)) {
    $command = $argv[0];
}

$rotaManager = new RotaManager(new Storage('.lunchbot'));

switch ($command) {
    case 'who':
        $shopper = $rotaManager->getShopperForDate(new DateTime());
        $response = "Today's shopper is {$shopper}";
        break;

    case 'join':
        if (!$slack) {
            throw new RunTimeException('Cannot run this command outside of Slack');
        }
        $rotaManager->addClubber($_POST['user_name']);
        $response = "{$_POST['user_name']} has been added to Lunchclub";
        break;

    case 'ping':
        die('Pong!');
        break;

    case 'help':
    default:
        $response = <<<TEXT
/lunchbot <command>
*help*: Display help
*join*: Join Lunchclub
*who*:  Whose turn it is to go shopping
[more commands to be implemented]
TEXT;
        break;
}

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
    echo "{$response}\n";
}
