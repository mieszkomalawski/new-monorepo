<?php

namespace BacklogBundle\Form;

use PHPRum\DomainModel\Backlog\CompoundItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BlockedByType.
 */
class BlockedByType extends EntityType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompoundItem::class,
            'class' => CompoundItem::class,
        ]);
    }
}
