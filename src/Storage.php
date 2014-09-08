<?php

class Storage
{
    protected $file;

    public function __construct($file)
    {
        /*
        if (!file_exists($file) || !is_writable($file) || !is_writable(dirname($file))) {
            throw new InvalidArgumentException('Invalid file');
        }*/
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
