<?php


namespace BacklogBundle\Service;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class UserNotificationProducer
{
    /**
     * UserNotificationConsumer constructor.
     */
    public function __construct()
    {
        $this->amqpConnection = new AMQPStreamConnection(
            'localhost',
            '5672',
            'guest',
            'guest',
            '/'
        );

        $this->channel = $this->amqpConnection->channel();
        $queue = 'user_activity';
        $this->channel->queue_declare($queue, false, true, false, false);
        $exchange = 'user';
        $this->channel->exchange_declare($exchange, 'direct', false, true, false);
        $this->channel->queue_bind($queue, $exchange);
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
            'user'
        );
    }
}