<?php


namespace Tests\Unit\BacklogBundle\Form;


use BacklogBundle\Entity\Item;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\SelectSprintType;
use BacklogBundle\Form\UpdateItemType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Twig\Extension\CoreExtension;

class UpdateItemTypeTest extends TypeTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function getExtensions()
    {
        $mockEntityManager = $this->createMock(EntityManager::class);
        $mockEntityManager->method('getClassMetadata')
            ->willReturn(new ClassMetadata(Sprint::class))
        ;
        $entityRepository = $this->createMock(EntityRepository::class);
        $entityRepository->method('createQueryBuilder')
            ->willReturn(new QueryBuilder($mockEntityManager))
        ;
        $mockEntityManager->method('getRepository')->willReturn($entityRepository);
        $mockRegistry = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(['getManagerForClass'])
            ->getMock()
        ;
        $mockRegistry->method('getManagerForClass')
            ->willReturn($mockEntityManager)
        ;
        /** @var EntityType|\PHPUnit_Framework_MockObject_MockObject $mockEntityType */
        $mockEntityType = $this->getMockBuilder(EntityType::class)
            ->setConstructorArgs([$mockRegistry])
            ->setMethodsExcept(['configureOptions', 'getParent'])
            ->getMock()
        ;
        $mockEntityType->method('getLoader')->willReturnCallback(function ($a, $b, $class) {
            return new class($class) implements EntityLoaderInterface
            {
                /**
                 * @var
                 */
                private $class;
                /**
                 *  constructor.
                 *
                 * @param $class
                 */
                public function __construct($class)
                {
                    $this->class = $class;
                }
                /**
                 * Returns an array of entities that are valid choices in the corresponding choice list.
                 *
                 * @return array The entities
                 */
                public function getEntities()
                {
                    switch ($this->class) {
                        case Sprint::class:
                            return [new Sprint('1_week', new User())];
                            break;
                    }
                }
                /**
                 * Returns an array of entities matching the given identifiers.
                 *
                 * @param string $identifier The identifier field of the object. This method
                 *                           is not applicable for fields with multiple
                 *                           identifiers.
                 * @param array  $values     The values of the identifiers
                 *
                 * @return array The entities
                 */
                public function getEntitiesByIds($identifier, array $values)
                {
                    // TODO: Implement getEntitiesByIds() method.
                }
            };
        })
        ;
        return [
            new class($mockEntityType) implements FormExtensionInterface
            {
                private $type;
                public function __construct($type)
                {
                    $this->type = $type;
                }
                public function getType($name)
                {
                    return $this->type;
                }
                public function hasType($name)
                {
                    return $name === EntityType::class;
                }
                public function getTypeExtensions($name)
                {
                    return [];
                }
                public function hasTypeExtensions($name)
                {
                }
                public function getTypeGuesser()
                {
                }
            },
        ];
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
            'priority' => 3,
            'status' => Item::STAUS_IN_PROGRESS,
            'Sprint' => $sprint
        ];

        $object = new Item(
            'old_name',
            $user
        );


        $form = $this->factory->create(UpdateItemType::class, $object, [
            'sprint_query' => function () {
                return $this->getMockBuilder(QueryBuilder::class)->disableOriginalConstructor()->getMock();
            }
        ]);

        $object->setEstimate(5);
        $object->setPriority(3);
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