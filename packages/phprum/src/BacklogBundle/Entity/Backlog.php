<?php

namespace BacklogBundle\Entity;

use PHPRum\DomainModel\Backlog\CompoundItem;

class Backlog extends \PHPRum\DomainModel\Backlog\Backlog
{
    protected function doGetItem(string $name, User $user): CompoundItem
    {
        return new \BacklogBundle\Entity\CompoundItem($name, $user);
    }
}
