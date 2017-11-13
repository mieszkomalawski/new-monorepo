<?php

namespace PHPRum\Commands\Backlog;

use BacklogBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use PHPRum\DomainModel\Backlog\Sprint;

class CreateSrpint
{
    /**
     * @var string
     */
    private $duration = '';

    /**
     * @var User
     */
    private $user;

    /**
     * @var ObjectManager
     */
    private $entityNanager;

    /**
     * CreateItem constructor.
     *
     * @param ObjectManager $entityNanager
     */
    public function __construct(ObjectManager $entityNanager)
    {
        $this->entityNanager = $entityNanager;
    }

    public function execute()
    {
        $sprint = new Sprint(
            $this->duration,
            $this->user
        );
        $this->entityNanager->persist($sprint);
        $this->entityNanager->flush();
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration(string $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
