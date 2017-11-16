<?php

namespace BacklogBundle\Entity;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Epic extends \PHPRum\DomainModel\Backlog\Epic
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
        $metadata->addPropertyConstraint('color', new Length([
            'min' => 2,
            'max' => 20
        ]));
    }
}
