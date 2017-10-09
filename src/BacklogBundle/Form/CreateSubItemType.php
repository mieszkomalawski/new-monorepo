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

class CreateSubItemType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubItem::class
        ])->setRequired('parent_item');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CompoundItem $parentItem */
        $parentItem = $options['parent_item'];
        $emptyData = function (FormInterface $form) use ($parentItem) {
            if ($form->has('name') && is_string($form->get('name')->getData())) {
                return $parentItem->createSubItem($form->get('name')->getData());
            }
        };
        $builder->setEmptyData(
            $emptyData
        )
            ->add('name', TextType::class, ['mapped' => false])
            ->add('description', CKEditorType::class, ['required' => false, 'empty_data' => ''])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
    }

}