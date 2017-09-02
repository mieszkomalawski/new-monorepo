<?php


namespace BacklogBundle\Entity;


use PHPRum\DomainModel\Backlog\Item;

class Backlog extends \PHPRum\DomainModel\Backlog\Backlog
{
    protected function doGetItem(string $name, User $user): Item
    {
        return new \BacklogBundle\Entity\Item($name, $user);
    }

}