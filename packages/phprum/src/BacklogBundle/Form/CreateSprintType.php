<?php


namespace BacklogBundle\Form;


use BacklogBundle\Entity\Sprint;
use PHPRum\DomainModel\Backlog\SprintDuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSprintType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sprint::class
        ])->setRequired('user');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $emptyData = function (FormInterface $form) use ($user) {
            if ($form->has('duration') && is_string($form->get('duration')->getData())) {
                return new Sprint(
                    new SprintDuration($form->get('duration')->getData()),
                    $user
                );
            }
        };
        $builder->setEmptyData(
            $emptyData
        )
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    'One week' => SprintDuration::ONE_WEEK,
                    'Two weeks' => SprintDuration::TWO_WEEKS,
                    'Three weeks' => SprintDuration::THREE_WEEKS,
                    'Four weeks' => SprintDuration::FOUR_WEEKS,
                ],
            ])
            ->add('Save', SubmitType::class)
            ->getForm();
    }
}