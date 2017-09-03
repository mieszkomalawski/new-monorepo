<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\Epic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectEpicType extends AbstractType
{

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'class' => Epic::class,
            'choice_label' => 'getName',
            'placeholder' => 'none',
            'required' => false
        ])->setRequired(['query_builder']);
    }


    public function getParent()
    {
        return EntityType::class;
    }
}