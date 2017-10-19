<?php


namespace PHPRum;


class StaticEventDispatcher
{
    /** @var  EventDispatcher  */
    static private $eventDispatcher;

    /**
     * @return EventDispatcher
     */
    public static function getEventDispatcher(): EventDispatcher
    {
        if(!self::$eventDispatcher instanceof EventDispatcher){
            self::$eventDispatcher = new EmptyEventDispatcher();
        }
        return self::$eventDispatcher;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public static function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        self::$eventDispatcher = $eventDispatcher;
    }


}