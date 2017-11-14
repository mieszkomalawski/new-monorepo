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
     * SubItem constructor.
     *
     * @param string       $name
     * @param BacklogOwner $creator
     * @param CompoundItem $parentItem
     */
    public function __construct(string $name, BacklogOwner $creator, CompoundItem $parentItem)
    {
        $this->status = ItemStatus::NEW();
        $this->name = $name;
        $this->creator = $creator;
        $this->parentItem = $parentItem;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return Sprint
     */
    public function getSprint(): ?Sprint
    {
        return $this->parentItem->getSprint();
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
