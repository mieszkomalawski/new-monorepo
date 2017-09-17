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
     * @return Sprint
     */
    public function getSprint(): ?Sprint
    {
        return $this->parentItem->getSprint();
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


    public function getEpic(): ?Epic
    {
        return $this->parentItem->getEpic();
    }

    public function isInSprint(): bool
    {
        return $this->parentItem->isInSprint();
    }


}