<?php


namespace PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;

class Sprint
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $duration;

    /**
     * @var /DateTime
     */
    protected $startDate;

    /**
     * @var User
     */
    protected $creator;

    /**
     * @var bool
     */
    protected $isStarted = false;

    /**
     * @var array
     */
    protected $items;

    /**
     * Sprint constructor.
     * @param string $duration
     * @param User $creator
     */
    public function __construct($duration, User $creator)
    {
        $this->duration = $duration;
        $this->creator = $creator;
    }

    public function start()
    {
        $this->isStarted = true;
        $this->startDate = new \DateTime();
        // todo how to save this new sprint ? oneTonOne ?
        $nextSprint = new Sprint(
            $this->duration,
            $this->creator
        );
        $this->store($nextSprint);
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->doAddToItems($item);
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
        return array_reduce($this->items, function(int $carry, Item $item){
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

    protected function store(Sprint $sprint){}

    /**
     * @param Item $item
     */
    protected function doAddToItems(Item $item): void
    {
        $this->items[] = $item;
    }

}