<?php


namespace Tests\Unit\BacklogBundle\Form;

use BacklogBundle\Entity\Epic;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Service\CreatorJailer;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Prophecy\Argument;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class EntityAwareTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        /** @var EntityManager $mockEntityManager */
        $mockEntityManager = $this->prophesize(EntityManager::class);
        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->prophesize(EntityRepository::class);
        /** @var Registry $mockRegistry */
        $mockRegistry = $this->prophesize(Registry::class);

        $mockEntityManager->getClassMetadata(Argument::any())->willReturn(new ClassMetadata(Sprint::class));

        $entityRepository
            ->createQueryBuilder(Argument::any())
            ->willReturn(new QueryBuilder($mockEntityManager->reveal()));

        $mockEntityManager->getRepository(Argument::any())->willReturn($entityRepository->reveal());

        $mockRegistry->getManagerForClass(Argument::any())->willReturn($mockEntityManager->reveal());

        /** @var EntityType|\PHPUnit_Framework_MockObject_MockObject $mockEntityType */
        $mockEntityType = $this->getMockBuilder(EntityType::class)
            ->setConstructorArgs([$mockRegistry->reveal()])
            ->setMethodsExcept(['configureOptions', 'getParent'])
            ->getMock();

        $fakeEntities = $this->getFakeEntities();

        $mockEntityType->method('getLoader')->willReturnCallback(function ($a, $b, $class) use ($fakeEntities) {
            return new class($class, $fakeEntities) implements EntityLoaderInterface
            {
                /**
                 * @var
                 */
                private $class;

                private $fakeEntities;

                /**
                 *  constructor.
                 *
                 * @param $class
                 */
                public function __construct($class, $fakeEntities)
                {
                    $this->class = $class;
                    $this->fakeEntities = $fakeEntities;
                }

                /**
                 * Returns an array of entities that are valid choices in the corresponding choice list.
                 *
                 * @return array The entities
                 */
                public function getEntities()
                {
                    $callable = $this->fakeEntities;
                    return $callable($this->class);
                }

                /**
                 * Returns an array of entities matching the given identifiers.
                 *
                 * @param string $identifier The identifier field of the object. This method
                 *                           is not applicable for fields with multiple
                 *                           identifiers.
                 * @param array $values The values of the identifiers
                 *
                 * @return array The entities
                 */
                public function getEntitiesByIds($identifier, array $values)
                {
                    // TODO: Implement getEntitiesByIds() method.
                }
            };
        });

        return [
            $this->getPreloadedExtensions(),
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
     * @param string $class
     * @return callable
     */
    protected function getFakeEntities()
    {
        return function ($class) {

        };

    }

    /**
     * @param $updateItemType
     * @return PreloadedExtension
     */
    protected function getPreloadedExtensions()
    {
        return new PreloadedExtension([], []);
    }

}