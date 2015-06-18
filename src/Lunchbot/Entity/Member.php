<?php

namespace Lunchbot\Entity;

use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;
use Lunchbot\Model\Member\MemberAdded;

class Member
{
    /**
     * @var MemberId
     */
    private $memberId;
    /**
     * @var Name
     */
    private $name;

    private $events = [];

    public static function add(
        MemberId $memberId,
        Name $name
    ) {
        $member = new self($memberId, $name);
        $member->events[] = new MemberAdded($memberId, $name);
        return $member;
    }

    protected function __construct(
        MemberId $memberId,
        Name $name
    ) {
        $this->memberId = $memberId;
        $this->name = $name;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function resetEvents()
    {
        $this->events = [];
    }


    /**
     * @return MemberId
     */
    public function getId()
    {
        return $this->memberId;
    }

    /**
     * @return Name
     */
    public function getName()
    {
        return $this->name;
    }


}
