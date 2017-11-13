<?php

namespace BacklogBundle\Form;

use BacklogBundle\Entity\CompoundItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskStatusType extends AbstractType
{
    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'choices' => [
                'New' => CompoundItem::STATUS_NEW,
                'In progress' => CompoundItem::STAUS_IN_PROGRESS,
                'Done' => CompoundItem::STATUS_DONE,
            ],
            'required' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
