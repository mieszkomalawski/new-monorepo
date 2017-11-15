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
    const MESSAGE_READ_INTERVAL = 5;
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
    public function __construct(string $host, string $port)
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
        $loop->addPeriodicTimer(self::MESSAGE_READ_INTERVAL, [$userNotificationConsumer, 'read']);

        $logger->info('loop created');
        $webSock = new Server($this->host . ':' . $this->port, $loop);
        $logger->info('WebSocket Created');
        return new IoServer(new HttpServer(new WsServer($component)), $webSock, $loop);
    }
}