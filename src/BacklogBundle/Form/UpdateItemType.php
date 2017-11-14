<?php

namespace BacklogBundle\Form;

use BacklogBundle\Entity\CompoundItem;
use BacklogBundle\Service\CreatorJailer;
use BacklogBundle\SprintPropertyAccessor;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use PHPRum\DomainModel\Backlog\Item;
use PHPRum\DomainModel\Backlog\ItemStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateItemType extends AbstractType
{
    /**
     * @var CreatorJailer
     */
    private $creatorJailer;

    /**
     * CreateItemType constructor.
     *
     * @param CreatorJailer $creatorJailer
     */
    public function __construct(CreatorJailer $creatorJailer)
    {
        $this->creatorJailer = $creatorJailer;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired(['userId', 'other_items']);
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add('name', TextType::class, ['required' => false, 'empty_data' => ''])
            ->add('description', CKEditorType::class, ['required' => false, 'empty_data' => ''])
            ->add('estimate', TextType::class, ['required' => false])
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['estimate' => 'estimate'])
            ))
            ->add('status', TaskStatusType::class)
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['status' => 'changeStatus'])
            ))
            ->add('Sprint', SelectSprintType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($options['userId']),
            ])
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['Sprint' => 'addToSprint'])
            ))
            ->add('epic', SelectEpicType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($options['userId']),
            ])
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['epic' => 'moveToAnotherEpic'])
            ))
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('imageFile', FileType::class, ['required' => false])
            ->add('subItems', CollectionType::class, [
                'entry_type' => StatusUpdateSubItemType::class,
                'entry_options' => ['label' => false],
            ])
            ->add('blockedBy', EntityType::class, [
                'data_class' => null,
                'choices' => $options['other_items'],
                'class' => CompoundItem::class,
                'multiple' => true,
                'choice_label' => 'getName',
                'required' => false,
            ])
            ->add('blocks', EntityType::class, [
                'data_class' => null,
                'choices' => $options['other_items'],
                'class' => CompoundItem::class,
                'multiple' => true,
                'choice_label' => 'getName',
                'required' => false,
            ])
            ->add('labels', CollectionType::class, [
                'entry_type' => LabelType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
            ]);

        $formBuilder
            ->get('status')
            ->addModelTransformer(new ItemStatusTransformer())->getForm();
    }
}
