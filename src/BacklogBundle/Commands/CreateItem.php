<?php

namespace BacklogBundle\Commands;

use PHPRum\DomainModel\Backlog\Item;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CreateItem extends \PHPRum\Commands\Backlog\CreateItem
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // ...

        $metadata->addPropertyConstraint('name', new Length(array(
            'min' => 3
        )));
    }

    protected function createItem(): Item
    {
        return new \BacklogBundle\Entity\Item($this->name, $this->user);
    }


}