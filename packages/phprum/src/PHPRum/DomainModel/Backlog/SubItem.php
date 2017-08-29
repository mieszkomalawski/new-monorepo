<?php


namespace PHPRum\DomainModel\Backlog;


use BacklogBundle\Entity\User;

class SubItem extends Item
{
    /**
     * @var Item
     */
    private $parentItem;

    /**
     * Item constructor.
     * @param string $name
     */
    public function __construct(string $name, User $creator, Item $parentItem)
    {
        parent::__construct($name, $creator);
        $this->parentItem = $parentItem;
    }
}