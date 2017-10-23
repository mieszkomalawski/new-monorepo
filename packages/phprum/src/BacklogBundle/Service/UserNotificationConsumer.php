<?php


namespace BacklogBundle\Service;


use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Output\OutputInterface;

class UserNotificationConsumer
{
    /**
     * @var OutputInterface
     */
    private $output;

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
    public function __construct(UserNotification $userNotification, OutputInterface $output, AmqpChannelManager $amqpChannelManager)
    {
        $this->userNotification = $userNotification;
        $this->output = $output;
        $this->channel = $amqpChannelManager->createUserNotificationConsumer([$this, 'consumeMessage']);
    }

    public function read()
    {
        $this->output->writeln('reading messages');
        try{
            $this->channel->wait(null, true, 1);
        }catch(AMQPTimeoutException $e){

        }
        $this->output->writeln('reading messages done');
    }

    public function consumeMessage(AMQPMessage $message)
    {
        $this->output->writeln('Message from queue received');
        $this->userNotification->pushMessage($message->getBody());
    }

}