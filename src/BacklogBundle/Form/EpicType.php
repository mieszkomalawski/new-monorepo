<?php

namespace BacklogBundle\Form;

use BacklogBundle\Entity\Epic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $emptyData = function (FormInterface $form) use ($user) {
            if ($form->has('name') && $form->has('color')) {
                return new Epic($form->get('name')->getData(), $user);
            }
        };
        $builder->add('name')->add('color')->setEmptyData($emptyData);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BacklogBundle\Entity\Epic',
        ))->setRequired('user');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backlogbundle_epic';
    }
}
