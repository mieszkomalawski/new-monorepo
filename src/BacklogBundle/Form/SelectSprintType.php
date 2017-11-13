<?php

namespace BacklogBundle\Form;

use BacklogBundle\Entity\Sprint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectSprintType extends AbstractType
{
    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'class' => Sprint::class,
            'choice_label' => 'getName',
            'placeholder' => 'none',
            'required' => false,
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
