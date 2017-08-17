<?php


namespace PHPRum\Commands\Backlog;


use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use PHPRum\DomainModel\Backlog\Sprint;

class CreateSrpint
{
    /**
     * @var string
     */
    private $duration;

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
            $this->user,
            $this->getItemCollection()
        );
        $this->entityNanager->persist($sprint);
        $this->entityNanager->flush();
    }

    /**
     * @return ArrayCollection
     */
    protected function getItemCollection(): ArrayCollection
    {
        return new ArrayCollection();
    }
}