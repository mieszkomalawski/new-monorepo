<?php

namespace BacklogBundle\Form;

use BacklogBundle\Repository\ItemSearchQuery;
use BacklogBundle\Service\CreatorJailer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchItemType extends AbstractType
{
    /**
     * @var CreatorJailer
     */
    private $creatorJailer;

    /**
     * SearchItemType constructor.
     *
     * @param CreatorJailer $creatorJailer
     */
    public function __construct(CreatorJailer $creatorJailer)
    {
        $this->creatorJailer = $creatorJailer;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemSearchQuery::class,
        ])
        ->setRequired(['user_id']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Status', TaskStatusType::class)
            ->add('Epic', SelectEpicType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($options['user_id']),
                'placeholder' => 'all',
            ])
            ->add('Search', SubmitType::class)
            ->getForm();
    }
}
