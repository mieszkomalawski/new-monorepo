<?php


namespace Tests\Unit\BacklogBundle\Form;


use BacklogBundle\Entity\Backlog;
use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Service\CreatorJailer;
use Doctrine\ORM\QueryBuilder;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Ivory\CKEditorBundle\Model\ConfigManager;
use Ivory\CKEditorBundle\Model\PluginManager;
use Ivory\CKEditorBundle\Model\StylesSetManager;
use Ivory\CKEditorBundle\Model\TemplateManager;
use Ivory\CKEditorBundle\Model\ToolbarManager;
use PHPRum\DomainModel\Backlog\SprintDuration;
use function SebastianBergmann\ObjectGraph\object_graph_dump;
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
        $ck = new CKEditorType(
            $this->prophesize(ConfigManager::class)->reveal(),
            $this->prophesize(PluginManager::class)->reveal(),
            $this->prophesize(StylesSetManager::class)->reveal(),
            $this->prophesize(TemplateManager::class)->reveal(),
            $this->prophesize(ToolbarManager::class)->reveal()
        );
        return new PreloadedExtension([$creasteItemType, $ck], []);
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
                    return [new Sprint(SprintDuration::ONE_WEEK(), new User())];
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
        $item->moveToAnotherEpic(new Epic('epic1', new User()));

        $form = $this->factory->create(CreateItemType::class, null, [
            'user' => $user->reveal(),
            'backlog' => $backlog
        ]);

        $form->submit($formData);

        static::assertEquals($item->getPriority(), $form->getData()->getPriority() - 1);
        static::assertEquals($item->getName(), $form->getData()->getName());
    }
}