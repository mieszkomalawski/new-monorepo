<?php


namespace PHPRum\DomainModel\Backlog;

class SubItem
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Item
     */
    protected $parentItem;

    /**
     * @var BacklogOwner
     */
    protected $creator;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Sprint
     */
    protected $sprint;

    /**
     * Item constructor.
     * @param string $name
     */
    public function __construct(string $name, BacklogOwner $creator, Item $parentItem)
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
     * @param Sprint $sprint
     */
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
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function removeFromSprint()
    {
        $this->sprint = null;
    }
}