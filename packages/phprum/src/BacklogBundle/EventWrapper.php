<?php


namespace BacklogBundle;


use Symfony\Component\EventDispatcher\Event;

class EventWrapper extends Event
{
    /**
     * @var \PHPRum\Event
     */
    private $internalEvent;

    /**
     * EventWrapper constructor.
     * @param \PHPRum\Event $internalEvent
     */
    public function __construct(\PHPRum\Event $internalEvent)
    {
        $this->internalEvent = $internalEvent;
    }

    /**
     * @return \PHPRum\Event
     */
    public function getInternalEvent(): \PHPRum\Event
    {
        return $this->internalEvent;
    }
}