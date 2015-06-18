<?php

namespace Lunchbot\Model\Member;

use Lunchbot\Infrastructure\Event;
use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;

class MemberAdded implements Event
{
    /**
     * @var MemberId
     */
    private $memberId;

    /**
     * @var
     */
    private $name;

    public function __construct(
        MemberId $memberId,
        Name $name
    ) {
        $this->name = $name;
        $this->memberId = $memberId;
    }

    /**
     * @return MemberId
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
