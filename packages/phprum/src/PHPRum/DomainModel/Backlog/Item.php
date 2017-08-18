<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;

use BacklogBundle\Entity\User;

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
     * @var User
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
     * Item constructor.
     * @param string $name
     */
    public function __construct(string $name, User $creator)
    {
        $this->name = $name;
        $this->createdAt = new \DateTime();
        $this->creator = $creator;
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
     */
    public function setEstimate(int $estimate) : void
    {
        if (!$estimate) {
            $this->estimate = null;
            return;
        }
        if (!in_array($estimate, self::ALLOWED_ESTIMATES)) {
            throw new \InvalidArgumentException('Estimate ' . $estimate . ' not allowed, must be one of: ' . implode(',',
                    self::ALLOWED_ESTIMATES));
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
        if (!in_array($status, self::ALLOWED_STATUSES)) {
            throw new \InvalidArgumentException('Status ' . $status . ' not allowed, must be one of: ' . implode(',',
                    self::ALLOWED_STATUSES));
        }
        $this->status = $status;
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
}