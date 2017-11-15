<?php

namespace BacklogBundle\Command;

use BacklogBundle\Infrastructure\Amqp\AmqpChannelManager;
use BacklogBundle\Service\UserNotification;
use BacklogBundle\Service\UserNotificationConsumer;
use BacklogBundle\Service\WebSocketServerFactory;
use Psr\Log\LoggerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('user:notification:server')
            ->setDescription('Start websocket server for user notifications');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->getContainer()->get('monolog.logger.console');
        /** @var AmqpChannelManager $amqpChannelManager */
        $amqpChannelManager = $this->getContainer()->get(AmqpChannelManager::class);
        $server = $this->getContainer()->get(WebSocketServerFactory::class)->create(
            $logger,
            $amqpChannelManager
        );
        $server->run();
    }
}
