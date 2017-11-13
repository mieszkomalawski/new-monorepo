<?php

namespace BacklogBundle\Infrastructure\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpChannelManager
{
    const USER_ACTIVITY_QUEUE = 'user_activity';
    const USER_EXCHANGE = 'user';
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * AmqpChannelManager constructor.
     *
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param callable $consumer
     *
     * @return AMQPChannel
     */
    public function createUserNotificationConsumer(callable $consumer): AMQPChannel
    {
        $channel = $this->getChannel();
        $channel->basic_consume(self::USER_ACTIVITY_QUEUE, 'consumer', false, false, false, false, $consumer);

        return $channel;
    }

    /**
     * @return AMQPChannel
     */
    public function createUserNotificationPublisher(): AMQPChannel
    {
        return $this->getChannel();
    }

    /**
     * @return AMQPChannel
     */
    protected function getChannel(): AMQPChannel
    {
        $channel = $this->connection->channel();
        $channel->queue_declare(self::USER_ACTIVITY_QUEUE, false, true, false, false);
        $channel->exchange_declare(self::USER_EXCHANGE, 'direct', false, true, false);
        $channel->queue_bind(self::USER_ACTIVITY_QUEUE, self::USER_EXCHANGE);

        return $channel;
    }
}
