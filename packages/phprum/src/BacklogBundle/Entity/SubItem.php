<?php

namespace BacklogBundle\Entity;

use PHPRum\DomainModel\Backlog\ItemStatus;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class SubItem extends \PHPRum\DomainModel\Backlog\SubItem
{
    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('name', new Length([
            'min' => 2,
            'max' => 255
        ]));
        $metadata->addPropertyConstraint('description', new Length([
            'min' => 2,
            'max' => 2000
        ]));
        $metadata->addPropertyConstraint('status', new Choice([
            'choices' => ItemStatus::values()
        ]));
    }
}
