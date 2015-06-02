<?php
namespace RgpJones\Lunchbot;

class MemberList
{
    private $members = [];

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    public function addMember($name)
    {
        if (in_array($name, $this->members)) {
            throw new \InvalidArgumentException("'{$name}' is already a member of Lunch Club");
        }

        $this->members[] = $name;
    }

    public function removeMember($name)
    {
        if (!in_array($name, $this->members)) {
            throw new \InvalidArgumentException("'{$name}' is not a member of Lunch Club");
        }

        unset($this->members[array_search($name, $this->members)]);

        $this->members = array_values($this->members);
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function next()
    {
        $member = array_shift($this->members);
        $this->members[] = $member;

        return $member;
    }

    public function prev()
    {
        $member = array_pop($this->members);
        array_unshift($this->members, $member);

        return $this->members[0];
    }


    public function getMemberAfter($name)
    {
        if (!in_array($name, $this->members)) {
            throw new \RuntimeException("Member {$name} not found");
        }

        $index = array_search($name, $this->members) + 1;
        if ($index >= count($this->members)) {
            $index = 0;
        }

        return $this->members[$index];
    }
}
