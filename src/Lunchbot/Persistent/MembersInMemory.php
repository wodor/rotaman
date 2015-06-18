<?php

namespace Lunchbot\Persistent;

use Everzet\PersistedObjects\InMemoryRepository;
use Lunchbot\Entity\Member;
use Lunchbot\Entity\Members;

class MembersInMemory implements Members
{
    /**
     * @var InMemoryRepository
     */
    private $repository;

    public function __construct(InMemoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function add(Member $member)
    {
        $this->repository->save($member);
    }

    public function all()
    {
        return $this->repository->getAll();
    }
}
