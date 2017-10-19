<?php


namespace BacklogBundle\Event;


use BacklogBundle\EventWrapper;
use BacklogBundle\Service\UserNotificationProducer;
use PHPRum\DomainModel\Backlog\Event\ItemAdded;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserActivitySubscriber implements EventSubscriberInterface
{
    /** @var  UserNotificationProducer */
    private $userNotificationProducer;

    /**
     * UserActivitySubscriber constructor.
     * @param UserNotificationProducer $userNotificationProducer
     */
    public function __construct(UserNotificationProducer $userNotificationProducer)
    {
        $this->userNotificationProducer = $userNotificationProducer;
    }

    public static function getSubscribedEvents()
    {
        return [
            ItemAdded::NAME => 'onItemAdded'
        ];
    }

    public function onItemAdded(EventWrapper $itemAdded)
    {
        /** @var ItemAdded $itemAddedEvent */
        $itemAddedEvent = $itemAdded->getInternalEvent();
        $this->userNotificationProducer->publish('New item ' . $itemAddedEvent->getItemName() . ' added to backlog');
    }

}