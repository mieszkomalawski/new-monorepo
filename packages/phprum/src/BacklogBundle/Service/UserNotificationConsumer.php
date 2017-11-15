<?php

namespace BacklogBundle\Service;

use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserNotificationConsumer
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserNotification
     */
    private $userNotification;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * UserNotificationConsumer constructor.
     */
    public function __construct(UserNotification $userNotification, LoggerInterface $output, AmqpChannelManager $amqpChannelManager)
    {
        $this->userNotification = $userNotification;
        $this->logger = $output;
        $this->channel = $amqpChannelManager->createUserNotificationConsumer([$this, 'consumeMessage']);
    }

    public function read(): void
    {
        $this->logger->info('reading messages');
        try {
            $this->channel->wait(null, true, 1);
        } catch (AMQPTimeoutException $e) {
        }
        $this->logger->info('reading messages done');
    }

    public function consumeMessage(AMQPMessage $message): void
    {
        $this->logger->info('Message from queue received');
        $this->userNotification->pushMessage($message->getBody());
    }
}
