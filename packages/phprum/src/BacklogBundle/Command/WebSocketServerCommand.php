<?php


namespace BacklogBundle\Command;

use BacklogBundle\Service\UserNotification;
use BacklogBundle\Service\UserNotificationConsumer;
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
        $component = new UserNotification($output);
        $userNotificationConsumer = new UserNotificationConsumer($component, $output);
        $loop = Factory::create();
        $loop->addPeriodicTimer(5, [$userNotificationConsumer, 'read']);

        $output->writeln('loop created');
        $webSock = new Server('127.0.0.1:8080', $loop);
        $output->writeln('WebSocket Created');
        $server = new IoServer(new HttpServer(new WsServer($component)), $webSock, $loop);
        $output->writeln('starting server');
        $server->run();
    }
}