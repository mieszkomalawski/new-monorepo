<?php


namespace BacklogBundle\Service;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Output\OutputInterface;

class UserNotificationConsumer
{
    /**
     * @var \AMQPConnection
     */
    private $amqpConnection;

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
    public function __construct(UserNotification $userNotification, OutputInterface $output)
    {
        $this->amqpConnection = new AMQPStreamConnection(
            'localhost',
            '5672',
            'guest',
            'guest',
            '/'
        );
        $this->output = $output;
        $this->userNotification = $userNotification;
        $this->channel = $this->amqpConnection->channel();
        $queue = 'user_activity';
        $this->channel->queue_declare($queue, false, true, false, false);
        $exchange = 'user';
        $this->channel->exchange_declare($exchange, 'direct', false, true, false);
        $this->channel->queue_bind($queue, $exchange);
        $this->channel->basic_consume($queue, 'consumer', false, false, false, false, [$this, 'consumeMessage']);
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