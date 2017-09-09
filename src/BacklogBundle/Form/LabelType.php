<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emptyData = function (FormInterface $form)  {
            if (
                $form->has('name') &&
                is_string($form->get('name')->getData()) &&
                $form->has('color') &&
                is_string($form->get('color')->getData())
            ) {
                return new Label(
                    $form->get('name')->getData(),
                    $form->get('color')->getData()
                );
            }
        };
        $builder
            ->setEmptyData($emptyData)
            ->add('name')
            ->add('color');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Label::class
        ]);
    }

}