<?php


namespace BacklogBundle\Service;


use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;

class WebSocketServerFactory
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * WebSocketServerFactory constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }


    public function create(LoggerInterface $logger, AmqpChannelManager $amqpChannelManager): IoServer
    {
        $component = new UserNotification($logger);
        $userNotificationConsumer = new UserNotificationConsumer(
            $component,
            $logger,
            $amqpChannelManager
        );
        $loop = Factory::create();
        $loop->addPeriodicTimer(5, [$userNotificationConsumer, 'read']);

        $logger->info('loop created');
        $webSock = new Server('127.0.0.1:8080', $loop);
        $logger->info('WebSocket Created');
        return new IoServer(new HttpServer(new WsServer($component)), $webSock, $loop);
    }
}