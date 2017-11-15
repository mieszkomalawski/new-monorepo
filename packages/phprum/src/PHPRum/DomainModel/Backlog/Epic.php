<?php
declare(strict_types=1);

namespace PHPRum\DomainModel\Backlog;

class Epic
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var BacklogOwner
     */
    protected $creator;

    /**
     * Epic constructor.
     *
     * @param string       $name
     * @param BacklogOwner $creator
     */
    public function __construct(string $name, BacklogOwner $creator)
    {
        $this->name = $name;
        $this->creator = $creator;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

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
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color)
    {
        $this->color = $color;
    }
}
