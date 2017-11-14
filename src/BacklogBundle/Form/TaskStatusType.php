<?php

namespace BacklogBundle\Form;

use BacklogBundle\Entity\CompoundItem;
use PHPRum\DomainModel\Backlog\ItemStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'New' => ItemStatus::NEW,
                'In progress' => ItemStatus::IN_PROGRESS,
                'Done' => ItemStatus::DONE,
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
