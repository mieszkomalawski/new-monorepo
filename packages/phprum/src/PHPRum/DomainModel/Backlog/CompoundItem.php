<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;

use PHPRum\DomainModel\Backlog\Exception\InvalidActionException;
use PHPRum\DomainModel\Backlog\Exception\InvalidEstimate;

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
     * @param string $name
     * @param BacklogOwner $creator
     */
    public function __construct(string $name, BacklogOwner $creator)
    {
        $this->name = $name;
        $this->createdAt = new \DateTime();
        $this->creator = $creator;
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
     * @return SubItem
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
     * @return bool
     */
    public function hasAccess(int $userId): bool
    {
        /**
         * for now only owner has access
         */
        return $this->creator->getId() === $userId;
    }

    /**
     * @param int $estimate
     * @return bool
     */
    public function canEstimate(int $estimate): bool
    {
        return $this->isValidEstimate($estimate);
    }

    /**
     * @param int $estimate
     * @throws InvalidEstimate
     */
    public function setEstimate(int $estimate): void
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
    public function setPriority(int $priority)
    {
        if (!$priority) {
            $this->priority = null;
            return;
        }
        $this->priority = $priority;
    }

    public function lowerPriority()
    {
        $this->priority++;
    }

    public function canChangeStatus(string $status): bool
    {
        if (self::STATUS_DONE === $status && $this->hasSubItems()) {

            foreach ($this->subItems as $subItem) {
                if (self::STATUS_DONE !== $subItem->getStatus()) {
                    return false;
                }
            }

        }
        if (!empty($this->blockedBy) && in_array($status, [self::STATUS_DONE, self::STAUS_IN_PROGRESS], true)) {
            foreach ($this->blockedBy as $blockedBy) {
                if (!$blockedBy->isDone()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param string $status
     * @throws InvalidActionException
     */
    public function setStatus(string $status): void
    {
        if (!$this->canChangeStatus($status)) {
            throw InvalidActionException::createCannotFinishTask();
        }
        parent::setStatus($status);
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
    public function setEpic(?Epic $epic)
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
    public function addBlockedBy(CompoundItem $item)
    {
        $this->blockedBy[] = $item;
        $item->addBlockedBy($this);
    }

    /**
     * @param CompoundItem $item
     */
    public function addBlocks(CompoundItem $item)
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