<?php


namespace PHPRum\DomainModel\Backlog;


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
     * @var /DateTime
     */
    protected $closedOn;

    /**
     * @var BacklogOwner
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
     * @param BacklogOwner $creator
     */
    public function __construct($duration, BacklogOwner $creator)
    {
        $this->duration = $duration;
        $this->creator = $creator;
    }

    /**
     * Starts this sprint and returns next one
     * @return Sprint
     */
    public function start(): Sprint
    {
        $this->isStarted = true;
        $this->startDate = new \DateTime();
        return $this->createNexSprint();
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->doAddToItems($item);
        $item->addToSprint($this);
    }

    public function getName(): string
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
    public function getTotalPoints(): int
    {
        return array_reduce($this->items, function (int $carry, Item $item) {
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

    /**
     * @param Item $item
     */
    protected function doAddToItems(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate() : \DateTime
    {
        $interval = new \DateInterval('P1W');
        if ($this->duration === '1_weel') {
            $interval = new \DateInterval('P1W');
        }
        if ($this->duration === '2_weel') {
            $interval = new \DateInterval('P2W');
        }
        if ($this->duration === '3_weel') {
            $interval = new \DateInterval('P3W');
        }
        if ($this->duration === '4_weel') {
            $interval = new \DateInterval('P4W');
        }

        /** @var \DateTime $startDate */
        $startDate = $this->startDate;

        return $startDate->add($interval);
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    /**
     * @return Sprint
     */
    protected function createNexSprint(): Sprint
    {
        return new Sprint(
            $this->duration,
            $this->creator
        );
    }

    /**
     * @return \DateTime
     */
    public function getClosedOn() : ?\DateTime
    {
        return $this->closedOn;
    }

    /**
     *
     */
    public function end() : void
    {
        $this->closedOn = new \DateTime();
        $this->isStarted = false;
    }

    public function isFinished() : bool
    {
        return $this->closedOn !== null ? true : false;
    }

}