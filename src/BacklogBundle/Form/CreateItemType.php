<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateItemType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class
        ])->setRequired(['user']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $emptyData = function (FormInterface $form) use ($user) {
            if ($form->has('name') && is_string($form->get('name')->getData())) {
                return new Item($form->get('name')->getData(), $user);
            }
        };
        $builder->setEmptyData(
            $emptyData
        )
            ->add('name', TextType::class)
            ->getForm();
    }

}