<?php

namespace RgpJones\Rotaman;

class Storage
{
    protected $file;

    public function __construct($channel)
    {
        $file = __DIR__.'/../../../var/'.$channel.'.json';
        if (!file_exists($file) || !is_writable($file)) {
            throw new \InvalidArgumentException('Invalid file');
        }
        $this->file = $file;
    }

    public function load()
    {
        $data = [];
        if (file_exists($this->file)) {
            $data = json_decode(file_get_contents($this->file), true);
        }

        return $data;
    }

    public function save($data)
    {
        file_put_contents($this->file, json_encode($data));
    }
}
