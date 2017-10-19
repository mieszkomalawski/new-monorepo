<?php


namespace BacklogBundle;


use PHPRum\Event;
use PHPRum\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcher;

class SymfonyEventDispatcherBridge implements EventDispatcher
{
    /**
     * @var SymfonyEventDispatcher
     */
    private $eventDispatcher;

    /**
     * SymfonyEventDispatcher constructor.
     * @param SymfonyEventDispatcher $eventDispatcher
     */
    public function __construct(SymfonyEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Event $event
     */
    public function dispatch(Event $event) : void
    {
        $this->eventDispatcher->dispatch($event->getName(), new EventWrapper($event));
    }


}