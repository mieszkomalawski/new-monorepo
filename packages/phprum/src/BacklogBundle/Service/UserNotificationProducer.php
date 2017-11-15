<?php

namespace BacklogBundle\Service;

use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class UserNotificationProducer
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * UserNotificationProducer constructor.
     *
     * @param AMQPChannel $channel
     */
    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @param string $message
     */
    public function publish(string $message)
    {
        $this->channel->basic_publish(
            new AMQPMessage(
                $message,
                ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            ),
            AmqpChannelManager::USER_EXCHANGE
        );
    }
}
