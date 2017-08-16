<?php


namespace PHPRum\DomainModel\Backlog;

class Item
{
    const ALLOWED_ESTIMATES = [1, 2, 3, 5, 8, 13, 21];
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var int
     */
    private $points;

    /**
     * Item constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
     * @param int $estimate
     */
    public function estimate(int $estimate)
    {
        if (!in_array($estimate, self::ALLOWED_ESTIMATES)) {
            throw new \InvalidArgumentException('Estimate ' . $estimate . ' not allowed, must be one of: ' . implode(',',
                    self::ALLOWED_ESTIMATES));
        }
        $this->points = $estimate;
    }
}