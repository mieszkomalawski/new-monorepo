<?php


namespace BacklogBundle\Command;

use BacklogBundle\Service\UserNotification;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
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
        $server = IoServer::factory(new HttpServer(new WsServer(new UserNotification($output))), 8080);
        $server->run();
    }
}