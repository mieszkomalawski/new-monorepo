<?php

namespace BacklogBundle\Service;

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserNotification implements MessageComponentInterface
{
    /**
     * @var ConnectionInterface[]
     */
    protected $connections = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * UserNotification constructor.
     *
     * @param LoggerInterface $debugOutput
     */
    public function __construct(LoggerInterface $debugOutput)
    {
        $this->logger = $debugOutput;
    }

    public function onOpen(ConnectionInterface $currentConnection): void
    {
        $this->logger->info('connection opened');
        $this->connections[] = $currentConnection;
        $currentConnection->send('opened');
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->logger->info('connection closed');
        $conn->send('closed');
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $this->logger->info('error: '.$e->getMessage());
        $conn->send('error');
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->logger->info('message received: '.$msg);
        $f = function (ConnectionInterface $connection) use ($msg) {
            $connection->send($msg);
        };
        $this->executeOnAllOtherCollections($from, $f);
    }

    /**
     * @param $message
     */
    public function pushMessage($message)
    {
        foreach ($this->connections as $connection) {
            $connection->send($message);
        }
    }

    /**
     * @param ConnectionInterface $currentConnection
     *
     * @return array|ConnectionInterface[]
     */
    protected function getOtherConnections(ConnectionInterface $currentConnection)
    {
        return array_filter(
            $this->connections,
            function (ConnectionInterface $connection) use ($currentConnection) {
                return $connection !== $currentConnection;
            }
        );
    }

    /**
     * @param ConnectionInterface $currentConnection
     * @param callable            $f
     */
    protected function executeOnAllOtherCollections(ConnectionInterface $currentConnection, $f): void
    {
        $otherConnections = $this->getOtherConnections($currentConnection);
        foreach ($otherConnections as $otherConnection) {
            $f($otherConnection);
        }
    }
}
