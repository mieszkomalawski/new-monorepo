<?php


namespace PHPRum;


interface EventDispatcher
{
    public function dispatch(Event $event) : void;
}