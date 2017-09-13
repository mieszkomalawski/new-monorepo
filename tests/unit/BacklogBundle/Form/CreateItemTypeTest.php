<?php


namespace Tests\Unit\BacklogBundle\Form;


use BacklogBundle\Entity\Backlog;
use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Service\CreatorJailer;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\PreloadedExtension;

class CreateItemTypeTest extends EntityAwareTypeCase
{
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
        $creasteItemType = new CreateItemType(
            $creatorJailer->reveal()
        );
        return new PreloadedExtension([$creasteItemType], []);
    }

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
     * @test
     */
    public function submitValidData()
    {
        $user = $this->prophesize(User::class);
        $user->getId()->willReturn(1);
        $backlog = new Backlog([]);

        $formData = [
            'name' => 'newItem',
            'epic' => 1
        ];

        $item = $backlog->createItem('newItem', $user->reveal());
        $item->setEpic(new Epic('epic1', new User()));

        $form = $this->factory->create(CreateItemType::class, null, [
            'user' => $user->reveal(),
            'backlog' => $backlog
        ]);

        $form->submit($formData);

        static::assertEquals($item->getPriority(), $form->getData()->getPriority() - 1);
        static::assertEquals($item->getName(), $form->getData()->getName());
    }
}