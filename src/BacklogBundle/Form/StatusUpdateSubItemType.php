<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\CompoundItem;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use PHPRum\DomainModel\Backlog\SubItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusUpdateSubItemType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubItem::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['disabled' => true])
            ->add('description', TextareaType::class, ['disabled' => false])
            ->add('status', TaskStatusType::class, ['required' => false])
            ->getForm();
    }

}