<?php


namespace PHPRum\DomainModel\Backlog;


use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class Sprint
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $duration;

    /**
     * @var /DateTime
     */
    private $startDate;

    /**
     * @var User
     */
    private $creator;

    /**
     * @var bool
     */
    private $isStarted = false;

    /**
     * @var Item[]
     */
    private $items;

    /**
     * Sprint constructor.
     * @param string $duration
     * @param User $creator
     */
    public function __construct($duration, User $creator, ArrayCollection $items)
    {
        $this->duration = $duration;
        $this->creator = $creator;
        $this->items = $items;
    }

    public function start()
    {
        $this->isStarted = true;
        $this->startDate = new \DateTime();
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items->add($item);
        $item->addToSprint($this);
    }

}