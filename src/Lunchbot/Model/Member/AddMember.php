<?php

namespace Lunchbot\Model\Member;

use Lunchbot\Infrastructure\Command;
use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;

class AddMember implements Command
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
