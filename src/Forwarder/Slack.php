<?php
namespace RgpJones\Lunchbot\Forwarder;

use SimpleXMLElement;
use RgpJones\Lunchbot\Forwarder;

class Slack implements Forwarder
{
    /**
     * @var array
     */
    private $config;

    private $debug;
    private $messages;

    public function __construct(\SimpleXMLElement $config)
    {
        $this->config = $config;
    }

    public function setDebug($boolean)
    {
        $this->debug = (bool) $boolean;
    }

    public function send($message)
    {
        $content['username'] = 'Lunchbot';
        $content['text'] = $message;
        $content['icon_emoji'] = ':sandwich:';

        if ((int) $this->config->testMode) {
            $content['channel'] = (string) $this->config->channel_id_test;
        }

        $payload = sprintf("payload=%s", json_encode($content));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, (string) $this->config->webhook);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        if (!$this->debug) {
            curl_exec($ch);
        }

        $this->messages[] = $message;
        curl_close($ch);
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
