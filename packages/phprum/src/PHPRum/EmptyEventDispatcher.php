<?php
declare(strict_types=1);

namespace PHPRum;

class EmptyEventDispatcher implements EventDispatcher
{
    public function dispatch(Event $event): void
    {
        // TODO: Implement dispatch() method.
    }
}
