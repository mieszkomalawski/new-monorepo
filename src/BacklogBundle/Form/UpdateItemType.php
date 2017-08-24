<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\Item;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\SprintPropertyAccessor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateItemType extends AbstractType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired('sprint_query');
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add('name', TextType::class)
            ->add('estimate', TextType::class, ['required' => false])
            ->add('priority', TextType::class, ['required' => false])
            ->add('status', TaskStatusType::class )
            ->add('Sprint', SelectSprintType::class, [
                'query_builder' => $options['sprint_query']
            ])
            ->setDataMapper(new PropertyPathMapper(
                new SprintPropertyAccessor(['Sprint' => 'addToSprint'])
            ))
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('imageFile', FileType::class, ['required' => false])
            ->getForm();
    }
}