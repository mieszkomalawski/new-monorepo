<?php

namespace PHPRum\DomainModel\Backlog;

use PHPRum\DomainModel\Backlog\Exception\StatusNotAllowed;

abstract class Item
{
    const ALLOWED_ESTIMATES = [1, 2, 3, 5, 8, 13, 21];
    const STAUS_IN_PROGRESS = 'in_progress';
    const STATUS_NEW = 'new';
    const STATUS_DONE = 'done';
    const ALLOWED_STATUSES = [self::STATUS_NEW, self::STAUS_IN_PROGRESS, self::STATUS_DONE];

    /**
     * @var Label[]
     */
    protected $labels = [];
    /**
     * @var \DateTime
     */
    protected $createdAt;
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $status = self::STATUS_NEW;
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
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
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return iterable
     */
    public function getLabels(): iterable
    {
        return $this->labels;
    }

    /**
     * @return Sprint
     */
    abstract public function getSprint(): ?Sprint;

    /**
     * @return Epic
     */
    abstract public function getEpic(): ?Epic;

    /**
     * @param Label[] $labels
     */
    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }

    /**
     * @param Label $label
     */
    public function addLabel(Label $label): void
    {
        $this->labels[] = $label;
    }

    /**
     * @param Label $labelToRemove
     */
    public function removeLabel(Label $labelToRemove): void
    {
        foreach ($this->labels as $key => $label) {
            if ($label->getId() === $labelToRemove->getId()) {
                unset($this->labels[$key]);
            }
        }
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return self::STATUS_DONE === $this->status;
    }

    /**
     * @param string $status
     *
     * @throws StatusNotAllowed
     */
    public function setStatus(string $status): void
    {
        if (!$this->isStatusAllowed($status)) {
            throw StatusNotAllowed::create($status, self::ALLOWED_STATUSES);
        }
        $this->status = $status;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isStatusAllowed(string $status): bool
    {
        return in_array($status, self::ALLOWED_STATUSES, true);
    }

    /**
     * @return bool
     */
    abstract public function isInSprint(): bool;

    public function done(): void
    {
        $this->setStatus(self::STATUS_DONE);
    }
}
