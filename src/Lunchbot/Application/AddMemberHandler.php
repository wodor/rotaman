<?php

namespace Lunchbot\Application;

use Lunchbot\Entity\Member;
use Lunchbot\Entity\Members;
use Lunchbot\Infrastructure\Command;
use Lunchbot\Infrastructure\CommandHandler;
use Lunchbot\Infrastructure\EventBus;
use Lunchbot\Model\Member\AddMember;

class AddMemberHandler implements CommandHandler
{
    /**
     * @var EventBus
     */
    private $eventBus;
    /**
     * @var Members
     */
    private $members;

    public function __construct(EventBus $eventBus, Members $members)
    {
        $this->eventBus = $eventBus;
        $this->members = $members;
    }

    public function handle(Command $command)
    {
        if ($command instanceOf AddMember) {
            $member = Member::add($command->getMemberId(), $command->getName());
            $this->members->add($member);
            $this->eventBus->triggerAll($member->getEvents());
            $member->resetEvents();
        }
    }
}
