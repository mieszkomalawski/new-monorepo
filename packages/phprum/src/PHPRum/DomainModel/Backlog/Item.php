<?php


namespace PHPRum\DomainModel\Backlog;


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
     * @return Label[]
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

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->status === Item::STATUS_DONE;
    }
}