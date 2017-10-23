<?php


namespace BacklogBundle\Service;


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
     * @var OutputInterface
     */
    protected $debugOutput;

    /**
     * UserNotification constructor.
     * @param OutputInterface $debugOutput
     */
    public function __construct(OutputInterface $debugOutput)
    {
        $this->debugOutput = $debugOutput;
    }


    function onOpen(ConnectionInterface $currentConnection)
    {
        $this->debugOutput->writeln('connection opened');
        $this->connections[] = $currentConnection;
        $currentConnection->send('opened');
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->debugOutput->writeln('connection closed');
        $conn->send('closed');
        $conn->close();
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->debugOutput->writeln('error: ' . $e->getMessage());
        $conn->send('error');
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $this->debugOutput->writeln('message received: ' . $msg);
        $f = function (ConnectionInterface $connection) use ($msg) {
            $connection->send($msg);
        };
        $this->executeOnAllOtherCollections($from, $f);
    }

    /**
     * @param $message
     */
    public function pushMessage($message){
        foreach ($this->connections as $connection){
            $connection->send($message);
        }
    }

    /**
     * @param ConnectionInterface $currentConnection
     * @return array|ConnectionInterface[]
     */
    protected function getOtherConnections(ConnectionInterface $currentConnection)
    {
        return array_filter($this->connections,
            function (ConnectionInterface $connection) use ($currentConnection) {
                return $connection !== $currentConnection;
            });
    }

    /**
     * @param ConnectionInterface $currentConnection
     * @param callable $f
     */
    protected function executeOnAllOtherCollections(ConnectionInterface $currentConnection, $f): void
    {
        $otherConnections = $this->getOtherConnections($currentConnection);
        foreach ($otherConnections as $otherConnection) {
            $f($otherConnection);
        }
    }

}