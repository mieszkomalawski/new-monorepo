<?php


namespace BacklogBundle\Form;


use PHPRum\DomainModel\Backlog\Item;
use Symfony\Bridge\Doctrine\Tests\Fixtures\SingleIntIdNoToStringEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateItemType extends AbstractType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => SingleIntIdNoToStringEntity::classs
        ]);
    }
}