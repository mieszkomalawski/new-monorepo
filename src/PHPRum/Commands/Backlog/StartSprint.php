<?php

namespace PHPRum\Commands\Backlog;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use PHPRum\DomainModel\Backlog\Sprint;

class StartSprint
{
    /**
     * @var ObjectManager
     */
    private $entityNanager;

    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * CreateItem constructor.
     *
     * @param EntityManager $entityNanager
     */
    public function __construct(ObjectManager $entityNanager, Sprint $sprint)
    {
        $this->entityNanager = $entityNanager;
        $this->sprint = $sprint;
    }

    public function execute()
    {
        $nextSprint = $this->sprint->start();
        $this->entityNanager->persist($this->sprint);
        $this->entityNanager->persist($nextSprint);
        $this->entityNanager->flush();
    }
}
