<?php

namespace BacklogBundle\Form;

use BacklogBundle\CustomPropertyAccessor;
use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\CompoundItem;
use BacklogBundle\Entity\User;
use BacklogBundle\Service\CreatorJailer;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use PHPRum\DomainModel\Backlog\Backlog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateItemType extends AbstractType
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompoundItem::class,
        ])->setRequired(['user', 'backlog']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];
        /** @var Backlog $backlog */
        $backlog = $options['backlog'];
        $emptyData = function (FormInterface $form) use ($user, $backlog) {
            if ($form->has('name') && is_string($form->get('name')->getData())) {
                return $backlog->createItem($form->get('name')->getData(), $user);
            }
        };
        $builder->setEmptyData(
            $emptyData
        )
            ->add('name', TextType::class)
            ->add('description', CKEditorType::class, ['required' => false, 'empty_data' => ''])
            ->add('epic', SelectEpicType::class, [
                'query_builder' => $this->creatorJailer->getJailingQuery($user->getId()),
            ])
            ->setDataMapper(new PropertyPathMapper(
                new CustomPropertyAccessor(['epic' => 'moveToAnotherEpic'])
            ))
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
    }
}
