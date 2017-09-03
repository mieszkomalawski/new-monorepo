<?php


namespace PHPRum\DomainModel\Backlog;


use BacklogBundle\Entity\User;

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
     * @var User
     */
    protected $creator;

    /**
     * Epic constructor.
     * @param $name
     * @param $color
     * @param User $creator
     */
    public function __construct(string $name, User $creator)
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