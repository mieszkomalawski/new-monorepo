<?php

namespace PHPRum\DomainModel\Backlog;

use PHPRum\DomainModel\Backlog\Exception\StatusNotAllowed;

abstract class Item
{
    const ALLOWED_ESTIMATES = [1, 2, 3, 5, 8, 13, 21];

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
     * @var ItemStatus
     */
    protected $status;
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
     * @return ItemStatus
     */
    public function getStatus(): ItemStatus
    {
        if(is_null($this->status)){
            $this->status = ItemStatus::NEW();
        }
        if(is_string($this->status)){
            $this->status = new ItemStatus($this->status);
        }
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
        return $this->status->isDone();
    }

    /**
     * @param ItemStatus $status
     *
     * @throws StatusNotAllowed
     */
    public function changeStatus(ItemStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @param ItemStatus $status
     * Aliast for changeStatus
     *
     * @throws StatusNotAllowed
     */
    public function setStatus(ItemStatus $status): void
    {
        $this->changeStatus($status);
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isStatusAllowed(string $status): bool
    {
        return ItemStatus::isValid($status);
    }

    /**
     * @return bool
     */
    abstract public function isInSprint(): bool;

    public function done(): void
    {
        $this->changeStatus(ItemStatus::DONE());
    }
}
