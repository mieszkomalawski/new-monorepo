<?php


namespace PHPRum\Commands\Backlog;


use BacklogBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use PHPRum\DomainModel\Backlog\Item;

class CreateItem
{
    /**
     * @var string
     */
    protected $name = '';


    /**
     * @var User
     */
    protected $user;

    /**
     * @var ObjectManager
     */
    private $entityNanager;

    /**
     * CreateItem constructor.
     * @param EntityManager $entityNanager
     */
    public function __construct(ObjectManager $entityNanager)
    {
        $this->entityNanager = $entityNanager;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     *
     */
    public function execute() : void
    {
        $item = $this->createItem();
        $this->entityNanager->persist($item);
        $this->entityNanager->flush();
    }

    /**
     * @return Item
     */
    protected function createItem(): Item
    {
        return new Item($this->name, $this->user);
    }


}