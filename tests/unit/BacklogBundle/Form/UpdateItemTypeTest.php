<?php


namespace Tests\Unit\BacklogBundle\Form;


use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\Item;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Service\CreatorJailer;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class UpdateItemTypeTest extends EntityAwareTypeTest
{

    /**
     * @param string $class
     * @return callable
     */
    protected function getFakeEntities()
    {
        return function ($class) {
            switch ($class) {
                case Sprint::class:
                    return [new Sprint('1_week', new User())];
                    break;
                case Epic::class:
                    return [new Epic('epic1', new User())];
                    break;

            }
        };

    }

    /**
     * @return PreloadedExtension
     */
    protected function getPreloadedExtensions()
    {
        $creatorJailer = $this->prophesize(CreatorJailer::class);
        $queryBuilder = $this->prophesize(QueryBuilder::class);
        $creatorJailer->getJailingQuery(1)->willReturn(function () use ($queryBuilder) {
            return $queryBuilder->reveal();
        });
        $updateItemType = new UpdateItemType(
            $creatorJailer->reveal()
        );
        return new PreloadedExtension([$updateItemType], []);
    }

    /**
     * @test
     */
    public function submitValidData()
    {
        $user = new User();
        $sprint = new Sprint('1_week', $user);
        $formData = [
            'name' => 'new_name',
            'estimate' => 5,
            'status' => Item::STAUS_IN_PROGRESS,
            'Sprint' => $sprint
        ];

        $object = new Item('old_name', $user);


        $form = $this->factory->create(UpdateItemType::class, $object, [
            'userId' => 1
        ]);

        $object->setEstimate(5);
        $object->setStatus(Item::STAUS_IN_PROGRESS);
        $object->addToSprint($sprint);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}