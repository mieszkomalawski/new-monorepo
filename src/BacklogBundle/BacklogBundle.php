<?php

namespace BacklogBundle;

use PHPRum\StaticEventDispatcher;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BacklogBundle extends Bundle
{
    const MAX_ITEMS_PER_PAGE = 1000;

    public function boot()
    {
        /**
         * to avoid coupling domain to symfony compoent, we must pass our own wrapper
         */
        StaticEventDispatcher::setEventDispatcher(new SymfonyEventDispatcherBridge($this->container->get('event_dispatcher')));
    }


}
