<?php

namespace Lunchbot\Infrastructure;

class EventBus
{
    private $events = [];

    public function trigger(Event $event)
    {
        $this->events[] = $event;
    }

    public function triggerAll(array $events)
    {
        foreach ($events as $event) {
            $this->trigger($event);
        }
    }

    public function getEvents()
    {
        return $this->events;
    }
}
