<?php

namespace Lunchbot\ValueObject\Member;

class MemberId
{
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function __toString()
    {
        return $this->username;
    }
}
