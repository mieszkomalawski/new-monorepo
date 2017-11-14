<?php

declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;

use PHPRum\DomainModel\Backlog\Event\ItemAdded;
use PHPRum\DomainModel\Backlog\Exception\InvalidActionException;
use PHPRum\DomainModel\Backlog\Exception\InvalidEstimate;
use PHPRum\StaticEventDispatcher;

class CompoundItem extends Item
{
    /**
     * @var BacklogOwner
     */
    protected $creator;

    /**
     * @var Sprint
     */
    protected $sprint;

    /**
     * @var SubItem[]
     */
    protected $subItems = [];

    /**
     * @var Epic
     */
    protected $epic;
    /**
     * @var int
     */
    protected $priority = null;
    /**
     * @var int
     */
    protected $estimate = null;

    /**
     * @var Item[]
     */
    protected $blockedBy = [];

    /**
     * @var Item[]
     */
    protected $blocks = [];

    /**
     * Item constructor.
     *
     * @param string       $name
     * @param BacklogOwner $creator
     */
    public function __construct(string $name, BacklogOwner $creator)
    {
        $this->status = ItemStatus::NEW();
        $this->name = $name;
        $this->createdAt = new \DateTime();
        $this->creator = $creator;
        StaticEventDispatcher::getEventDispatcher()->dispatch(new ItemAdded(
            $name
        ));
    }

    /**
     * @return bool
     */
    public function canCreateSubItem(): bool
    {
        return !$this->isDone();
    }

    /**
     * @param string $name
     *
     * @return SubItem
     *
     * @throws InvalidActionException
     */
    public function createSubItem($name): SubItem
    {
        if ($this->isDone()) {
            throw InvalidActionException::createCannotAddSubTask();
        }
        $subItem = $this->doCreateSubItem($name);

        $this->addToSubItems($subItem);

        return $subItem;
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function hasAccess(int $userId): bool
    {
        /*
         * for now only owner has access
         */
        return $this->creator->getId() === $userId;
    }

    /**
     * @param int $estimate
     *
     * @return bool
     */
    public function canEstimate(int $estimate): bool
    {
        return $this->isValidEstimate($estimate);
    }

    /**
     * @param int $estimate
     *
     * @throws InvalidEstimate
     */
    public function estimate(int $estimate): void
    {
        if (!$estimate) {
            $this->estimate = null;

            return;
        }
        if (!$this->isValidEstimate($estimate)) {
            throw InvalidEstimate::create($estimate, static::ALLOWED_ESTIMATES);
        }
        $this->estimate = $estimate;
    }

    /**
     * @return int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function changePriority(int $priority): void
    {
        if (!$priority) {
            $this->priority = null;
        } else {
            $this->priority = $priority;
        }
    }

    public function lowerPriority()
    {
        ++$this->priority;
    }

    public function canChangeStatus(ItemStatus $status): bool
    {

        if ($status->equals(ItemStatus::DONE()) && $this->hasSubItems()) {
            foreach ($this->subItems as $subItem) {
                if (!$subItem->getStatus()->equals(ItemStatus::DONE())) {
                    return false;
                }
            }
        }
        if (!empty($this->blockedBy) && $status->equals(ItemStatus::DONE())) {
            foreach ($this->blockedBy as $blockedBy) {
                if (!$blockedBy->isDone()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param ItemStatus $status
     *
     * @throws InvalidActionException
     */
    public function changeStatus(ItemStatus $status): void
    {
        if (!$this->canChangeStatus($status)) {
            throw InvalidActionException::createCannotFinishTask();
        }
        parent::changeStatus($status);
    }

    /**
     * @param ItemStatus $status
     * Alias for change status
     *
     * @throws InvalidActionException
     */
    public function setStatus(ItemStatus $status): void
    {
        $this->changeStatus($status);
    }

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
     * @return bool
     */
    public function isInSprint(): bool
    {
        return $this->sprint instanceof Sprint;
    }

    /**
     * @return SubItem[]
     */
    public function getSubItems(): iterable
    {
        return $this->subItems;
    }

    /**
     * @param $name
     *
     * @return SubItem
     */
    protected function doCreateSubItem(string $name): SubItem
    {
        return new SubItem($name, $this->creator, $this);
    }

    public function removeFromSprint()
    {
        $this->sprint = null;
    }

    /**
     * @return Epic
     */
    public function getEpic(): ?Epic
    {
        return $this->epic;
    }

    /**
     * @param Epic $epic
     */
    public function moveToAnotherEpic(?Epic $epic)
    {
        $this->epic = $epic;
    }

    /**
     * @param SubItem $subItem
     */
    protected function addToSubItems(SubItem $subItem): void
    {
        $this->subItems[] = $subItem;
    }

    /**
     * @param int $estimate
     *
     * @return bool
     */
    protected function isValidEstimate(int $estimate): bool
    {
        return in_array($estimate, static::ALLOWED_ESTIMATES);
    }

    /**
     * @return bool
     */
    protected function hasSubItems(): bool
    {
        return !empty($this->subItems);
    }

    /**
     * @return int
     */
    public function getEstimate(): ?int
    {
        return $this->estimate;
    }

    /**
     * @param CompoundItem $item
     */
    public function addBlockedBy(self $item): void
    {
        $this->blockedBy[] = $item;
        $item->addBlockedBy($this);
    }

    /**
     * @param CompoundItem $item
     */
    public function addBlocks(self $item): void
    {
        $this->blocks[] = $item;
    }

    /**
     * @return iterable
     */
    public function getBlockedBy(): iterable
    {
        return $this->blockedBy;
    }

    /**
     * @return iterable
     */
    public function getBlocks(): iterable
    {
        return $this->blocks;
    }
}
