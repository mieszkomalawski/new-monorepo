<?php


namespace BacklogBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use PHPRum\DomainModel\Backlog\Sprint;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FirstSprintData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var Container
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        $allUsers = $userManager->findUsers();
        // create first sprint for all users
        foreach($allUsers as $user){
            $sprint = new Sprint(
                '1_week',
                $user,
                new ArrayCollection()
            );
            $manager->persist($sprint);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }


}