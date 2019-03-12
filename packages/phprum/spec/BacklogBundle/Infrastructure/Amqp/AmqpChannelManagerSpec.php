<?php

namespace spec\BacklogBundle\Infrastructure\Amqp;

use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class AmqpChannelManagerSpec extends ObjectBehavior
{
    public function it_creates_consumer_with_callable(AMQPStreamConnection $connection)
    {
        $this->beConstructedWith($connection);

        /** @var AMQPChannel $channel */
        $channel = (new Prophet())->prophesize(AMQPChannel::class);
        $connection->channel()->willReturn($channel);
        $consumer = function () {};
        $channel->basic_consume('user_activity', 'consumer', false, false, false, false, $consumer);

        $this->createUserNotificationConsumer($consumer)->shouldReturnAnInstanceOf(AMQPChannel::class);
    }
}
