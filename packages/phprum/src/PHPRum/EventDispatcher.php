<?php
declare(strict_types=1);

namespace PHPRum;

interface EventDispatcher
{
    public function dispatch(Event $event): void;
}
