<?php


namespace PHPRum\DomainModel\Backlog;

class SubItem extends Item
{
    /**
     * @var CompoundItem
     */
    protected $parentItem;

    /**
     * @var BacklogOwner
     */
    protected $creator;

    /**
     * @var Sprint
     */
    protected $sprint;
    /**
     * @var int
     */
    protected $priority = null;
    /**
     * @var int
     */
    protected $estimate = null;

    /**
     * Item constructor.
     * @param string $name
     */
    public function __construct(string $name, BacklogOwner $creator, CompoundItem $parentItem)
    {
        $this->name = $name;
        $this->creator = $creator;
        $this->parentItem = $parentItem;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param Sprint $sprint
     */
    public function addToSprint(Sprint $sprint)
    {
        $this->sprint = $sprint;
    }

    /**
     * @return Sprint
     */
    public function getSprint(): ?Sprint
    {
        return $this->sprint;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function removeFromSprint()
    {
        $this->sprint = null;
    }

    /**
     * @return int
     */
    public function getEstimate(): ?int
    {
        return $this->estimate;
    }

    public function getEpic(): ?Epic
    {
        return $this->parentItem->getEpic();
    }


}