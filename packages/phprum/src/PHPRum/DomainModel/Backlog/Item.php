<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;

use PHPRum\DomainModel\Backlog\Exception\InvalidActionException;
use PHPRum\DomainModel\Backlog\Exception\InvalidEstimate;
use PHPRum\DomainModel\Backlog\Exception\StatusNotAllowed;

class Item
{
    const ALLOWED_ESTIMATES = [1, 2, 3, 5, 8, 13, 21];

    const ALLOWED_STATUSES = [self::STATUS_NEW, self::STAUS_IN_PROGRESS, self::STATUS_DONE];
    const STATUS_NEW = 'new';
    const STAUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $estimate = null;

    /**
     * @var int
     */
    protected $priority = null;

    /**
     * @var BacklogOwner
     */
    protected $creator;

    /**
     * @var string
     */
    protected $status = self::STATUS_NEW;

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
     * @var Label[]
     */
    protected $labels = [];

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
     * @param string $name
     * @return SubItem
     * @throws InvalidActionException
     */
    public function createSubItem($name)
    {
        if ($this->isDone()) {
            throw InvalidActionException::createCannotAddSubTask();
        }
        $subItem = $this->doCreateSubItem($name);
        if ($this->isInSprint()) {
            $subItem->addToSprint($this->sprint);
        }
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEstimate(): ?int
    {
        return $this->estimate;
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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        if (!$this->isStatusAllowed($status)) {
            throw StatusNotAllowed::create($status, self::ALLOWED_STATUSES);
        }
        if (self::STATUS_DONE === $status && $this->hasSubItems()) {

            foreach ($this->subItems as $subItem) {
                if (self::STATUS_DONE !== $subItem->getStatus()) {
                    throw InvalidActionException::createCannotFinishTask();
                }
            }

        }
        $this->status = $status;
    }

    public function addToSprint(Sprint $sprint)
    {
        $this->sprint = $sprint;
        if ($this->hasSubItems()) {
            foreach ($this->subItems as $subItem) {
                $subItem->addToSprint($sprint);
            }
        }
    }


    /**
     * @return Sprint
     */
    public function getSprint(): ?Sprint
    {
        return $this->sprint;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    protected function isInSprint(): bool
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
        if ($this->hasSubItems()) {
            foreach ($this->subItems as $subItem) {
                $subItem->removeFromSprint();
            }
        }
    }


    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return Label[]
     */
    public function getLabels(): iterable
    {
        return $this->labels;
    }

    /**
     * @param Label[] $labels
     */
    public function setLabels(array $labels)
    {
        $this->labels = $labels;
    }

    public function addLabel(Label $label)
    {
        $this->labels[] = $label;
    }

    /**
     * @param Label $labelToRemove
     */
    public function removeLabel(Label $labelToRemove)
    {
        foreach ($this->labels as $key => $label) {
            if ($label->getId() == $labelToRemove->getId()) {
                unset($this->labels[$key]);
            }
        }
    }

    public function done()
    {
        $this->setStatus(self::STATUS_DONE);
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
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
     * @param string $status
     * @return bool
     */
    protected function isStatusAllowed(string $status): bool
    {
        return in_array($status, self::ALLOWED_STATUSES);
    }

    /**
     * @return bool
     */
    protected function hasSubItems(): bool
    {
        return !empty($this->subItems);
    }
}