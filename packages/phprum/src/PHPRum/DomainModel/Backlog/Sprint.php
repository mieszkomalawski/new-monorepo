<?php


namespace PHPRum\DomainModel\Backlog;


use BacklogBundle\Entity\User;
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
     * @var ArrayCollection
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
        // todo how to save this new sprint ? oneTonOne ?
        $nextSprint = new Sprint(
            $this->duration,
            $this->creator,
            new ArrayCollection()
        );
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items->add($item);
        $item->addToSprint($this);
    }

    public function getName() : string
    {
        return 'Sprint ' . $this->id;
    }

    /**
     * @return iterable
     */
    public function getItems(): iterable
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotalPoints() : int
    {
        return array_reduce($this->items->getIterator()->getArrayCopy(), function(int $carry, Item $item){
            $carry += $item->getEstimate();
            return $carry;
        }, 0);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}