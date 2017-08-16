<?php


namespace PHPRum\Commands\Backlog;


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
        $item = new Item($this->name);
        $this->entityNanager->persist($item);
        $this->entityNanager->flush();
    }

}