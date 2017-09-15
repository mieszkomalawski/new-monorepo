<?php


namespace BacklogBundle\Form;


use BacklogBundle\Service\CreatorJailer;
use BacklogBundle\SprintPropertyAccessor;
use Symfony\Component\Form\AbstractType;
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
     * @param CreatorJailer $creatorJailer
     */
    public function __construct(CreatorJailer $creatorJailer)
    {
        $this->creatorJailer = $creatorJailer;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired('userId');
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add('name', TextType::class)
            ->add('estimate', TextType::class, ['required' => false])
            // priority is updated by draggings items on list
            //->add('priority', TextType::class, ['required' => false])
            ->add('status', TaskStatusType::class)
            ->add('Sprint', SelectSprintType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($options['userId'])
            ])
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['Sprint' => 'addToSprint'])
            ))
            ->add('epic', SelectEpicType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($options['userId']),
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('imageFile', FileType::class, ['required' => false])
            ->add('subItems', CollectionType::class, [
                'entry_type' => UpdateSubItemType::class,
                'entry_options' => ['label' => false]
            ])
            ->add('labels', CollectionType::class, [
                'entry_type' => LabelType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->getForm();
    }
}