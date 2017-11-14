<?php


namespace Tests\Unit\BacklogBundle\Form;


use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\CompoundItem;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Service\CreatorJailer;
use Doctrine\ORM\QueryBuilder;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Ivory\CKEditorBundle\Model\ConfigManager;
use Ivory\CKEditorBundle\Model\PluginManager;
use Ivory\CKEditorBundle\Model\StylesSetManager;
use Ivory\CKEditorBundle\Model\TemplateManager;
use Ivory\CKEditorBundle\Model\ToolbarManager;
use PHPRum\DomainModel\Backlog\ItemStatus;
use PHPRum\DomainModel\Backlog\SprintDuration;
use Symfony\Component\Form\PreloadedExtension;

class UpdateItemTypeTest extends EntityAwareTypeCase
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
                    return [new Sprint(SprintDuration::ONE_WEEK(), new User())];
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
        $ck = new CKEditorType(
            $this->prophesize(ConfigManager::class)->reveal(),
            $this->prophesize(PluginManager::class)->reveal(),
            $this->prophesize(StylesSetManager::class)->reveal(),
            $this->prophesize(TemplateManager::class)->reveal(),
            $this->prophesize(ToolbarManager::class)->reveal()
        );
        return new PreloadedExtension([$updateItemType, $ck], []);
    }

    /**
     * @test
     */
    public function submitValidData()
    {
        $user = new User();
        $sprint = new Sprint(SprintDuration::ONE_WEEK(), $user);
        $formData = [
            'name' => 'new_name',
            'estimate' => 5,
            'status' => ItemStatus::IN_PROGRESS(),
            'Sprint' => $sprint
        ];

        $object = new CompoundItem('old_name', $user);


        $form = $this->factory->create(UpdateItemType::class, $object, [
            'userId' => 1,
            'other_items' => []
        ]);

        $object->setEstimate(5);
        $object->setStatus(ItemStatus::IN_PROGRESS());
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